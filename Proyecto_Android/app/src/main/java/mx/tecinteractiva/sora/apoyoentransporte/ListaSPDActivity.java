package mx.tecinteractiva.sora.apoyoentransporte;

import android.content.Context;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

public class ListaSPDActivity extends AppCompatActivity {
    // Variables
    public ItemSPD gItemsSPD[] = new ItemSPD[]{};
    private String strP = "";
    // Al seleccionar un item
    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        int id = item.getItemId();
        if (id == android.R.id.home) {
            onBackPressed();
            return  true;
        }
        return super.onOptionsItemSelected(item);

    }
    // Constructor
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // -------------------------------
        // -------------------------------
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lista_spd);
        // -------------------------------
        // -------------------------------
        // Recuperar patrametros
        Bundle b = getIntent().getExtras();
        strP = b.getString("listaP");

        // Establecer boton de regreso en barra superior
        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        // -------------------------------
        // -------------------------------
        // Obtener arreglo de elementos
        String[] aryX = strP.split("┘◊└");
        String[] aryP = null;
        String[] aryD = null;
        String[] aryT = null;
        String[] aryH = null;

        // Obtener tamaño
        int numAryX = aryX.length;
        for (int i = 0; i < numAryX; i++) {
            switch (i){
                case 0:
                    aryP = aryX[i].split("┐◊┌");
                    break;
                case 1:
                    aryD = aryX[i].split("┐◊┌");
                    break;
                case 2:
                    aryT = aryX[i].split("┐◊┌");
                    break;
                case 3:
                    aryH = aryX[i].split("┐◊┌");
                    break;
            }
        }
        // Obtener numero de patrones
        numAryX = aryP.length;
        // Configurar tamaño de arreglo
        gItemsSPD = new ItemSPD[numAryX];
        // Recorrer patrones
        for (int i = 0; i < numAryX; i++) {
            if (i < aryD.length) {
                if (i < aryT.length) {
                    if (i < aryH.length) {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], aryD[i], aryT[i], aryH[i]);
                    } else {
                        gItemsSPD[i] = new ItemSPD(aryP[i], aryD[i], aryT[i], "");
                    }
                } else {
                    if (i < aryH.length) {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], aryD[i], "", aryH[i]);
                    } else {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], aryD[i], "", "");
                    }

                }
            } else {
                if (i < aryT.length) {
                    if (i < aryH.length) {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], "", aryT[i], aryH[i]);
                    } else {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], "", aryT[i], "");
                    }

                } else {
                    if (i < aryH.length) {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], "", "", aryH[i]);
                    } else {
                        // Crear nuevo elemento
                        gItemsSPD[i] = new ItemSPD(aryP[i], "", "", "");
                    }
                }
            }
        }
        // -------------------------------
        // -------------------------------
        // Agregar items a un adaptador
        AdapterItemSPD adapterSPD = new AdapterItemSPD(this, gItemsSPD);
        ListView listV = (ListView) findViewById(R.id.listViewSPD);
        // Relacionar lista y adaptador
        listV.setAdapter(adapterSPD);
        // Configurar evento de adaptador
        listV.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                // Obtener item
                ItemSPD miItem = gItemsSPD[position];
                Toast.makeText(ListaSPDActivity.this, miItem.getDireccion(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    // Clase para ITEM
    public class ItemSPD {
        // Variables
        private String patron = "",
                       direccion = "",
                       telefonos = "",
                       fecha = "",
                       horaIni = "",
                       horaFin = "",
                       horario = "";
        // Constructor
        public ItemSPD (String p, String d, String t, String h) {
            // Variable telefono
            String[] tels = t.split("\\|");
            int numTels = tels.length;
            t = "";
            // Recorrer telefonos // |
            for (int i = 0; i < numTels; i++) {
                if (t.equals("")) {
                    t += tels[i];
                } else {
                    t += (", " + tels[i]);
                }
            }
            // Variable horarios
            String[] hrs = h.split("ʭΩʭ");
            int numHrs = hrs.length;
            // Recorrer horarios // "ʭΩʭ"
            for (int i = 0; i < numHrs; i++) {
                switch (i) {
                    case 0:
                        this.fecha = hrs[i];
                        break;
                    case 1:
                        this.horaIni = hrs[i];
                        break;
                    case 2:
                        this.horaFin = hrs[i];
                        break;
                }
            }
            if (!this.horaIni.equals("") && !this.horaFin.equals("")) {
                h = MainActivity.obtenerHorario(this.horaIni, this.horaFin);
            } else {
                h = "";
            }
            // Asignar valores
            this.patron = p;
            this.direccion = d;
            this.telefonos = t;
            this.horario = h;
        }

        // Funciones para la obtencion de variables
        public String getPatron() {
            return patron;
        }

        public String getTelefonos() {
            return telefonos;
        }

        public String getDireccion() {
            return direccion;
        }

        public String getHorario() {
            return horario;
        }

        public String getFecha() {
            return fecha;
        }

        // Configurar imagen de Hora
        public void configurarImgH(ImageView imgC, ImageView imgS) {
            // Recupera patron
            String p = patron;
            // Valida patron
            switch (p.toUpperCase()) {
                case "AHTECA":
                    imgC.setImageResource(R.drawable.ic_ahteca);
                    break;
                case "DIF XALAPA":
                    imgC.setImageResource(R.drawable.ic_difxalapa);
                    break;
                case "":
                    imgC.setImageResource(R.drawable.ic_sol);
                    break;
                default:
                    imgC.setImageResource(R.drawable.ic_primerbus);
                    break;
            }
            // Posicionar sol
            MainActivity.posicionarSolList(imgS, this.fecha, this.horaIni);
        }
    }

    // Clase del adaptador
    public class AdapterItemSPD extends ArrayAdapter<ListaSPDActivity.ItemSPD> {
        // Arreglo de items y contexto
        private ListaSPDActivity.ItemSPD itemsSPD[];
        private Context contexto;
        // Constructor
        public AdapterItemSPD (Context pContexto, ListaSPDActivity.ItemSPD pItemsSPD[]) {
            super(pContexto, R.layout.item_spd_to_listview, pItemsSPD);
            this.itemsSPD = pItemsSPD;
            this.contexto = pContexto;
        }
        // Funcion para construir item de lista
        public View getView(int pos, View view, ViewGroup parentView) {
            // Validar vista
            if (view == null) {
                LayoutInflater inflater = ((AppCompatActivity) this.contexto).getLayoutInflater();
                view = inflater.inflate(R.layout.item_spd_to_listview, parentView, false);
            }
            // Obtener item
            ListaSPDActivity.ItemSPD miItem = this.itemsSPD[pos];
            // Obtener elemento de la vista
            ImageView imgPatron = (ImageView) view.findViewById(R.id.imgViewIconPatron);
            ImageView imgLinSol = (ImageView) view.findViewById(R.id.imgViewStatusSolList);
            TextView textInfo = (TextView) view.findViewById(R.id.textViewInfo);
            // Establecer mensaje
            textInfo.setText(miItem.getPatron() + "\n" + miItem.getFecha() + "\n" + miItem.getHorario() + "\n" + miItem.getTelefonos() + "\n" + miItem.getDireccion());
            // Ajustar imagen
            miItem.configurarImgH(imgPatron, imgLinSol);
            // Retornar vista
            return view;
        }
    }
}

