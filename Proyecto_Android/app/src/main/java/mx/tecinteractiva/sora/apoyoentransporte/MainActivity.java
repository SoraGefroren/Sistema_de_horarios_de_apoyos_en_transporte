package mx.tecinteractiva.sora.apoyoentransporte;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;

import android.view.View;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    // Variables para ICONOS
    private ImageView imgViewEstado;
    private ImageView imgViewCalendario;
    private ImageView imgViewEstadoDer;
    private ImageView imgViewEstadoIzq;
    private ImageView imgViewBusDer;
    private ImageView imgViewBusIzq;
    private ImageView imgViewPicoOscuro;
    private ImageView imgViewPicoOscuroM;
    private ImageView imgViewLineaConSol;
    private ImageView imgViewEstacion;
    // Variables para TEXTO
    private TextView textViewMsj;
    private TextView textViewFecha;
    private TextView textViewHorario;
    private TextView textViewNota;
    // Variables de apoyo
    private JSONArray jsonAry = null;
    private static ArrayList<Map> dataAryList = new ArrayList<Map>();
    private static Map<String, String> dataDictionary = new HashMap<String, String>();
    private static Map<String, String> dataDictionaryApy = new HashMap<String, String>();

    // Función que realiza llamada remota
    private void consultarDatos() {
        (new AsyncTask <Void, Void, Void>() {
            // Variables
            private String LineJD = ""; // String auxiliar para linea de datos JSON
            private String strJsonData = ""; // String de datos JSON
            // Peticion
            @Override
            protected Void doInBackground(Void... params) {
                try {
                    // Obtener fecha actual del sistema
                    String date = new SimpleDateFormat("yyyy-MM-dd").format(new Date());
                    // Construir URL a solicitar
                    URL url = new URL("http://192.168.100.18/Proyecto_PHP/index.php/apoyo_en_transporte/informacion/" + date);
                    // Solicitar información
                    BufferedReader bufferRdr = new BufferedReader(
                            new InputStreamReader(
                                    ((HttpURLConnection) url.openConnection()).getInputStream()
                            )
                    );
                    // Mientras la linea de datos JSON no sea nula
                    while (LineJD != null) {
                        // Leer nuevo linea de datos JSON
                        LineJD = bufferRdr.readLine();
                        // Concatenar linea a String para los datos JSON
                        strJsonData += LineJD;
                    }
                    // Parsear y salvar datos en un arreglo JSON
                    jsonAry = new JSONArray(strJsonData);
                } catch (Exception e) {
                    jsonAry = null;
                }
                return null;
            }
            @Override
            protected void onPostExecute(Void result) {
                // Configurar el arreglo JSON
                configurarArregloDeDatos();
                // Configurar la interface
                configurarInterface();
                // Configurar los elementos de la interface
                configurarElementos();
            }
        }).execute();
    }

    // Constructor
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // **********************************************
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // **********************************************
        // ----------------------------------------------
        // Obtener referencia a elementos de la Actividad
        // Icono de ESTADO
        imgViewEstado = (ImageView) findViewById(R.id.imgViewEstado);
        // Icono de CALENDARIO
        imgViewCalendario = (ImageView) findViewById(R.id.imgViewCalendario);
        // Icono de PICO, el color OBSCURO o la sombra del mismo
        imgViewPicoOscuro = (ImageView) findViewById(R.id.imgViewPicoObscuro);
        imgViewPicoOscuroM = (ImageView) findViewById(R.id.imgViewPicoObscuroM);
        // Iconos de SOL y LUNA
        imgViewLineaConSol = (ImageView) findViewById(R.id.imgViewLinea);
        // Iconos de SOL y LUNA
        imgViewEstacion = (ImageView) findViewById(R.id.imgViewEstacion);

        // ----------------------------------------------
        // Elementos de texto: MENSAJE, FECHA y HORARIO
        textViewMsj = (TextView) findViewById(R.id.textViewMsj);
        textViewMsj.setText("Cargando...");
        textViewFecha = (TextView) findViewById(R.id.textViewFecha);
        textViewFecha.setText("");
        textViewHorario = (TextView) findViewById(R.id.textViewHorario);
        textViewHorario.setText("");
        textViewNota = (TextView) findViewById(R.id.textViewNota);
        textViewNota.setText("");

        // **********************************************
        // ----------------------------------------------
        // Iconos de ESTADO DER e IZQ
        imgViewEstadoDer = (ImageView) findViewById(R.id.imgViewEstadoDer);
        imgViewEstadoIzq = (ImageView) findViewById(R.id.imgViewEstadoIzq);
        // Iconos de BUS DER e IZQ
        imgViewBusDer = (ImageView) findViewById(R.id.imgViewBusDer);
        imgViewBusIzq = (ImageView) findViewById(R.id.imgViewBusIzq);

        // ----------------------------------------------
        // Establecer alfa para elementos de estado
        imgViewEstadoDer.setImageAlpha(15);
        imgViewEstadoIzq.setImageAlpha(15);
        // Establecer alfa para autobuses
        imgViewBusDer.setImageAlpha(15);
        imgViewBusIzq.setImageAlpha(15);

        // **********************************************
        // ----------------------------------------------
        this.consultarDatos();
    }

    // Reiniciar variables de datos: Arreglo y Diccionario
    private void reloadDataVars () {
        dataAryList = new ArrayList<Map>();
        dataDictionary = new HashMap<String, String>();
        dataDictionaryApy = new HashMap<String, String>();
    }

    // Función para configura el arreglo de datos
    private void configurarArregloDeDatos(){
        // Reiniciar arreglo
        reloadDataVars();
        // Validar Datos JSON
        if (jsonAry != null) {
            // Variables de apoyo al proceso
            String djTipo = ""; // Tipo de apoyo (Por dia, Rango)
            String djHayApy = ""; // Hay apoyo (0 = NO, 1 = SI)
            String djDiaIni = ""; // Y-m-a
            String djDiaFin = ""; // Y-m-a
            String djHrIni = ""; // H:i:s
            String djHrFin = ""; // H:i:s
            String patron = ""; // PATROCIONADOR
            String dirP = ""; // Dirección del PATROCIONADOR
            String telsP = ""; // Telefonos del PATROCIONADOR
            // Evaluar data
            try {
                // Recorrer arreglo de datos JSON
                for (int i = 0; i < jsonAry.length(); i++) {
                    // Obtener linea JSON
                    JSONObject jsonObj = (JSONObject) jsonAry.get(i);
                    // ------------------------------
                    // ------------------------------
                    // Recuperar valores obligatorios
                    // ********* Tipo de apoyo (Por dia, Rango)
                    if (jsonObj.isNull("tipo")) {
                        djTipo = "";
                    } else {
                        djTipo = (String) jsonObj.get("tipo");
                    }
                    // ********* Hay apoyo (0 = NO, 1 = SI)
                    if (jsonObj.isNull("hay_apy")) {
                        djHayApy = "";
                    } else {
                        djHayApy = (String) jsonObj.get("hay_apy");
                    }
                    // ********* Y-m-a
                    if (jsonObj.isNull("dia_ini")) {
                        djDiaIni = "";
                    } else {
                        djDiaIni = (String) jsonObj.get("dia_ini");
                    }
                    // Recuperar valores no obligatorios
                    if (jsonObj.isNull("dia_fin")) { // Y-m-a
                        djDiaFin = "";
                    } else {
                        djDiaFin = (String) jsonObj.get("dia_fin");
                    }
                    if (jsonObj.isNull("hr_ini")) { // H:i:s
                        djHrIni = "";
                    } else {
                        djHrIni = (String) jsonObj.get("hr_ini");
                    }
                    if (jsonObj.isNull("hr_fin")) { // H:i:s
                        djHrFin = "";
                    } else {
                        djHrFin = (String) jsonObj.get("hr_fin");
                    }
                    // Recuperar valores adicionales
                    if (jsonObj.isNull("patron")) { // PATROCIONADOR
                        patron = "";
                    } else {
                        patron = (String) jsonObj.get("patron");
                    }
                    if (jsonObj.isNull("direccion")) { // Dirección del PATROCIONADOR
                        dirP = "";
                    } else {
                        dirP = (String) jsonObj.get("direccion");
                    }
                    if (jsonObj.isNull("tels")) { // Telefonos del PATROCIONADOR
                        telsP = "";
                    } else {
                        telsP = (String) jsonObj.get("tels");
                    }
                    // ------------------------------
                    // ------------------------------
                    formarRegsDeApoyo(djTipo, djHayApy, djDiaIni, djDiaFin, djHrIni, djHrFin, patron, dirP, telsP);
                    // ------------------------------
                    // ------------------------------
                }
            } catch (Exception e) {
                // Reiniciar arreglo
                reloadDataVars();
            }
        }
    }

    // Función, dado los datos del apoyo, se forman los reqistros para el mismo
    private void formarRegsDeApoyo (String djTipo, String djHayApy, String djDiaIni, String djDiaFin, String djHrIni, String djHrFin, String patron, String dirP, String telsP) {
        // Evaluar data
        try {
            // Fecha actual del sistema
            Date dateHoy = new Date();
            // Se inicializa el forma en el cual se espera que vengan las fechas del JSON
            SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
            // Transformar fecha actual al formato de fecha en datos JSON
            dateHoy = dateFormat.parse(dateFormat.format(dateHoy));
            // ------------------------------
            // ------------------------------
            // Variables de apoyo
            Date mDate = null; // Mi variable de fecha
            Date dateIni = null; // Fecha de inicio
            Date dateFin = null; // Fecha de termino
            Calendar calDatIni = null; // Fecha inicial como calendario
            Calendar calDatFin = null; // Fecha final como calendario
            // ------------------------------
            // ------------------------------
            // Evaluar tipo de dato
            if (djTipo.equals("Rango")) { // Si es por un rango de fechas
                // Siempre que sea por Rango, debe existir la fecha final
                if (((djDiaFin != null) && !djDiaFin.equals("") && !djDiaFin.equals("0000-00-00"))) {
                    // Parsear fechas a objeto fecha
                    dateIni = dateFormat.parse(djDiaIni);
                    dateFin = dateFormat.parse(djDiaFin);
                    // Parsear objetos fecha a objeto calendario
                    calDatIni = Calendar.getInstance();
                    calDatFin = Calendar.getInstance();
                    calDatIni.setTime(dateIni);
                    calDatFin.setTime(dateFin);
                    // Ciclo desde la fecha inicial hasta la fecha final
                    for (mDate = calDatIni.getTime(); // Obtener la fecha inicial en el calendario
                         calDatIni.before(calDatFin); // Pregunta si la fecha inicial en el calendario ha pasado a la fecha final de otro calendario
                         calDatIni.add(Calendar.DATE, 1), mDate = calDatIni.getTime() // Incrementa en un dia la fecha en el calendario inicial y asigna esta nueva fecha a un objeto fecha
                        ) {
                        // Validar fecha, si la fecha actual es igual a la fecha obtenida o si la fecha actual es anterior a la fecha obtenida
                        salvarRegDeApoyo(dateHoy, mDate, djHayApy, djHrIni, djHrFin, patron, dirP, telsP);
                    }
                    // Generalmente la última fecha no es validada por eso se tiene que validar
                    salvarRegDeApoyo(dateHoy, mDate, djHayApy, djHrIni, djHrFin, patron, dirP, telsP);
                } else {
                    // Ajusta fecha la fecha inicial
                    dateIni = dateFormat.parse(djDiaIni);
                    salvarRegDeApoyo(dateHoy, dateIni, djHayApy, djHrIni, djHrFin, patron, dirP, telsP);
                }
            } else if (djTipo.equals("Por dia") ) { // Si es por dia
                // Ajusta fecha la fecha inicial
                dateIni = dateFormat.parse(djDiaIni);
                salvarRegDeApoyo(dateHoy, dateIni, djHayApy, djHrIni, djHrFin, patron, dirP, telsP);
            }
        } catch (Exception e) {
            // Reiniciar arreglo
            reloadDataVars();
        }
    }

    // Función para salvar un nuevo registro de apoyo
    private void salvarRegDeApoyo (Date dateHoy, Date mDate, String djHayApy, String djHrIni, String djHrFin, String patron, String dirP, String telsP) {
        // Evaluar data
        try {
            // Validar que la fecha inicial es igual o pasa a la fecha actual
            if((mDate != null) && (dateHoy.equals(mDate) || dateHoy.before(mDate))) {
                // Dar formato a fecha inicial
                String djDiaApy = new SimpleDateFormat("dd/MM/yyyy").format(mDate);
                // Variable diccionario
                Map<String, String> mDictionary = generarDiccionario(true, djHayApy, djDiaApy, djHrIni, djHrFin, patron, dirP, telsP);
                // ----------------------------
                // Variables de apoyo
                int arySize = dataAryList.size();
                boolean agregarRA = true;
                // Recorrer arreglo
                for (int i = 0; i < arySize; i++) {
                    // Construir un nuevo item y grabarlo en arreglo
                    Map<String, String> dataDic = dataAryList.get(i);
                    if (dataDic.get("fecha").equals(mDictionary.get("fecha"))) {
                        // Puede haber y no haber apoyo dicho dia
                        djHayApy = dataDic.get("hay_apoyo") + "⌂∆⌂" + mDictionary.get("hay_apoyo");
                        // Recuperar horarios
                        djHrIni = dataDic.get("hora_ini") + "⌂∆⌂" + djHrIni;
                        djHrFin = dataDic.get("hora_fin") + "⌂∆⌂" + djHrFin;
                        // Recuperar valores de patron y actualizarlos
                        patron = dataDic.get("patrones") + "⌂∆⌂" + patron;
                        dirP = dataDic.get("dirsPatrones") + "⌂∆⌂" + dirP;
                        telsP = dataDic.get("telsPatrones") + "⌂∆⌂" + telsP;
                        // No agregar registro en apoyo
                        agregarRA = false;
                        // Reconstruir diccionario
                        mDictionary = generarDiccionario(false, djHayApy, djDiaApy, djHrIni, djHrFin, patron, dirP, telsP);
                        // Actualizar item
                        dataAryList.set(i, mDictionary);
                    }
                }
                // Validar si agregar o no el registro en apoyo
                if (agregarRA) {
                    // Guardar en diccionario
                    dataAryList.add(mDictionary);
                }
                // Validar si la fecha es hoy
                if (dateHoy.equals(mDate)) {
                    dataDictionary = mDictionary;
                }
            }
        } catch (Exception e) {
            // Reiniciar arreglo
            reloadDataVars();
        }
    }

    // Función para generar el diccionario del arreglo de datos
    private Map generarDiccionario(Boolean valHayApy, String hayApy, String dia, String hrIni, String hrFin, String patrones, String dirsP, String telsP) {
        // Define diccionario
        Map<String, String> dictionary = new HashMap<String, String>();
        // Dar formato a valores
        if (valHayApy) {
            if (hayApy.equals("1") || (hayApy.equals("Si"))) {
                dictionary.put("hay_apoyo", "Si");
            } else {
                dictionary.put("hay_apoyo", "No");
            }
        } else {
            dictionary.put("hay_apoyo", hayApy);
        }
        // Asignar fecha y horario
        dictionary.put("fecha", dia);
        dictionary.put("hora_ini", hrIni);
        dictionary.put("hora_fin", hrFin);
        dictionary.put("patrones", patrones);
        dictionary.put("dirsPatrones", dirsP);
        dictionary.put("telsPatrones", telsP);
        // Regresar diccionario
        return dictionary;
    }

    // Función para recuperar valores de diccionario
    public static Map<String, String> transformarDataDic (Map<String, String> dataDic) {
        // Nuevo mapa
        Map<String, String> mDicctionary = new HashMap<String, String>();
        // Try - Catch
        try {
            // -----------------------------------
            // Se inicializa el forma en el cual se espera que vengan las fechas del JSON
            SimpleDateFormat dateFormat = new SimpleDateFormat("dd/MM/yyyy");
            // Recupera fecha de registro
            String fecha = dataDic.get("fecha");
            // Fecha actual del sistema
            Date dateHoy = dateFormat.parse(dateFormat.format(new Date()));
            // Parsear fechas a objeto fecha
            Date dateObj = dateFormat.parse(fecha);

            // Variables de apoyo
            Boolean esHoy = false;
            long varDiferencia = 0;
            Date varMejorHora = null;
            Boolean validarDiferencia = true;

            // Validar si la fecha coincide con el día de hoy
            if (dateHoy.equals(dateObj)) {
                esHoy = true;
            }

            // -----------------------------------
            // Asignar valores
            mDicctionary.put("fecha", fecha);

            // Asignar valores originales
            mDicctionary.put("hay_apoyo", dataDic.get("hay_apoyo"));
            mDicctionary.put("hora_ini", dataDic.get("hora_ini"));
            mDicctionary.put("hora_fin", dataDic.get("hora_fin"));
            mDicctionary.put("patrones", dataDic.get("patrones"));
            mDicctionary.put("dirsPatrones", dataDic.get("dirsPatrones"));
            mDicctionary.put("telsPatrones", dataDic.get("telsPatrones"));

            // Variables
            String hayApy = "";
            String hrIni = "";
            String hrFin = "";

            // Se prepara un formato de 24 hrs
            SimpleDateFormat hr24Format = new SimpleDateFormat("HH:mm");
            DateFormat hourFormat = new SimpleDateFormat("HH:mm:ss");

            // -----------------------------------
            // Obtener arreglos
            String[] aryHayApy =  dataDic.get("hay_apoyo").split("⌂∆⌂");
            String[] aryHrsIni =  dataDic.get("hora_ini").split("⌂∆⌂");
            String[] aryHrsFin =  dataDic.get("hora_fin").split("⌂∆⌂");
            String[] aryPatrones =  dataDic.get("patrones").split("⌂∆⌂");
            String[] aryDirsP =  dataDic.get("dirsPatrones").split("⌂∆⌂");
            String[] aryTelsP =  dataDic.get("telsPatrones").split("⌂∆⌂");

            // Todos los elementos deberian tener el mismo tamaño
            int numElemts_AHA = aryHayApy.length,
                numElemts_AHI = aryHrsIni.length,
                numElemts_AHF = aryHrsFin.length,
                numElemts_APs = aryPatrones.length,
                numElemts_ADP = aryDirsP.length,
                numElemts_ATP = aryTelsP.length,
                contador = 0,
                posDMH = -1;

            for (int i = 0; i < numElemts_APs; i++) {
                // Contador
                contador ++;
                // Datos del apoyo
                if (i < numElemts_AHA) {
                    hayApy = aryHayApy[i];
                    mDicctionary.put("hay_apoyo" + i, hayApy);
                } else {
                    mDicctionary.put("hay_apoyo" + i, "");
                }
                if (i < numElemts_AHI) {
                    hrIni = aryHrsIni[i];
                    mDicctionary.put("hora_ini" + i, hrIni);
                } else {
                    hrIni = "";
                    mDicctionary.put("hora_ini" + i, "");
                }
                if (i < numElemts_AHF) {
                    hrFin = aryHrsFin[i];
                    mDicctionary.put("hora_fin" + i, hrFin);
                } else {
                    hrFin = "";
                    mDicctionary.put("hora_fin" + i, "");
                }
                // Datos del patron
                if (i < numElemts_APs) {
                    mDicctionary.put("patrones" + i, aryPatrones[i]);
                } else {
                    mDicctionary.put("patrones" + i, "");
                }
                if (i < numElemts_ADP) {
                    mDicctionary.put("dirsPatrones" + i, aryDirsP[i]);
                } else {
                    mDicctionary.put("dirsPatrones" + i, "");
                }
                if (i < numElemts_ATP) {
                    mDicctionary.put("telsPatrones" + i, aryTelsP[i]);
                } else  {
                    mDicctionary.put("telsPatrones" + i, "");
                }

                // Si buscar la mejor hora
                if (esHoy) {
                    // Si hay apoyo
                    if (hayApy.equals("Si")) {
                        // Reiniciar variable
                        String hora = "";
                        // Validar hora inicio
                        if ((hrIni != null) && !hrIni.equals("")) {
                            hora = hrIni;
                            // Validar hora fin
                            if ((hrFin != null) && !hrFin.equals("")) {
                                hora = hrFin; // Se usara la hora mas vieja para el analisis
                            }
                        } else {
                            // Validar hora fin
                            if ((hrFin != null) && !hrFin.equals("")) {
                                hora = hrFin; // Se usara la hora mas vieja para el analisis
                            }
                        }
                        // HORA ACTUAL
                        // Se obtiene la fecha y hora actual
                        Date varHoraActual = new Date();
                        // La fecha y hora actuales se convierten a un formato de 24 hrs
                        varHoraActual = hr24Format.parse(hourFormat.format(varHoraActual));
                        // HORA FINAL
                        // Se parsea la hora a un objeto date
                        Date varHoraTermina = hr24Format.parse(hora);
                        // VALIDACION
                        // Si la hora de finalización ha pasado a la hora actual
                        if (varHoraActual.before(varHoraTermina)) {
                            if (varMejorHora == null) {
                                varMejorHora = varHoraTermina;
                                validarDiferencia = false;
                                posDMH = i;
                            } else {
                                if (varMejorHora.before(varHoraTermina)) {
                                    varMejorHora = varHoraTermina;
                                    posDMH = i;
                                }
                            }
                        } else {
                            if (validarDiferencia && (varMejorHora == null)) {
                                // Diferencia
                                long varD = varHoraActual.getTime() - varHoraTermina.getTime();
                                // Validar diferencia
                                if (varDiferencia < varD) {
                                    varDiferencia = varD;
                                    posDMH = i;
                                }
                            }
                        }
                    }
                } else {
                    // Si hay apoyo -> BUSCAR EL REGISTRO CON EL MAYOR TIEMPO
                    if (hayApy.equals("Si")) {
                        // Reiniciar variable
                        String hora = "";
                        String hora2 = "";
                        // Validar hora inicio
                        if ((hrIni != null) && !hrIni.equals("")) {
                            hora = hrIni;
                            // Validar hora fin
                            if ((hrFin != null) && !hrFin.equals("")) {
                                hora2 = hrFin; // Se usara la hora mas vieja para el analisis
                            }
                        }
                        // Validar horas
                        if (((hora != null) && !hora.equals("")) && ((hora2 != null) && !hora2.equals(""))) {
                            // HORAS
                            // Se parsea la hora a un objeto date
                            Date varHoraInicia = hr24Format.parse(hora);
                            Date varHoraTermina = hr24Format.parse(hora2);
                            // Diferencia
                            long varD = varHoraTermina.getTime() - varHoraInicia.getTime();
                            // Validar diferencia
                            if (varDiferencia < varD) {
                                varDiferencia = varD;
                                posDMH = i;
                            }
                        }
                    }
                }
            }
            // Asignar número de elementos
            mDicctionary.put("tam", (contador + ""));
            mDicctionary.put("pos", (posDMH + ""));
        } catch (Exception e) {}
        // Devolver nuevo diccionario
        return mDicctionary;
    }

    // Funcion para configura la interface
    private void configurarInterface() {
        // Variables de datos
        String fecha = ""; // Fecha (deberia ser la fecha actual)
        String hora_ini = ""; // Horario inicial // H:i:s
        String hora_fin = ""; // Horario de termino // H:i:s
        String hay_apoyo = ""; // Hay apoyo (0 = NO, 1 = SI)

        String patrones = ""; // PATROCIONADORES
        String dirsP = ""; // Dirección de los PATROCIONADORES
        String telsP = ""; // Telefonos de los PATROCIONADORES

        String pos = ""; // Posición

        // Validar que existan valores para las variables
        if (dataDictionary.size() > 0) {
            Map<String, String> mDictionary = transformarDataDic(dataDictionary);
            // Validar que existan valores para las variables
            if (mDictionary.size() > 0) {
                // Recupera posición
                pos = mDictionary.get("pos");
                // Si no hay apoyo alguno para este dia
                if (pos.equals("-1")) {
                    pos = "0";
                }
                // Si existen valores para las variables, estos seran asignados
                fecha = mDictionary.get("fecha");
                // HORA
                hora_ini = mDictionary.get("hora_ini" + pos);
                hora_fin = mDictionary.get("hora_fin" + pos);
                // ¿HAY?
                hay_apoyo = mDictionary.get("hay_apoyo" + pos);
                // Resguardar
                dataDictionaryApy = mDictionary;
            }
        }

        // Reiniciar pico
        imgViewPicoOscuro.setRotation(0);
        imgViewPicoOscuroM.setRotation(0);
        // Validar, si no hay datos para configurar
        if (hay_apoyo.equals("") && fecha.equals("") && hora_ini.equals("") && hora_fin.equals("")) {
            // Texto para modo desconocido
            textViewMsj.setText(obtenerInsigniaDeEstado("texto", hay_apoyo, hora_ini, hora_fin, fecha));
            textViewFecha.setText("??/??/??");
            textViewHorario.setText("--:--");
            textViewNota.setText("");
            // Imagen para modo desconocido
            imgViewEstado.setImageResource(R.drawable.ic_interrogacion);
            imgViewEstadoDer.setImageResource(R.drawable.ic_interrogacion);
            imgViewEstadoIzq.setImageResource(R.drawable.ic_interrogacion);
            // Establecer alfa para elementos de estado
            imgViewEstadoDer.setImageAlpha(15);
            imgViewEstadoIzq.setImageAlpha(15);
            // Establecer alfa para autobuses
            imgViewBusDer.setImageAlpha(15);
            imgViewBusIzq.setImageAlpha(15);
        } else {
            // vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
            // vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv

            // Asignar imagen a elemento de estado principal
            switch (obtenerInsigniaDeEstado("simbolo", hay_apoyo, hora_ini, hora_fin, fecha)) {
                case "paloma":
                    imgViewEstado.setImageResource(R.drawable.ic_paloma);
                    imgViewEstadoDer.setImageResource(R.drawable.ic_paloma);
                    imgViewEstadoIzq.setImageResource(R.drawable.ic_paloma);
                    break;
                case "equis":
                    imgViewEstado.setImageResource(R.drawable.ic_equis);
                    imgViewEstadoDer.setImageResource(R.drawable.ic_equis);
                    imgViewEstadoIzq.setImageResource(R.drawable.ic_equis);
                    break;
                default:
                    imgViewEstado.setImageResource(R.drawable.ic_interrogacion);
                    imgViewEstadoDer.setImageResource(R.drawable.ic_interrogacion);
                    imgViewEstadoIzq.setImageResource(R.drawable.ic_interrogacion);
                    break;
            }
            // Mensajes
            textViewMsj.setText(obtenerInsigniaDeEstado("texto",hay_apoyo, hora_ini, hora_fin, fecha));
            textViewFecha.setText(fecha);
            textViewHorario.setText(obtenerHorario(hora_ini, hora_fin));
            textViewNota.setText("¿Donde conseguir el apoyo?");
            try {
                // Se prepara un formato de 24 hrs
                SimpleDateFormat hr24Format = new SimpleDateFormat("HH:mm");
                DateFormat hourFormat = new SimpleDateFormat("HH:mm:ss");
                // Se obtiene la fecha y hora actual
                Date horaActual = new Date();
                // La fecha y hora actuales se convierten a un formato de 24 hrs
                horaActual = hr24Format.parse(hourFormat.format(horaActual));
                // Posicionar sol
                posicionarSol(horaActual);
            } catch (Exception e) {
                imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol16);
                // Rotar pico
                imgViewPicoOscuro.setRotation(0);
                imgViewPicoOscuroM.setRotation(0);
            }
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
            // ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        }
    }

    // Función para ocultar o mostrar ciertos autobuses
    private void setAlfaBuses(String dir) {
        switch (dir) {
            case "izq":
                // Asignar alfa
                imgViewBusIzq.setImageAlpha(15);
                imgViewEstadoIzq.setImageAlpha(15);
                imgViewBusDer.setImageAlpha(255);
                imgViewEstadoDer.setImageAlpha(255);
                break;
            case "der":
                // Asignar alfa
                imgViewBusIzq.setImageAlpha(255);
                imgViewEstadoIzq.setImageAlpha(255);
                imgViewBusDer.setImageAlpha(15);
                imgViewEstadoDer.setImageAlpha(15);
                break;
            default:
                // Asignar alfa
                imgViewBusIzq.setImageAlpha(15);
                imgViewEstadoIzq.setImageAlpha(15);
                imgViewBusDer.setImageAlpha(15);
                imgViewEstadoDer.setImageAlpha(15);
                break;
        }
    }

    // Función para posicionar el sol de acuerdo con la hora
    private void posicionarSol(Date horaActual) {
        // Obtener hora actual
        Calendar calActual = Calendar.getInstance();
        calActual.setTime(horaActual);
        // Variables para hora actual
        float hourActual = (float) calActual.get(Calendar.HOUR_OF_DAY),
              minsActual = (float) calActual.get(Calendar.MINUTE),
              partOfGrades = (float) 360/12,
              partOfGraMs = (float) 180/50,
              partOfHrs = (float) 24/32;
        float posH = 0;
        // Obtener posicion de hora
        minsActual = ((minsActual * 100) / 60);
        posH = (float) (((float) hourActual) + (minsActual * 0.01));
        // Validar hora
        for (int i = 1; i < 33; i++) {
            if (posH < (partOfHrs * i)) {
                // Rotar pico
                imgViewPicoOscuro.setRotation((partOfGrades * posH));
                imgViewPicoOscuroM.setRotation((partOfGraMs * minsActual));
                // Asignar alfa
                if (i == 16) {
                    setAlfaBuses("");
                } else if (i < 16) {
                    setAlfaBuses("izq");
                } else {
                    setAlfaBuses("der");
                }
                // Asignar imagen
                switch (i) {
                    case 32:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol01);
                        break;
                    case 31:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol02);
                        break;
                    case 30:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol03);
                        break;
                    case 29:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol04);
                        break;
                    case 28:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol05);
                        break;
                    case 27:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol06);
                        break;
                    case 26:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol07);
                        break;
                    case 25:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol08);
                        break;
                    case 24:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol09);
                        break;
                    case 23:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol10);
                        break;
                    case 22:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol11);
                        break;
                    case 21:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol12);
                        break;
                    case 20:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol13);
                        break;
                    case 19:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol14);
                        break;
                    case 18:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol15);
                        break;
                    case 17:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol16);
                        break;
                    case 16:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol17);
                        break;
                    case 15:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol18);
                        break;
                    case 14:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol19);
                        break;
                    case 13:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol20);
                        break;
                    case 12:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol21);
                        break;
                    case 11:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol22);
                        break;
                    case 10:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol23);
                        break;
                    case 9:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol24);
                        break;
                    case 8:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol25);
                        break;
                    case 7:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol26);
                        break;
                    case 6:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol27);
                        break;
                    case 5:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol28);
                        break;
                    case 4:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol29);
                        break;
                    case 3:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol30);
                        break;
                    case 2:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol31);
                        break;
                    case 1:
                        imgViewLineaConSol.setImageResource(R.drawable.ic_lineaconsol32);
                        break;
                    default:
                        break;
                }
                // Salir del ciclo
                break;
            }
        }
    }

    // Función para dar funcionalidad a elementos
    private void configurarElementos () {
        // Abrir vista con lista de fechas de apoyo
        imgViewCalendario.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(MainActivity.this, ListaAeTActivity.class);
                startActivity(intent);
            }
        });

        // Abrir vista con lista de fechas de apoyo
        imgViewEstacion.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Map<String, String> dataDic = getDataDictionaryApy();
                if ((dataDic != null) && (dataDic.size() > 0)) {
                    // Validar si hay apoyo
                    if (!dataDic.get("pos").equals("-1")) {
                        // Recupera parametros
                        String cadP = paramsParaListaPatrones(dataDic);
                        // Invoca lista de patrocinadores
                        Intent intent = new Intent(MainActivity.this, ListaSPDActivity.class);
                        intent.putExtra("listaP", cadP);
                        intent.putExtra("padre", "Main");
                        startActivity(intent);
                    } else {
                        // Emitir mensaje
                        Toast.makeText(MainActivity.this,
                                obtenerInsigniaDeEstado("mensaje",
                                        dataDic.get("hay_apoyo0"),
                                        dataDic.get("hora_ini0"),
                                        dataDic.get("hora_fin0"),
                                        dataDic.get("fecha")),
                                Toast.LENGTH_SHORT).show();
                    }
                }
            }
        });
    }

    // Función para obtener el horario formateado dada dos horas
    public static String obtenerInsigniaDeEstado (String peticion, String hayApoyo, String hrIni, String hrFin, String fecha) {
        String simbolo = "interrogacion",
                leyenda = "",
                mensaje = "",
                texto = "",
                result = "";
        // Validar
        if (hayApoyo.equals("") && hrIni.equals("") && hrFin.equals("")) {
            simbolo = "interrogacion";
            leyenda = "Apoyo no disponible";
            mensaje = "Estado del apoyo desconocido";
            texto = "Estado del apoyo desconocido";
        } else {
            // Validar si hay apoyo
            if (hayApoyo.equals("Si")) {
                // Validar fecha
                Date dateObj = null;
                Date dateHoy = new Date();
                SimpleDateFormat dateFormatS = new SimpleDateFormat("dd/MM/yyyy");
                try {
                    dateObj = dateFormatS.parse(fecha);
                }catch (Exception e) { }
                if ((dateObj != null) && dateHoy.before(dateObj)) {
                    // Texto para modo conocido
                    simbolo = "paloma";
                    leyenda = "Apoyo disponible";
                    mensaje = "Es posible solicitar apoyo para transporte durante este momento";
                    texto = "Actualmente se puede solicitar apoyo para transporte";
                } else {
                    // Asignar horario
                    String hora = "";
                    // Validar hora inicio
                    if ((hrIni != null) && !hrIni.equals("")) {
                        hora = hrIni;
                        // Validar hora fin
                        if ((hrFin != null) && !hrFin.equals("")) {
                            hora = hrFin; // Se usara la hora mas vieja para el analisis
                        }
                    } else {
                        // Validar hora fin
                        if ((hrFin != null) && !hrFin.equals("")) {
                            hora = hrFin; // Se usara la hora mas vieja para el analisis
                        }
                    }
                    // Se prepara un formato de 24 hrs
                    SimpleDateFormat hr24Format = new SimpleDateFormat("HH:mm");
                    DateFormat hourFormat = new SimpleDateFormat("HH:mm:ss");
                    try {
                        // Se parsea la hora a un objeto date
                        Date varHoraTermina = hr24Format.parse(hora);
                        // Se obtiene la fecha y hora actual
                        Date varTiempoActual = new Date();
                        // La fecha y hora actuales se convierten a un formato de 24 hrs
                        varTiempoActual = hr24Format.parse(hourFormat.format(varTiempoActual));
                        // Si la hora de finalización ha pasado a la hora actual
                        if (varHoraTermina.before(varTiempoActual)) {
                            // Texto para modo conocido
                            simbolo = "equis";
                            leyenda = "Apoyo no disponible";
                            mensaje = "Ha finalizado el periodo durante el cual se otorga apoyo para transporte";
                            texto = "Ha finalizado el periodo durante el cual se otorga apoyo para transporte";
                        } else {
                            // Si la hora de finalización no ha pasado a la hora actual
                            // Se pregunta por nuna hora de inicio
                            if ((hrIni != null) && !hrIni.equals("")) {
                                // Se formatea la hora a 24 hrs
                                hora = hrIni;
                                varHoraTermina = hr24Format.parse(hora);
                                // Se valida si la hora de inicio es mas joven que la hora actual
                                if (varHoraTermina.after(varTiempoActual)) {
                                    // Texto para modo conocido
                                    simbolo = "interrogacion";
                                    leyenda = "Disponible más tarde";
                                    mensaje = "Aún no se ha reanudado el otorgamiento de apoyo para transporte";
                                    texto = "Aún no se ha reanudado el otorgamiento de apoyo para transporte";
                                } else {
                                    // Texto para modo conocido
                                    simbolo = "paloma";
                                    leyenda = "Apoyo disponible";
                                    mensaje = "Es posible solicitar apoyo para transporte durante este momento";
                                    texto = "Actualmente se puede solicitar apoyo para transporte";
                                }
                            } else {
                                // Texto para modo conocido
                                simbolo = "paloma";
                                leyenda = "Apoyo disponible";
                                mensaje = "Es posible solicitar apoyo para transporte durante este momento";
                                texto = "Actualmente se puede solicitar apoyo para transporte";
                            }
                        }
                    } catch (Exception e) {
                        // Texto para modo inactivo
                        simbolo = "equis";
                        leyenda = "Apoyo no disponible";
                        mensaje = "El apoyo para transporte no se puede solicitar durante este momento";
                        texto = "Actualmente se encuentra suspendido el apoyo para transporte";
                    }
                }
            } else {
                // Texto para modo inactivo
                simbolo = "equis";
                leyenda = "Apoyo no disponible";
                mensaje = "El apoyo para transporte no se puede solicitar durante este momento";
                texto = "Actualmente se encuentra suspendido el apoyo para transporte";
            }
        }
        switch (peticion) {
            case "simbolo":
                result = simbolo;
                break;
            case "leyenda":
                result = leyenda;
                break;
            case "mensaje":
                result = mensaje;
                break;
            case "texto":
                result = texto;
                break;
        }
        return result;
    }

    // Función para obtener el horario formateado dada dos horas
    public static String obtenerHorario (String hrIni, String hrFin) {
        // Asignar horario
        String horario = "";
        Date varHoraAx = null;
        SimpleDateFormat hr24Format = new SimpleDateFormat("HH:mm");
        SimpleDateFormat hr12Format = new SimpleDateFormat("hh:mm a");
        // Validar hora inicio
        if ((hrIni != null) && !hrIni.equals("")) {
            try {
                // Ajustar hora a formato de 12 hrs
                varHoraAx = hr24Format.parse(hrIni);
                hrIni = hr12Format.format(varHoraAx);
            } catch (Exception e) {}
            // Asignar hora a horario
            horario += hrIni;
            // Validar hora fin
            if ((hrFin != null) && !hrFin.equals("")) {
                try {
                    // Ajustar hora a formato de 12 hrs
                    varHoraAx = hr24Format.parse(hrFin);
                    hrFin = hr12Format.format(varHoraAx);
                } catch (Exception e) {}
                // Concatenar hora a horario
                horario += (" a " + hrFin);
            }
        } else {
            // Validar hora fin
            if ((hrFin != null) && !hrFin.equals("")) {
                try {
                    // Ajustar hora a formato de 12 hrs
                    varHoraAx = hr24Format.parse(hrFin);
                    hrFin = hr12Format.format(varHoraAx);
                } catch (Exception e) {}
                // Concatenar hora a horario
                horario += hrFin;
            }
        }
        if (horario.equals("")) {
            horario = "--:--";
        }
        return horario;
    }

    // Función estatica para obtener la lista de elementos de la actividad
    public static ArrayList<Map> getDataAryList() {
        return dataAryList;
    }

    // Función para devolver el diccionario sobre el apoyo
    public static Map<String, String> getDataDictionaryApy () {
        return dataDictionaryApy;
    }

    // Función para conseguir la cadena de parametros sobre los patrones
    public static String paramsParaListaPatrones (Map<String, String> dataDic) {
        // Cadena
        String result = "",
               hayapy = "",
               patrones = "",
               dirsP = "",
               telsP = "",
               hrsP = "",
               tamData = dataDic.get("tam");
        // Seguro
        try {
            //Obtener tamaño
            int tam = Integer.parseInt(tamData);
            // Recorrer elementos
            for (int i = 0; i < tam; i++) {
                // ¿Hay apoyo?
                hayapy = dataDic.get("hay_apoyo" + i);
                // Validar por apoyo
                if (hayapy.equals("Si")) {
                    // Validar
                    if (patrones.equals("")) {
                        patrones += dataDic.get("patrones" + i);
                    } else {
                        patrones += ("┐◊┌" + dataDic.get("patrones" + i));
                    }
                    if (dirsP.equals("")) {
                        dirsP += dataDic.get("dirsPatrones" + i);
                    } else {
                        dirsP += ("┐◊┌" + dataDic.get("dirsPatrones" + i));
                    }
                    if (telsP.equals("")) {
                        telsP += dataDic.get("telsPatrones" + i);
                    } else {
                        telsP += ("┐◊┌" + dataDic.get("telsPatrones" + i));
                    }
                    // Horario
                    if (hrsP.equals("")) {
                        hrsP += (dataDic.get("fecha") + "ʭΩʭ" + dataDic.get("hora_ini" + i) + "ʭΩʭ" + dataDic.get("hora_fin" + i));
                    } else {
                        hrsP += ("┐◊┌" + (dataDic.get("fecha") + "ʭΩʭ" + dataDic.get("hora_ini" + i) + "ʭΩʭ" + dataDic.get("hora_fin" + i)));
                    }
                }
            }
            result = patrones + "┘◊└" + dirsP + "┘◊└" + telsP + "┘◊└" + hrsP;
        } catch (Exception e) {
            result = "";
        }
        // Devolver resultados
        return result;
    }

    // Función para posicionar sol de Listados
    public static void posicionarSolList (ImageView imgS, String strDate, String hrBase) {
        // Try-CATCH
        try {
            // -------------------------------
            // Se prepara un formato de 24 hrs
            SimpleDateFormat hr24Format = new SimpleDateFormat("HH:mm");
            DateFormat hourFormat = new SimpleDateFormat("HH:mm:ss");
            // Ajustar hora a formato de 12 hrs
            Date horaAX= hr24Format.parse(hrBase);
            // La fecha y hora actuales se convierten a un formato de 24 hrs
            horaAX = hr24Format.parse(hourFormat.format(horaAX));
            // -------------------------------
            // Obtener hora actual
            Calendar calActual = Calendar.getInstance();
            calActual.setTime(horaAX);
            // -------------------------------
            // Variables para hora actual
            float hourObj = (float) calActual.get(Calendar.HOUR_OF_DAY),
                  minsObj = (float) calActual.get(Calendar.MINUTE),
                  partOfHrs = (float) 24/12;
            // Obtener posicion de hora
            minsObj = ((minsObj * 100) / 60);
            float posH = (float) (hourObj + (minsObj * 0.01));
            // -------------------------------
            // Buscar hora
            for (int i = 1; i < 13; i++) {
                // Calcular icono con base en la hora
                if (posH < (partOfHrs * i)) {
                    switch (i) {
                        case 12:
                            imgS.setImageResource(R.drawable.ic_imglist01);
                            break;
                        case 11:
                            imgS.setImageResource(R.drawable.ic_imglist02);
                            break;
                        case 10:
                            imgS.setImageResource(R.drawable.ic_imglist03);
                            break;
                        case 9:
                            imgS.setImageResource(R.drawable.ic_imglist04);
                            break;
                        case 8:
                            imgS.setImageResource(R.drawable.ic_imglist05);
                            break;
                        case 7:
                            imgS.setImageResource(R.drawable.ic_imglist06);
                            break;
                        case 6:
                            imgS.setImageResource(R.drawable.ic_imglist07);
                            break;
                        case 5:
                            imgS.setImageResource(R.drawable.ic_imglist08);
                            break;
                        case 4:
                            imgS.setImageResource(R.drawable.ic_imglist09);
                            break;
                        case 3:
                            imgS.setImageResource(R.drawable.ic_imglist10);
                            break;
                        case 2:
                            imgS.setImageResource(R.drawable.ic_imglist11);
                            break;
                        case 1:
                            imgS.setImageResource(R.drawable.ic_imglist12);
                            break;
                    }
                    // Salir del ciclo
                    break;
                }
            }
        } catch (Exception e) {}
    }
}
