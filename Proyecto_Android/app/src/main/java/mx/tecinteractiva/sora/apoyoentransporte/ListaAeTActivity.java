package mx.tecinteractiva.sora.apoyoentransporte;

import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.Map;

public class ListaAeTActivity extends AppCompatActivity {
    // Variables
    public ItemAeT gItemsAeT[] = new ItemAeT[]{};
    private ArrayList<Map> dataAryList = MainActivity.getDataAryList();
    // Constructor
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lista_ae_t);
        // Establecer boton de regreso en barra superior
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);
        // Configurar tamaño de arreglo
        gItemsAeT = new ItemAeT[dataAryList.size()];
        // Recorrer arreglo
        for (int i = 0; i < gItemsAeT.length; i++) {
            // Construir un nuevo item y grabarlo en arreglo
            Map<String, String> dataDictionary =  MainActivity.transformarDataDic(dataAryList.get(i));
            // Crear nuevo elemento
            gItemsAeT[i] = new ItemAeT(dataDictionary);
        }
        // Agregar items a un adaptador
        AdapterItemAeT adapterAeT = new AdapterItemAeT(this, gItemsAeT);
        ListView listV = (ListView) findViewById(R.id.listViewAeT);
        // Relacionar lista y adaptador
        listV.setAdapter(adapterAeT);
        // Configurar evento de adaptador
        listV.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                // Obtener item
                ItemAeT miItem = gItemsAeT[position];
                // Validar si hay apoyo
                if (miItem.getHayApoyo().equals("Si")) {
                    // Recupera parametros
                    String cadP = miItem.paramsParaListaPatrones();
                    // Invoca lista de patrocinadores
                    Intent intent = new Intent(ListaAeTActivity.this, ListaSPDActivity.class);
                    intent.putExtra("listaP", cadP);
                    intent.putExtra("padre", "AeT");
                    startActivity(intent);
                } else {
                    // Emitir mensaje
                    Toast.makeText(ListaAeTActivity.this, miItem.getMensaje(), Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    // Clase para ITEM
    public class ItemAeT {
        // Variables
        private String fecha = "",
                       horaIni = "",
                       horaFin = "",
                       hayApoyo = "";
        private Map<String, String> dataDictionary = null;
        // Constructor
        public ItemAeT (Map<String, String> dataDic) {
            // Recupera posición
            String pos = dataDic.get("pos");
            // Si no hay apoyo alguno para este dia
            if (pos.equals("-1")) {
                pos = "0";
            }
            // Variables
            String pFecha = dataDic.get("fecha"),
                   pHoraIni = dataDic.get("hora_ini" + pos),
                   pHoraFin = dataDic.get("hora_fin" + pos),
                   pHayApy = dataDic.get("hay_apoyo" + pos);
            // Asignar variables
            this.fecha = pFecha;
            this.horaIni = pHoraIni;
            this.horaFin = pHoraFin;
            this.hayApoyo = pHayApy;
            this.dataDictionary = dataDic;
        }
        // Obtener fecha
        public String getFecha() {
            return this.fecha;
        }

        // Obtener Si hay apoyo
        public String getHayApoyo() {
            return this.hayApoyo;
        }

        // Obtener horario
        public String getHorario() {
            if (this.hayApoyo.equals("Si")) {
                return MainActivity.obtenerHorario(this.horaIni, this.horaFin);
            } else {
                return "--:--";
            }
        }

        /* Funciones para obtener insignia */
        public String getLeyenda() {
            return MainActivity.obtenerInsigniaDeEstado("leyenda", this.hayApoyo, this.horaIni, this.horaFin, this.fecha);
        }

        public String getMensaje() {
            return MainActivity.obtenerInsigniaDeEstado("mensaje", this.hayApoyo, this.horaIni, this.horaFin, this.fecha);
        }

        public String getNomSimboloEstado() {
            return MainActivity.obtenerInsigniaDeEstado("simbolo", this.hayApoyo, this.horaIni, this.horaFin, this.fecha);
        }

        // Configurar imagen de Hora
        public void configurarImgH(ImageView imgH) {
            // Posicionar sol
            MainActivity.posicionarSolList(imgH, this.fecha, this.horaIni);
        }

        // Devolver parametros para lista de patrocinadores
        public String paramsParaListaPatrones () {
            return MainActivity.paramsParaListaPatrones(this.dataDictionary);
        }
    }

    // Clase del adaptador
    public class AdapterItemAeT extends ArrayAdapter<ItemAeT>{
        // Arreglo de items y contexto
        private ItemAeT itemsAeT[];
        private Context contexto;
        // Constructor
        public AdapterItemAeT (Context pContexto, ItemAeT pItemsAeT[]) {
            super(pContexto, R.layout.item_aet_to_listview, pItemsAeT);
            this.itemsAeT = pItemsAeT;
            this.contexto = pContexto;
        }
        // Funcion para construir item de lista
        public View getView(int pos, View view, ViewGroup parentView) {
            // Validar vista
            if (view == null) {
                LayoutInflater inflater = ((AppCompatActivity) this.contexto).getLayoutInflater();
                view = inflater.inflate(R.layout.item_aet_to_listview, parentView, false);
                //view = getLayoutInflater().inflate(R.layout.item_aet_to_listview, parentView, false);
            }
            // Obtener item
            ItemAeT miItem = itemsAeT[pos];
            // Obtener elemento para estado
            ImageView imgEstado = (ImageView) view.findViewById(R.id.imgViewStatusIconList);
            // Obtener elemento para texto
            TextView textVFecha = (TextView) view.findViewById(R.id.textViewFechaListView);
            // Obtener elemento de sol
            ImageView imgList = (ImageView) view.findViewById(R.id.imgViewStatusLineList);
            // Establecer mensaje
            textVFecha.setText(miItem.getLeyenda()+ "\n" + miItem.getFecha() + "\n" + miItem.getHorario());
            // Establecer simbolo
            switch (miItem.getNomSimboloEstado()) {
                case "paloma":
                    imgEstado.setImageResource(R.drawable.ic_paloma);
                    break;
                case "equis":
                    imgEstado.setImageResource(R.drawable.ic_equis);
                    break;
                default:
                    imgEstado.setImageResource(R.drawable.ic_interrogacion);
                    break;
            }
            miItem.configurarImgH(imgList);
            return view;
        }
    }
}

