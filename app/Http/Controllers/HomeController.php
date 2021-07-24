<?php

namespace App\Http\Controllers;

use App\Models\Application\Chauffeur;
use App\Models\Application\Compagnie;
use App\Models\Application\Depart;
use App\Models\Application\Vehicule;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->compagnie_id!=null){
            $compagnie = Compagnie::find(Auth::user()->compagnie_id);
            $menuPrincipal = $compagnie->libelle_compagnie;
            $compagnieByDeparts = [];
            $compagnieByVehicules = [];
            $compagnieByChauffeurs = [];
            $departs = Depart::where([['departs.deleted_at', NULL],['departs.statut',1],['departs.compagnie_id',Auth::user()->compagnie_id]]) 
                                ->join('localites as localiteDepart','localiteDepart.id','=','departs.localite_depart')
                                ->join('localites as localiteArrivee','localiteArrivee.id','=','departs.localite_arrive')
                                ->join('chauffeurs','chauffeurs.id','=','departs.chauffeur_id')
                                ->join('vehicules','vehicules.id','=','departs.vehicule_id')
                                ->select('departs.*','localiteDepart.libelle_localite as localite_depart','localiteArrivee.libelle_localite as localite_arrive','chauffeurs.full_name_chauffeur','vehicules.immatriculation',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                                ->orderBy('departs.date_depart', 'DESC')
                                ->take(5)->get();
            
            $departJour = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                                ->where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id]]) 
                                ->whereDate('departs.date_depart','=',date("Y-m-d")) 
                                ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                                ->orderBy('departs.date_depart', 'ASC')
                                ->get();
            
            $departTotal = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                                ->where([['departs.deleted_at', NULL],['departs.compagnie_id',Auth::user()->compagnie_id]]) 
                                ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                                ->orderBy('departs.date_depart', 'ASC')
                                ->get();
            
            $chauffeurs = Chauffeur::with('compagnie')
                                ->where([['chauffeurs.deleted_at', NULL],['chauffeurs.compagnie_id',Auth::user()->compagnie_id]]) 
                                ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                                ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                                ->get();
            
            $vehicules = Vehicule::with('marque','moteur','puissance','type_vehicule')
                        ->where([['vehicules.deleted_at', NULL],['vehicules.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->select('vehicules.*',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
        }else{
            
            $departs = Depart::where([['departs.deleted_at', NULL],['departs.statut',1]]) 
                                ->join('localites as localiteDepart','localiteDepart.id','=','departs.localite_depart')
                                ->join('localites as localiteArrivee','localiteArrivee.id','=','departs.localite_arrive')
                                ->join('compagnies','compagnies.id','=','departs.compagnie_id')
                                ->select('departs.*','compagnies.libelle_compagnie','localiteDepart.libelle_localite as localite_depart','localiteArrivee.libelle_localite as localite_arrive',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                                ->orderBy('departs.date_depart', 'DESC')
                                ->take(5)->get();
            
            $departTotal = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                                ->where('departs.deleted_at', NULL) 
                                ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                                ->orderBy('departs.date_depart', 'ASC')
                                ->get();
            
            $departJour = Depart::with('compagnie','localite_depart','localite_arrive','vehicule','chauffeur')
                                ->where('departs.deleted_at', NULL) 
                                ->whereDate('departs.date_depart','=',date("Y-m-d")) 
                                ->select('departs.*',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y %H:%i") as date_departs'))
                                ->orderBy('departs.date_depart', 'ASC')
                                ->get();
            
            $chauffeurs = Chauffeur::with('compagnie')
                                ->where('chauffeurs.deleted_at', NULL) 
                                ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                                ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                                ->get();
            
            $vehicules = Vehicule::with('marque','moteur','puissance','type_vehicule')
                        ->where('vehicules.deleted_at', NULL) 
                        ->select('vehicules.*',DB::raw('DATE_FORMAT(vehicules.date_next_visite, "%d-%m-%Y") as date_next_visites'))
                        ->orderBy('vehicules.id', 'DESC')
                        ->get();
            
            $compagnieByDeparts = Depart::where('departs.deleted_at', NULL) 
                                          ->whereDate('departs.date_depart','=',date("Y-m-d")) 
                                          ->join('compagnies','compagnies.id','=','departs.compagnie_id')
                                          ->select('compagnies.libelle_compagnie',DB::raw('COUNT(*) as total'))
                                          ->groupBy('compagnies.libelle_compagnie')
                                          ->orderBy('total', 'DESC')
                                          ->take(5)->get();
            
            $compagnieByVehicules = Vehicule::where('vehicules.deleted_at', NULL) 
                                                ->join('compagnies','compagnies.id','=','vehicules.compagnie_id')
                                                ->select('compagnies.libelle_compagnie',DB::raw('COUNT(*) as total'))
                                                ->groupBy('compagnies.libelle_compagnie')
                                                ->orderBy('total', 'DESC')
                                                ->take(5)->get();
            
            $compagnieByChauffeurs = Chauffeur::where('chauffeurs.deleted_at', NULL) 
                                                ->join('compagnies','compagnies.id','=','chauffeurs.compagnie_id')
                                                ->select('compagnies.libelle_compagnie',DB::raw('COUNT(*) as total'))
                                                ->groupBy('compagnies.libelle_compagnie')
                                                ->orderBy('total', 'DESC')
                                                ->take(5)->get();
             
            $menuPrincipal = "Accueil"; 
        }
        
        
        $compagnies = Compagnie::where('compagnies.deleted_at', NULL) 
                                ->select('compagnies.*')
                                ->orderBy('compagnies.libelle_compagnie', 'ASC')
                                ->get();        
        
        $localites = DB::table('localites')->select('localites.*')->Where('deleted_at', NULL)->orderBy('libelle_localite', 'ASC')->get();
        
        $titleControlleur = "Tableau de bord";
        $btnModalAjout = "FALSE";
        return view('home', compact('departs','departJour','compagnieByDeparts','compagnieByVehicules','compagnieByChauffeurs', 'compagnies','chauffeurs','vehicules','localites','departTotal', 'menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
}
