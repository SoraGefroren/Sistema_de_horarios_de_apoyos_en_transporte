-- Datos iniciales

-- -----------------------------------------------------
-- Schema de Horarios de transporte
-- -----------------------------------------------------
USE `horarios_de_transporte` ;

-- AGREGAR DATOS A LA TABLA DE TIPO DE USUARIOS
INSERT INTO tipo_usr (tipo)
VALUES ('super');
INSERT INTO tipo_usr (tipo)
VALUES ('director');
INSERT INTO tipo_usr (tipo)
VALUES ('patron');
-- AGREGAR DATOS DE USUARIO DEL SYSTEMA
INSERT INTO usr_sys (nombre, ap_paterno, ap_materno, correo, contrasenia, estado, reg_tipo)
VALUES ('Maria', 'Gonzales', 'Herrera', 'maria@hua.com', '', true, 1);
INSERT INTO usr_sys (nombre, ap_paterno, ap_materno, correo, contrasenia, estado, reg_tipo)
VALUES ('Samanta', 'Gonzales', 'Herrera', 'samanta@hua.com', '', true, 2);
INSERT INTO usr_sys (nombre, ap_paterno, ap_materno, correo, contrasenia, estado, reg_tipo)
VALUES ('Xarin', 'Gonzales', 'Herrera', 'xarin@hua.com', '', true, 3);
INSERT INTO usr_sys (nombre, ap_paterno, ap_materno, correo, contrasenia, estado, reg_tipo)
VALUES ('Yazhin', 'Gonzales', 'Herrera', 'yazhin@hua.com', '', true, 3);
-- AGREGAR DATOS A LA TABLA SOBRE EL PATROCINIO
INSERT INTO patrocinador (patron, direccion, tels)
VALUES ('AHTECA', 'Aguascalientes 123, Progreso Macuiltepetl, 91130 Xalapa Enríquez, Ver.', '012288407944|');
INSERT INTO patrocinador (patron, direccion, tels)
VALUES ('DIF Xalapa', 'Calle Licenciado Jorge Cerdan S/N, Adolfo Lopez Mateos, 91020 Xalapa Enríquez, Ver.', '012288220008|');
-- AGREGAR DATOS A LA TABLA SOBRE LA RELACIÓN DEL PATROCINIO CON LOS USUARIOS
INSERT INTO rel_patron_usr (reg_usr, reg_patron)
VALUES (3,1);
-- INSERT INTO rel_patron_usr (reg_usr, reg_patron)
-- VALUES (4,2);
-- AGREGAR DATOS A LA TABLA DE TIPO DE APOYO
INSERT INTO tipo_apy (tipo)
VALUES ('Por dia');

INSERT INTO tipo_apy (tipo)
VALUES ('Rango');
-- AGREGAR DATOS A LA TABLA DE APOYO EN TRANSPORTE
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (2, true, '2018-12-25', '2018-12-29', '09:00:00', '11:00:00', 2);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (2, false, '2018-12-30', '2019-01-06', '09:00:00', '11:00:00', 2);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (1, true, '2019-01-07', NULL, '10:30:00', '13:00:00', 2);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (2, true, '2018-12-25', '2018-12-28', '10:00:00', '12:00:00', 1);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (2, false, '2018-12-29', '2019-01-03', NULL, NULL, 1);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (1, true, '2019-01-04', NULL, '09:00:00', '12:30:00', 1);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (1, false, '2019-01-05', NULL, '10:00:00', '11:00:00', 1);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (1, false, '2019-01-06', NULL, '10:00:00', '11:00:00', 1);
INSERT INTO apy_transport (reg_tipo, hay_apy, dia_ini, dia_fin, hr_ini, hr_fin, reg_patron)
VALUES (1, true, '2019-01-07', NULL, '10:00:00', '11:00:00', 1);

-- -----------------------------------------------------
-- -----------------------------------------------------
-- -----------------------------------------------------