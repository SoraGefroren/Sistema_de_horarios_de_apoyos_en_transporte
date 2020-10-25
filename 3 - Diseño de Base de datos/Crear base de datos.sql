-- MySQL Workbench Forward Engineering - Adaptado

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema de Horarios de transporte
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `horarios_de_transporte` DEFAULT CHARACTER SET utf8 ;
USE `horarios_de_transporte` ;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`tipo_usr`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`tipo_usr` (
  `reg` INT AUTO_INCREMENT,
  `tipo` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`reg`),
  UNIQUE KEY (`tipo`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`usr_sys`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`usr_sys` (
  `reg` INT AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `ap_paterno` VARCHAR(25) NOT NULL,
  `ap_materno` VARCHAR(25),
  `correo` VARCHAR(50) NOT NULL,
  `contrasenia` TEXT NOT NULL,
  `estado` BOOLEAN NOT NULL,
  `reg_tipo` INT NOT NULL,
  PRIMARY KEY (`reg`),
  FOREIGN KEY (`reg_tipo`) REFERENCES `horarios_de_transporte`.`tipo_usr`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`patrocinador`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`patrocinador` (
  `reg` INT AUTO_INCREMENT,
  `patron` VARCHAR(50) NOT NULL,
  `direccion` VARCHAR(100) NOT NULL,
  `tels` VARCHAR(120) NOT NULL,
  PRIMARY KEY (`reg`),
  UNIQUE KEY (`patron`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`rel_patron_usr`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`rel_patron_usr` (
  `reg_usr` INT NOT NULL,
  `reg_patron` INT NOT NULL,
  FOREIGN KEY (`reg_usr`) REFERENCES `horarios_de_transporte`.`usr_sys`(`reg`),
  FOREIGN KEY (`reg_patron`) REFERENCES `horarios_de_transporte`.`patrocinador`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`tipo_apy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`tipo_apy` (
  `reg` INT AUTO_INCREMENT,
  `tipo` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`reg`),
  UNIQUE KEY (`tipo`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`apy_transport`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`apy_transport` (
  `reg` INT AUTO_INCREMENT,
  `reg_tipo` INT NOT NULL,
  `hay_apy` BOOLEAN NOT NULL,
  `dia_ini` DATE NOT NULL,
  `dia_fin` DATE,
  `hr_ini` TIME,
  `hr_fin` TIME,
  `reg_patron` INT NOT NULL,
  PRIMARY KEY (`reg`),
  FOREIGN KEY (`reg_tipo`) REFERENCES `horarios_de_transporte`.`tipo_apy`(`reg`),
  FOREIGN KEY (`reg_patron`) REFERENCES `horarios_de_transporte`.`patrocinador`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`act_apy_transport`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`act_apy_transport` (
  `reg_usr` INT,
  `reg_apy` INT,
  `fecha` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `resumen` TEXT NOT NULL,
  `adicional` TEXT,
  FOREIGN KEY (`reg_usr`) REFERENCES `horarios_de_transporte`.`usr_sys`(`reg`),
  FOREIGN KEY (`reg_apy`) REFERENCES `horarios_de_transporte`.`apy_transport`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`accion_usr`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`accion_usr` (
  `reg_usr` INT,
  `fecha` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `resumen` TEXT NOT NULL,
  FOREIGN KEY (`reg_usr`) REFERENCES `horarios_de_transporte`.`usr_sys`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`act_usr`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`act_usr` (
  `reg_usr` INT,
  `reg_afect` INT,
  `fecha` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `resumen` TEXT NOT NULL,
  `adicional` TEXT,
  FOREIGN KEY (`reg_usr`) REFERENCES `horarios_de_transporte`.`usr_sys`(`reg`),
  FOREIGN KEY (`reg_afect`) REFERENCES `horarios_de_transporte`.`usr_sys`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `horarios_de_transporte`.`act_patron`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `horarios_de_transporte`.`act_patron` (
  `reg_usr` INT,
  `reg_afect` INT,
  `fecha` DATE NOT NULL,
  `hora` TIME NOT NULL,
  `resumen` TEXT NOT NULL,
  `adicional` TEXT,
  FOREIGN KEY (`reg_usr`) REFERENCES `horarios_de_transporte`.`usr_sys`(`reg`),
  FOREIGN KEY (`reg_afect`) REFERENCES `horarios_de_transporte`.`patrocinador`(`reg`)
)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- -----------------------------------------------------
-- -----------------------------------------------------

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;