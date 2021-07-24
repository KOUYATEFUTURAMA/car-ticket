<?php

namespace App\Http\Controllers;

use App\Models\Application\Depart;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        $localites = DB::table('localites')->select('localites.*')->Where('deleted_at', NULL)->orderBy('libelle_localite', 'ASC')->get();

        return view('welcome', compact('localites'));
    }
    
    public function sercheDepart(Request $request){
        
        $localites = DB::table('localites')->select('localites.*')->Where('deleted_at', NULL)->orderBy('libelle_localite', 'ASC')->get();

        $data = $request->all(); 
        $dates = $data['date'];
        $depart = $data['depart'];
        $arrivee = $data['destination'];
        $date = Carbon::createFromFormat('d-m-Y', $dates);
        
        $departs = Depart::where([['departs.deleted_at', NULL],['departs.localite_depart',$depart],['departs.localite_arrive',$arrivee],['departs.statut',1]]) 
                            ->join('compagnies','compagnies.id','=','departs.compagnie_id') 
                            ->join('localites as localiteDepart','localiteDepart.id','=','departs.localite_depart') 
                            ->join('localites as localiteArrive','localiteArrive.id','=','departs.localite_arrive') 
                            ->join('vehicules','vehicules.id','=','departs.vehicule_id') 
                            ->whereDate('departs.date_depart','=',$date)
                            ->select('departs.*','localiteDepart.libelle_localite as depart','localiteArrive.libelle_localite as arrive','compagnies.logo','compagnies.libelle_compagnie','vehicules.immatriculation',DB::raw('DATE_FORMAT(departs.date_arrivee, "%d-%m-%Y à %H:%i") as date_arrivees'),DB::raw('DATE_FORMAT(departs.date_depart, "%d-%m-%Y à %H:%i") as date_departs'))
                            ->orderBy('departs.date_depart', 'DESC')
                            ->get();
               
        return view('site.search-depart', compact('departs','localites','dates','depart','arrivee'));
    }
    
}
