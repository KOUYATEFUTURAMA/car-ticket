<?php

namespace App\Http\Controllers;

use App\Models\Application\Compagnie;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Auth;

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
        }else{
           $menuPrincipal = "Accueil"; 
        }
        $titleControlleur = "Tableau de bord";
        $btnModalAjout = "FALSE";
        return view('home', compact('menuPrincipal', 'titleControlleur', 'btnModalAjout'));
    }
}
