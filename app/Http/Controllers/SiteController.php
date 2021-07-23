<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        $localites = DB::table('localites')->select('localites.*')->Where('deleted_at', NULL)->orderBy('libelle_localite', 'ASC')->get();

        return view('welcome', compact('localites'));
    }
    
}
