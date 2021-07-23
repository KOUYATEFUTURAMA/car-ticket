<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Application\Vehicule;
use App\Models\Application\Compagnie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if(Auth::user()->compagnie_id!=null){
            $compagnie = Compagnie::find(Auth::user()->compagnie_id);
            $menuPrincipal = $compagnie->libelle_compagnie;
        }else{
           $menuPrincipal = "Application"; 
        }
       $compagnies = DB::table('compagnies')->select('compagnies.id','compagnies.libelle_compagnie')->Where('compagnies.deleted_at', NULL)->orderBy('libelle_compagnie', 'ASC')->get();
       $marques = DB::table('marques')->select('marques.*')->Where('marques.deleted_at', NULL)->orderBy('libelle_marque', 'ASC')->get();
       $moteurs = DB::table('moteurs')->select('moteurs.*')->Where('moteurs.deleted_at', NULL)->orderBy('libelle_moteur', 'ASC')->get();
       $puissances = DB::table('puissances')->select('puissances.*')->Where('puissances.deleted_at', NULL)->orderBy('libelle_puissance', 'ASC')->get();
       $type_vehicules = DB::table('type_vehicules')->select('type_vehicules.*')->Where('type_vehicules.deleted_at', NULL)->orderBy('libelle_type_vehicule', 'ASC')->get();
       $titleControlleur = "Liste des véhicules";
       (Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Superviseur') ? $btnModalAjout = "FALSE" : $btnModalAjout = "TRUE";
       return view('application.vehicule.index',compact('compagnies','marques','moteurs','puissances','type_vehicules', 'menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }

    public function listeVehicule()
    {
        if(Auth::user()->compagnie_id!=null){
        $vehicules = Vehicule::with('marque','moteur','puissance','type_vehicule')
                        ->where([['vehicules.deleted_at', NULL],['vehicules.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->select('vehicules.*',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
        }else{
        $vehicules = Vehicule::with('compagnie','marque','moteur','puissance','type_vehicule')
                        ->where('vehicules.deleted_at', NULL) 
                        ->select('vehicules.*',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
        }
       $jsonData["rows"] = $vehicules->toArray();
       $jsonData["total"] = $vehicules->count();
       return response()->json($jsonData);
    }
    
    public function listeVehiculeByCompagnie($compagnie){
        $vehicules = Vehicule::with('compagnie','marque','moteur','puissance','type_vehicule')
                        ->where([['vehicules.deleted_at', NULL],['vehicules.compagnie_id',$compagnie]]) 
                        ->select('vehicules.*',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
        $jsonData["rows"] = $vehicules->toArray();
        $jsonData["total"] = $vehicules->count();
       return response()->json($jsonData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
       $jsonData = ["code" => 1, "msg" => "Enregistrement effectué avec succès."];
        if ($request->isMethod('post') && $request->input('immatriculation')) {

                $data = $request->all(); 

            try {
                
                $vehicule = new Vehicule;
                $vehicule->immatriculation = $data['immatriculation'];
                $vehicule->descritpion_vehicule = $data['descritpion_vehicule'];
                $vehicule->type_vitesse = $data['type_vitesse'];
                $vehicule->nombre_place = $data['nombre_place'];
                $vehicule->marque_id = $data['marque_id'];
                $vehicule->moteur_id = $data['moteur_id'];
                $vehicule->puissance_id = $data['puissance_id'];
                $vehicule->type_vehicule_id = $data['type_vehicule_id'];
                $vehicule->date_next_visite = Carbon::createFromFormat('d-m-Y', $data['date_next_visite']);
                $vehicule->compagnie_id = Auth::user()->compagnie_id;
                $vehicule->created_by = Auth::user()->id;
                $vehicule->save();
                $jsonData["data"] = json_decode($vehicule);
                return response()->json($jsonData);

            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  \App\Vehicule  $vehicule
     * @return Response
     */
    public function update(Request $request, Vehicule $vehicule)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
         if($vehicule){
             $data = $request->all(); 
            try {
                $vehicule->immatriculation = $data['immatriculation'];
                $vehicule->descritpion_vehicule = $data['descritpion_vehicule'];
                $vehicule->type_vitesse = $data['type_vitesse'];
                $vehicule->nombre_place = $data['nombre_place'];
                $vehicule->marque_id = $data['marque_id'];
                $vehicule->moteur_id = $data['moteur_id'];
                $vehicule->puissance_id = $data['puissance_id'];
                $vehicule->type_vehicule_id = $data['type_vehicule_id'];
                $vehicule->date_next_visite = Carbon::createFromFormat('d-m-Y', $data['date_next_visite']);
                $vehicule->updated_by = Auth::user()->id;
                $vehicule->save();
                $jsonData["data"] = json_decode($vehicule);
            return response()->json($jsonData);
            } catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }

        }
        return response()->json(["code" => 0, "msg" => "Echec de modification", "data" => NULL]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Vehicule  $vehicule
     * @return Response
     */
    public function destroy(Vehicule $vehicule)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($vehicule){
                try {
             
                $vehicule->update(['deleted_by' => Auth::user()->id]);
                $vehicule->delete();
                $jsonData["data"] = json_decode($vehicule);
                return response()->json($jsonData);
                } catch (Exception $exc) {
                   $jsonData["code"] = -1;
                   $jsonData["data"] = NULL;
                   $jsonData["msg"] = $exc->getMessage();
                   return response()->json($jsonData); 
                }
            }
        return response()->json(["code" => 0, "msg" => "Echec de suppression", "data" => NULL]);
    }
    
    //Liste des vehicules
    public function listeVehiculePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeVehicules());
        return $pdf->stream('liste_vehicules.pdf');
    }
    public function listeVehicules(){
        if(Auth::user()->compagnie_id!=null){
        $datas = Vehicule::where([['vehicules.deleted_at', NULL],['vehicules.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->join('marques','marques.id','=','vehicules.marque_id')
                        ->join('moteurs','moteurs.id','=','vehicules.moteur_id')
                        ->join('puissances','puissances.id','=','vehicules.puissance_id')
                        ->join('type_vehicules','type_vehicules.id','=','vehicules.type_vehicule_id')
                        ->select('vehicules.*','marques.libelle_marque','moteurs.libelle_moteur','puissances.libelle_puissance','type_vehicules.libelle_type_vehicule',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
        }else{
        $datas = Vehicule::where('vehicules.deleted_at', NULL) 
                        ->join('compagnies','compagnies.id','=','vehicules.compagnie_id')
                        ->join('marques','marques.id','=','vehicules.marque_id')
                        ->join('moteurs','moteurs.id','=','vehicules.moteur_id')
                        ->join('puissances','puissances.id','=','vehicules.puissance_id')
                        ->join('type_vehicules','type_vehicules.id','=','vehicules.type_vehicule_id')
                        ->select('vehicules.*','compagnies.libelle_compagnie','marques.libelle_marque','moteurs.libelle_moteur','puissances.libelle_puissance','type_vehicules.libelle_type_vehicule',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
        }
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des véhicules</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="15%" align="center">Imatriculation</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Description</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Place</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Marque</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Moteur</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Puissance</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Type</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Boîte</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date visite proch.</th>';
                        if(Auth::user()->compagnie_id==null){
                            $outPut .='<th cellspacing="0" border="2" width="20%" align="center">Compagnie</th>';
                        }
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->immatriculation.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->descritpion_vehicule.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nombre_place.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_marque.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_moteur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_puissance.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_vehicule.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->type_vitesse.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_next_visites.'</td>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .= '<td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>';
                            }
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Véhicule(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Liste des vehicules par compagnie
    public function listeVehiculeByCompagniePdf($compagnie){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeVehiculeByCompagnies($compagnie));
        $info_compagnie = Compagnie::find($compagnie);
        return $pdf->stream('liste_vehicules_de_compagnie_'.$info_compagnie->libelle_compagnie.'.pdf');
    }
    public function listeVehiculeByCompagnies($compagnie){
        $info_compagnie = Compagnie::find($compagnie);
        $datas = Vehicule::where([['vehicules.deleted_at', NULL],['vehicules.compagnie_id',$compagnie]]) 
                            ->join('marques','marques.id','=','vehicules.marque_id')
                            ->join('moteurs','moteurs.id','=','vehicules.moteur_id')
                            ->join('puissances','puissances.id','=','vehicules.puissance_id')
                            ->join('type_vehicules','type_vehicules.id','=','vehicules.type_vehicule_id')
                            ->select('vehicules.*','marques.libelle_marque','moteurs.libelle_moteur','puissances.libelle_puissance','type_vehicules.libelle_type_vehicule',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                            ->orderBy('vehicules.id', 'DESC')
                        ->get();
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des vehicules de la compagnie '.$info_compagnie->libelle_compagnie.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="15%" align="center">Imatriculation</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Description</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Place</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Marque</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Moteur</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Puissance</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Type</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Boîte</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date visite proch.</th>';
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->immatriculation.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->descritpion_vehicule.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->nombre_place.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_marque.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_moteur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_puissance.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_type_vehicule.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->type_vitesse.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_next_visites.'</td>
                   </tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Véhicule(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Header and footer des pdf
    public function header(){
        if(Auth::user()->compagnie_id!=null){
            $compagnie_info = Compagnie::find(Auth::user()->compagnie_id);
            $logo = $compagnie_info->logo;
        }else{
            $logo = "images/logo.png";
        }
        $header = '<html>
                    <head>
                        <style>
                            @page{
                                margin: 100px 25px;
                                }
                            header{
                                    position: absolute;
                                    top: -60px;
                                    left: 0px;
                                    right: 0px;
                                    height:20px;
                                }
                            .container-table{        
                                            margin:80px 0;
                                            width: 100%;
                                        }
                            .fixed-footer{.
                                width : 100%;
                                position: fixed; 
                                bottom: -28; 
                                left: 0px; 
                                right: 0px;
                                height: 50px; 
                                text-align:center;
                            }
                            .fixed-footer-right{
                                position: absolute; 
                                bottom: -150; 
                                height: 0; 
                                font-size:13px;
                                float : right;
                            }
                            .page-number:before {
                                            
                            }
                        </style>
                    </head>
    /
    <script type="text/php">
        if (isset($pdf)){
            $text = "Page {PAGE_NUM} / {PAGE_COUNT}";
            $size = 10;
            $font = $fontMetrics->getFont("Verdana");
            $width = $fontMetrics->get_text_width($text, $font, $size) / 2;
            $x = ($pdf->get_width() - $width) / 2;
            $y = $pdf->get_height() - 35;
            $pdf->page_text($x, $y, $text, $font, $size);
        }
    </script>
        <body>
        <header>
        <p style="margin:0; position:left;">
            <img src='.$logo.' width="200" height="160"/>
        </p>
        </header>';     
        return $header;
    }
    public function footer(){
        $footer ="<div class='fixed-footer'>
                        <div class='page-number'></div>
                    </div>
                    <div class='fixed-footer-right'>
                     <i> Editer le ".date('d-m-Y')."</i>
                    </div>
            </body>
        </html>";
        return $footer;
    }
}
