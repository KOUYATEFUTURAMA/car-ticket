<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Application\Compagnie;
use App\Models\Application\Depart;
use App\Models\Parametre\Localite;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartController extends Controller
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
            $vehicules = DB::table('vehicules')->select('vehicules.id','vehicules.immatriculation')->Where([['vehicules.deleted_at', NULL],['vehicules.compagnie_id',Auth::user()->compagnie_id]])->orderBy('vehicules.id', 'DESC')->get();
            $chauffeurs = DB::table('chauffeurs')->select('chauffeurs.id','chauffeurs.civilite','chauffeurs.full_name_chauffeur')->Where([['chauffeurs.deleted_at', NULL],['chauffeurs.compagnie_id',Auth::user()->compagnie_id]])->orderBy('chauffeurs.full_name_chauffeur', 'ASC')->get();
        }else{
           $menuPrincipal = "Application"; 
           $vehicules = DB::table('vehicules')->select('vehicules.id','vehicules.immatriculation')->Where('vehicules.deleted_at', NULL)->orderBy('vehicules.id', 'DESC')->get();
           $chauffeurs = DB::table('chauffeurs')->select('chauffeurs.id','chauffeurs.civilite','chauffeurs.full_name_chauffeur')->Where('chauffeurs.deleted_at', NULL)->orderBy('chauffeurs.full_name_chauffeur', 'ASC')->get();
        }
       $compagnies = DB::table('compagnies')->select('compagnies.id','compagnies.libelle_compagnie')->Where('compagnies.deleted_at', NULL)->orderBy('libelle_compagnie', 'ASC')->get();
       $localites = DB::table('localites')->select('localites.*')->Where('localites.deleted_at', NULL)->orderBy('libelle_localite', 'ASC')->get();
       $titleControlleur = "Liste des départs programés";
       (Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Superviseur') ? $btnModalAjout = "FALSE" : $btnModalAjout = "TRUE";
       return view('application.depart.index',compact('compagnies','localites','vehicules','chauffeurs','menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }
  
    public function listeDepart(){
       if(Auth::user()->compagnie_id!=null){
            $departs = Depart::with('localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $departs = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where('departs.deleted_at', NULL) 
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
       $jsonData["rows"] = $departs->toArray();
       $jsonData["total"] = $departs->count();
       return response()->json($jsonData);
    }
    
    public function listeDepartByDate($dates){
       $date = Carbon::createFromFormat('d-m-Y', $dates);
       if(Auth::user()->compagnie_id!=null){
        $departs = Depart::with('localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
        $departs = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where('departs.deleted_at', NULL)
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
       $jsonData["rows"] = $departs->toArray();
       $jsonData["total"] = $departs->count();
       return response()->json($jsonData);
    }
    
    public function listeDepartByLocalite($depart,$arrivee){
        if(Auth::user()->compagnie_id!=null){
            $departs = Depart::with('localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]]) 
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $departs = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]]) 
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
       $jsonData["rows"] = $departs->toArray();
       $jsonData["total"] = $departs->count();
       return response()->json($jsonData);
    }
    
    public function listeDepartByLocaliteDate($depart,$arrivee,$dates){
         $date = Carbon::createFromFormat('d-m-Y', $dates);
          if(Auth::user()->compagnie_id!=null){
            $departs = Depart::with('localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]]) 
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $departs = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]]) 
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
       $jsonData["rows"] = $departs->toArray();
       $jsonData["total"] = $departs->count();
       return response()->json($jsonData);
    }

    public function listeDepartByCompagnie($compagnie){
       
        $departs = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                        ->where([['departs.deleted_at', NULL],['departs.compagnie_id',$compagnie]]) 
                        ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
     
       $jsonData["rows"] = $departs->toArray();
       $jsonData["total"] = $departs->count();
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
        if ($request->isMethod('post') && $request->input('localite_depart') && $request->input('localite_arrive')) {

                $data = $request->all(); 

            try {
                if($data['localite_depart'] == $data['localite_arrive']){
                    return response()->json(["code" => 0, "msg" => "Vous avez choisi la même localité pour départ et arrivée", "data" => NULL]);
                }
                
                if((Carbon::createFromFormat('d-m-Y H:i', $data['date_depart']) == Carbon::createFromFormat('d-m-Y H:i', $data['date_arrivee'])) or (Carbon::createFromFormat('d-m-Y H:i', $data['date_depart']) > Carbon::createFromFormat('d-m-Y H:i', $data['date_arrivee']))){
                    return response()->json(["code" => 0, "msg" => "La date de depart est supérieure ou égale à la date d'arrivée", "data" => NULL]);
                }
                $depart = new Depart;
                $depart->localite_depart = $data['localite_depart'];
                $depart->localite_arrive = $data['localite_arrive'];
                $depart->tarif = $data['tarif'];
                $depart->place_disponible = $data['place_disponible'];
                $depart->vehicule_id = $data['vehicule_id'];
                $depart->chauffeur_id = $data['chauffeur_id'];
                $depart->statut = $data['statut'];
                $depart->date_depart = Carbon::createFromFormat('d-m-Y H:i', $data['date_depart']);
                $depart->date_arrivee = Carbon::createFromFormat('d-m-Y H:i', $data['date_arrivee']);
                $depart->compagnie_id = Auth::user()->compagnie_id;
                $depart->created_by = Auth::user()->id;
                $depart->save();
                $jsonData["data"] = json_decode($depart);
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
     * @param  \App\Depart  $depart
     * @return Response
     */
    public function update(Request $request, Depart $depart)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
         if($depart){
             $data = $request->all(); 
              if($data['localite_depart'] == $data['localite_arrive']){
                    return response()->json(["code" => 0, "msg" => "Vous avez choisi la même localité pour départ et arrivée", "data" => NULL]);
                }
                
                if((Carbon::createFromFormat('d-m-Y H:i', $data['date_depart']) == Carbon::createFromFormat('d-m-Y H:i', $data['date_arrivee'])) or (Carbon::createFromFormat('d-m-Y H:i', $data['date_depart']) > Carbon::createFromFormat('d-m-Y H:i', $data['date_arrivee']))){
                    return response()->json(["code" => 0, "msg" => "La date de depart est supérieure ou égale à la date d'arrivée", "data" => NULL]);
                }
            try {
                $depart->localite_depart = $data['localite_depart'];
                $depart->localite_arrive = $data['localite_arrive'];
                $depart->tarif = $data['tarif'];
                $depart->place_disponible = $data['place_disponible'];
                $depart->vehicule_id = $data['vehicule_id'];
                $depart->chauffeur_id = $data['chauffeur_id'];
                $depart->statut = $data['statut'];
                $depart->date_depart = Carbon::createFromFormat('d-m-Y H:i', $data['date_depart']);
                $depart->date_arrivee = Carbon::createFromFormat('d-m-Y H:i', $data['date_arrivee']);
                $depart->updated_by = Auth::user()->id;
                $depart->save();
                $jsonData["data"] = json_decode($depart);
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
     * @param  \App\Depart  $depart
     * @return Response
     */
    public function destroy(Depart $depart)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($depart){
                try {
             
                $depart->update(['deleted_by' => Auth::user()->id]);
                $depart->delete();
                $jsonData["data"] = json_decode($depart);
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
    
     //Liste des départs
    public function listeDepartPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeDeparts());
        return $pdf->stream('liste_departs.pdf');
    }
    public function listeDeparts(){
        if(Auth::user()->compagnie_id!=null){
            $datas = Depart::where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->select('departs.*','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $datas = Depart::where('departs.deleted_at', NULL)
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->join('compagnies','compagnies.id','departs.compagnie_id')
                        ->select('departs.*','compagnies.libelle_compagnie','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des départs</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%" align="center">Départ</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Destination</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Véhicule</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Chauffeur</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date départ</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date arrivée</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Tarif</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Place vendue</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Statut</th>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .='<th cellspacing="0" border="2" width="20%" align="center">Compagnie</th>';
                            }
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite_depart.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite_arrivee.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->immatriculation.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_departs.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_arrivees.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->tarif, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->place_vendue, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->statut.'</td>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .= '<td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>';
                            }
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Départ(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Liste des départs par date 
    public function listeDepartByDatePdf($date_depart){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeDepartByDates($date_depart));
        return $pdf->stream('liste_departs_du_'.$date_depart.'.pdf');
    }
    public function listeDepartByDates($date_depart){
        $date = Carbon::createFromFormat('d-m-Y', $date_depart);
        if(Auth::user()->compagnie_id!=null){
            $datas = Depart::where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $datas = Depart::where('departs.deleted_at', NULL)
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->join('compagnies','compagnies.id','departs.compagnie_id')
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*','compagnies.libelle_compagnie','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des départs du '.$date_depart.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%" align="center">Départ</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Destination</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Véhicule</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Chauffeur</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date arrivée</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Tarif</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Place vendue</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Statut</th>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .='<th cellspacing="0" border="2" width="20%" align="center">Compagnie</th>';
                            }
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite_depart.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_localite_arrivee.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->immatriculation.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_arrivees.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->tarif, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->place_vendue, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->statut.'</td>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .= '<td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>';
                            }
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Départ(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Liste des départs par localité
    public function listeDepartByLocalitePdf($depart, $arrivee){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeDepartByLocalites($depart, $arrivee));
        $info_localite_depart = Localite::find($depart);
        $info_localite_arrivee = Localite::find($arrivee);
        return $pdf->stream('liste_departs_de_'.$info_localite_depart->libelle_localite.'_vers_'.$info_localite_arrivee->libelle_localite.'.pdf');
    }
    public function listeDepartByLocalites($depart, $arrivee){
        $info_localite_depart = Localite::find($depart);
        $info_localite_arrivee = Localite::find($arrivee);
        if(Auth::user()->compagnie_id!=null){
            $datas = Depart::where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->select('departs.*','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $datas = Depart::where([['departs.deleted_at', NULL],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->join('compagnies','compagnies.id','departs.compagnie_id')
                        ->select('departs.*','compagnies.libelle_compagnie','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des départs du '.$info_localite_depart->libelle_localite.' vers '.$info_localite_arrivee->libelle_localite.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%" align="center">Véhicule</th>
                            <th cellspacing="0" border="2" width="35%" align="center">Chauffeur</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Date départ</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Date arrivée</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Tarif</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Place vendue</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Statut</th>';
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
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_departs.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_arrivees.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->tarif, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->place_vendue, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->statut.'</td>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .= '<td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>';
                            }
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Départ(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Liste des départs par compagnie
    public function listeDepartByCompagniePdf($compagnie){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeDepartByCompagnies($compagnie));
        $info_compagnie = Compagnie::find($compagnie);
        return $pdf->stream('liste_departs_de_lacompagnie_'.$info_compagnie->libelle_compagnie.'.pdf');
    }
    public function listeDepartByCompagnies($compagnie){
            $info_compagnie = Compagnie::find($compagnie);

            $datas = Depart::where([['departs.deleted_at', NULL],['departs.compagnie_id',$compagnie]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->select('departs.*','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
  
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des départs de la compagnie '.$info_compagnie->libelle_compagnie.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%" align="center">Véhicule</th>
                            <th cellspacing="0" border="2" width="35%" align="center">Chauffeur</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Date départ</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Date arrivée</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Tarif</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Place vendue</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Statut</th>';
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->immatriculation.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_departs.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_arrivees.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->tarif, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->place_vendue, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->statut.'</td>';
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Départ(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Liste des départs par localité date
    public function listeDepartByLocaliteDatePdf($depart, $arrivee, $date){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeDepartByLocaliteDates($depart, $arrivee, $date));
        $info_localite_depart = Localite::find($depart);
        $info_localite_arrivee = Localite::find($arrivee);
        return $pdf->stream('liste_departs_de_'.$info_localite_depart->libelle_localite.'_vers_'.$info_localite_arrivee->libelle_localite.'_au_'.$date.'.pdf');
    }
    public function listeDepartByLocaliteDates($depart, $arrivee, $dates){
         $date = Carbon::createFromFormat('d-m-Y', $dates);
        $info_localite_depart = Localite::find($depart);
        $info_localite_arrivee = Localite::find($arrivee);
        if(Auth::user()->compagnie_id!=null){
            $datas = Depart::where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }else{
            $datas = Depart::where([['departs.deleted_at', NULL],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee]])
                        ->join('localites as localiteDepart','localiteDepart.id','departs.localite_depart')
                        ->join('localites as localiteArrive','localiteArrive.id','departs.localite_arrive')
                        ->join('vehicules','vehicules.id','departs.vehicule_id')
                        ->join('chauffeurs','chauffeurs.id','departs.chauffeur_id')
                        ->join('compagnies','compagnies.id','departs.compagnie_id')
                        ->whereDate('departs.date_depart','=', $date)
                        ->select('departs.*','compagnies.libelle_compagnie','chauffeurs.civilite','chauffeurs.full_name_chauffeur','vehicules.immatriculation','localiteArrive.libelle_localite as libelle_localite_arrivee','localiteDepart.libelle_localite as libelle_localite_depart',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                        ->orderBy('departs.date_depart', 'ASC')
                        ->get();
        }
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des départs du '.$info_localite_depart->libelle_localite.' vers '.$info_localite_arrivee->libelle_localite.' le '.$date.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="20%" align="center">Véhicule</th>
                            <th cellspacing="0" border="2" width="35%" align="center">Chauffeur</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Date départ</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Date arrivée</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Tarif</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Place vendue</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Statut</th>';
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
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_departs.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_arrivees.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->tarif, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.number_format($data->place_vendue, 0, ',', ' ').'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->statut.'</td>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .= '<td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>';
                            }
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Départ(s)';
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
