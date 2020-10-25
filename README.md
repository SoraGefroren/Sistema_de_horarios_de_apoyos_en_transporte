# Sistema de horarios de apoyos en transporte
*******
<div align="justify">
Este proyecto adscribe un mecanismo de ayuda para la visualización y gestión de servicios en transporte, entregados de forma gratuita por alguna institución. Ante lo cual, y para efectos de ejemplificación, se suscribió a dos organizaciones, <i>AHTECA</i> y el <i>DIF de XALAPA</i>.
</div>

## Organización del proyecto

<div align="justify">
La motivación y el proceso de desarrollo de este proyecto, se encuentra descrito en el archivo <strong>Documento.pdf</strong> o <strong>Documento.docx</strong>, que reúne a los elementos suscritos en las siguientes carpetas:
</div>

</br>
<ol>
  <li><div align="justify">Carpeta <strong>"1 - StoryBoard"</strong>: Exponen una serie de ilustraciones a través de la cuales, se narran algunos problemas sobre la entrega de apoyos en transporte, por parte de una institución que ayude con ello a personas que lo necesiten.
  </br>
  </br>
  <div align="center">
    <img src="1 - StoryBoard/sinApp_5.png" width="580" height="350" alt="Ejemplo extraído del StoryBoard" title="Ejemplo extraído del StoryBoard">
  </div>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"2 - Modelo Relacional"</strong>: Muestra un <i>Modelo Relacional</i> basado en la necesidad conseguir una fuente de información con los horarios y fechas en que se otorgan los apoyos en transporte, que permita implementar una instancia de Base de datos, y en ella, almacenar la información utilizada por este proyecto.
  </br>
  </br>
  <div align="center">
    <img src="2 - Modelo Relacional/Modelo.png" width="580" height="350" alt="Modelo Relacional" title="Modelo Relacional">
  </div>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"3 - Diseño de Base de datos"</strong>: Presenta dos scripts <i>.sql</i> para el gestor de Base de datos relacionales <i>MySQL</i>, uno para generar la Base de datos utilizada por el presente proyecto (<i>Crear base de datos.sql</i>), y otro para llenar la Base de datos del sistema con información inicial de ejemplo (<i>Insertar datos.sql</i>). En relación con lo cual, cabe destacar que para poder ejecutar el script <i>Insertar datos.sql</i>, es necesario agregar en el campo <i>contrasenia</i> de cada uno de los <i>INSERT</i> dirigidos a la tabla <i>usr_sys</i>, una contraseña encriptada a través del método <i>password_hash</i> del lenguaje <i>PHP</i>.
  </br>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"4 - Diseño del Prototipo"</strong>: Expone una serie de imagenes sobre el diseño implementado en el mecanismo para la visualización de los apoyos en transporte, el cual, corresponde a una aplicación para dispositivos Android.
  </br>
  </br>
  <div align="center">
    <img src="4 - Diseño del Prototipo/Boceto Preliminar/Vista_1.png" width="580" height="235" alt="Pantalla principal del sistema" title="Pantalla principal del sistema">
  </div>
  </br>
  Por otra parte, cabe agregar que no se realizó un diseño preliminar para el mecanismo de apoyo en la gestión de los servicios de transporte, el cual, se desarrolló con base en el Framework <i>CodeIgniter</i>.
  </br>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"5 - En ejecución"</strong>: Muestra una serie de imagenes correspondientes al despliegue del sistema presentando en este proyecto, el cual, comprende un mecanismo para la visualización de apoyo en transporte y el medio de apoyo para la gestión de apoyos en transporte.
  </br>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"Proyecto_Android"</strong>: Proyecto de <i>Android Studio 3.6.1</i> con el código fuente del mecanismo para la visualización de apoyo en transporte, el cual, ha sido diseño para funcionar y compilarse con <i>Android 5.1 (API level 22)</i> o versiones superiores. Aunque, si se desea realizar pruebas locales (es decir, sin un host en la red), se recomienda compilar este proyecto con <i>Android 6 (API level 23)</i> o versiones superiores. Esto último, debido al uso de la declaración <i>android:usesCleartextTraffic="true"</i> en el tag <i>application</i> del archivo <i>AndroidManifest.xml</i>.
  </br>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"Proyecto_PHP"</strong>: Proyecto basado en el Framework <i>CodeIgniter 3</i> de 2018, cuyo requisito de ejecución es tener <i>PHP 5.3.7</i> o una versión superior. En este carpeta, se encuentra contenido el código fuente una <i>Aplicación Web</i> de apoyo para la gestión de apoyos en transporte, y para su despliegue, es necesario ubicar a esta carpeta en la carpeta donde se busque a la página o aplicación web, por ejemplo, con el Servidor <i>APACHE</i> este proyecto debe ser ubicado de la siguiente forma <i>..\htdocs\Proyecto_PHP</i>.
  </br>
  </br>
  </div></li>
  <li><div align="justify">Carpeta <strong>"Recursos"</strong>: Presenta los logotipos de las dos organizaciones suscritas por este proyecto, <i>AHTECA</i> y el <i>DIF de XALAPA</i>, para dar ejemplo sobre el funcionamiento de este.
  </div></li>
</ol>

## Requerimientos

|Mecanismo|Requisitos mínimos| 
|------|-----|
|<strong>Aplicación Web (basada en <i>"CodeIgniter 3"</i>)</strong>|<p>PHP 5.3.7 o superior<br/>Memoria RAM >= 512 MB<br/>CPU >= 1.0 GHz<br/>Servidor Web (por ejemplo <i>"APACHE"</i>)</br>Base de datos implementada en MySQL</p>|
|<strong>Cliente de Android</strong>|<p>Android 5.1 (Lollipop) o superior<br/>Memoria RAM >= 512 MB<br/>CPU >= 1.0 GHz<br/>Acceso a internet</p>|

## Uso del sistema

#### Aplicación Web

<div align="justify">
El mecanismo de apoyo para la gestión de apoyos en transporte, como se muestra en las siguientes imagenes, en su página principal presenta una tabla con los apoyos en transporte registrados en el sistema, los cuales, son descritos de una manera legible para los usuarios. Además, esta página suscribe dos opciones, una para permitir el acceso de un usuario con la capacidad de modificar la información del sistema y otra opción para ver el formato de los datos enviados al <i>Cliente de Android</i>.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0001.png" width="580" height="170" alt="Pantalla principal de la aplicación web" title="Pantalla principal de la aplicación web">
</div>

</br>
<div align="justify">
A través de la opción para acceder al sistema, el usuario debe ingresar su <i>Correo electrónico</i> y <i>Contraseña</i>, y luego dar clic en el botón <i>Acceder</i>.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0002.png" width="580" height="240" alt="Pantalla de inicio de sesión en la aplicación web" title="Pantalla de inicio de sesión en la aplicación web">
</div>

</br>
<div align="justify">
Luego de acceder al sistema, la interfaz mostrara en la barra superior, una serie de opciones adicionales acordes al tipo de usuario que ingreso.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0003.png" width="580" height="170" alt="Pantalla principal con la sesión iniciada en la aplicación web" title="Pantalla principal con la sesión iniciada en la aplicación web">
</div>

</br>
<div align="justify">
Una de estas opciones es la de <i>Usuarios</i>, la cual lleva a una página que permite el registro y edición de usuarios en el sistema. Ante lo cual, cabe destacar que los controles para el registro o edición de un usuario, solo se habilitaran si el checkbox <i>Estado</i> esta chequeado, ya que esto permite saber si el usuario esta o no activo.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0005.png" width="580" height="280" alt="Pantalla para la edición de usuarios en la aplicación web" title="Pantalla para la edición de usuarios en la aplicación web">
</div>

</br>
<div align="justify">
Tambien, en la misma página de <i>Usuarios</i>, en la parte inferior se presenta una tabla con todos los usuarios registros en el sistema.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0006.png" width="580" height="225" alt="Pantalla para la edición de usuarios en la aplicación web" title="Pantalla para la edición de usuarios en la aplicación web">
</div>

</br>
<div align="justify">
Otra opción es la de <i>Patrocinadores</i>, que al igual a la opción <i>Usuarios</i>, presenta una página con una serie de controles para el registro y edición de patrocinadores (instituciones u organizaciones que prestan apoyo en transporte a personas que lo requieran), y en la parte inferior, expone una tabla con todos los patrocinadores registrados en el sistema.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0007.png" width="580" height="300" alt="Pantalla para la edición de patrocinadores en la aplicación web" title="Pantalla para la edición de patrocinadores en la aplicación web">
</div>

</br>
<div align="justify">
Por otra parte, una opción más es la de <i>Apoyos</i>, que al igual a las opciones anteriores, presenta una página con una serie de controles para el registro y la edición de apoyos en transporte, distinguidos por fecha, hora y el patrocinador del mismo apoyo. Además, esta página muestra en la parte inferior de la página, una tabla con todos los apoyos registrados en el sistema.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Test-Web-0008.png" width="580" height="300" alt="Pantalla para la edición de apoyos en transporte de la aplicación web" title="Pantalla para la edición de apoyos en transporte de la aplicación web">
</div>

</br>
<div align="justify">
Finalmente, la opción <i>Json</i> o <i>Ver apoyos (JSON)</i> (presentada en la página principal de este mecanismo), permite observar el formato y conjunto de datos que la <i>Aplicación Web</i> envía al <i>Cliente de Android</i>.
</div>

</br>

```js
[
  {
    "tipo":"Rango",
    "hay_apy":"1",
    "dia_ini":"2020-10-23",
    "dia_fin":"2020-10-25",
    "hr_ini":"08:00:00",
    "hr_fin":"15:00:00",
    "patron":"DIF Xalapa",
    "direccion":"Calle Licenciado Jorge Cerdan S\/N, Adolfo Lopez Mateos, 91020 Xalapa Enr\u00edquez, Ver.",
    "tels":"012288220008|"
    }, ..., {...}
  ]
```

</br>
</br>

#### Cliente de Android

<div align="justify">

<div align="justify">
El mecanismo para la visualización de apoyos en transporte, como se muestra en las siguiente imagenes, requiere conocer la dirección IP del sitio en donde se encuentra alojada la <i>Aplicación Web</i>. De manera que, para compilar y hacer uso de este mecanismo, es necesario modificar la cadena <i>http:// Dirección IP de tu dominio /Proyecto_PHP/...</i> del método <i>doInBackground</i> perteneciente la <i>AsyncTask</i> anónima del método <i>consultarDatos</i> de la clase <i>MainActivity</i> del <i>Proyecto de Android</i>.
</div>

</br>

```java
// Función que realiza llamada remota
private void consultarDatos() {
  (new AsyncTask <Void, Void, Void>() {
    // Variables
    private String LineJD = ""; // String auxiliar para linea de datos JSON
    private String strJsonData = ""; // String de datos JSON
    // Petición
    @Override
    protected Void doInBackground(Void... params) {
      try {
        // Obtener fecha actual del sistema
        String date = new SimpleDateFormat("yyyy-MM-dd").format(new Date());
        // Construir URL a solicitar
        URL url = new URL("http:// Dirección IP de tu dominio /Proyecto_PHP/index.php/apoyo_en_transporte/informacion/" + date);
        ...
```

</br>
<div align="justify">
En relación con lo cual, resultan importante mencionar que, de no modificar la cadena <i>http:// Dirección IP de tu dominio /Proyecto_PHP/...</i>, el sistema fallara al buscar los datos que debe mostrar a su usuario.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Screenshot_20201023_000821_mx.tecinteractiva.sora.apoyoentransporte.jpg" width="290" height="580" alt="Pantalla principal del Cliente de Android" title="Pantalla principal del Cliente de Android">
</div>

</br>
<div align="justify">
Por otra parte, ante una configuración correcta de este mecanismo y al existir apoyos de transporte disponibles en las siguientes horas o concurrentes a la hora actual, el sistema mostrara en su pantalla principal el primer apoyo más cercano a la hora actual. En donde, si se da un toque sobre el icono de <i>Calendario</i> de la parte superior, se desplegará una lista con los apoyos en transporte disponibles, y, tambien en la pantalla principal, si se da un toque sobre el icono de un <i>Persona esperando transporte</i> entonces se desplegará una lista con información sobre las instituciones o patrocinadores que prestar el apoyo.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Screenshot_20201023_001913_mx.tecinteractiva.sora.apoyoentransporte.jpg" width="290" height="580" alt="Pantalla principal del Cliente de Android" title="Pantalla principal del Cliente de Android">
</div>

</br>
<div align="justify">
Además, cabe destacar que, si se está en la pantalla donde se desplego una lista con los apoyos disponibles y se da un toque sobre alguno de estos apoyos, entonces se desplegará una lista con información de los patrocinadores que prestan el apoyo seleccionado.
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Screenshot_20201023_002000_mx.tecinteractiva.sora.apoyoentransporte.jpg" width="290" height="580" alt="Pantalla principal del Cliente de Android" title="Pantalla principal del Cliente de Android">
</div>

</br>
<div align="center">
  <img src="5 - En ejecución/Screenshot_20201023_001931_mx.tecinteractiva.sora.apoyoentransporte.jpg" width="290" height="580" alt="Pantalla principal del Cliente de Android" title="Pantalla principal del Cliente de Android">
</div>

*******
## Créditos

Autor: *Jorge Luis Jácome Domínguez*

######  Otros medios < [Linkedin](https://www.linkedin.com/in/jorge-luis-j%C3%A1come-dom%C3%ADnguez-44294a91/) - [Dibujando](https://dibujando.net/soragefroren) - [Facebook](https://www.facebook.com/SoraGefroren) - [Youtube](https://www.youtube.com/c/SoraGefroren) >

