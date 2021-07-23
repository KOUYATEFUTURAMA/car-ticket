<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Marque;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function response;
use function view;

class MarqueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {       
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Marque de véhicule";
       $btnModalAjout = "FALSE";
       return view('parametre.marque.index',compact('menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }
    
    public function listeMarque()
    {
       $marques = DB::table('marques')->select('marques.*')->Where('deleted_at', NULL)->orderBy('libelle_marque', 'ASC')->get();
       $jsonData["rows"] = $marques->toArray();
       $jsonData["total"] = $marques->count();
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
        if ($request->isMethod('post') && $request->input('libelle_marque')) {

                $data = $request->all(); 

            try {

                $Marque = Marque::where('libelle_marque', $data['libelle_marque'])->first();
                if($Marque){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $marque = new Marque;
                $marque->libelle_marque = $data['libelle_marque'];
                $marque->created_by = Auth::user()->id;
                $marque->save();
                $jsonData["data"] = json_decode($marque);
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
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        $marque = Marque::find($id);

        if($marque){
            try {
                $marque->update([
                    'libelle_marque' => $request->get('libelle_marque'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($marque);
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
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
       $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
        $marque = Marque::find($id);
        
        if($marque){
            try {
                $marque->update(['deleted_by' => Auth::user()->id]);
                $marque->delete();
                $jsonData["data"] = json_decode($marque);
                return response()->json($jsonData);
            }catch (Exception $exc) {
               $jsonData["code"] = -1;
               $jsonData["data"] = NULL;
               $jsonData["msg"] = $exc->getMessage();
               return response()->json($jsonData); 
            }
        }
        return response()->json(["code" => 0, "msg" => "Echec de suppression", "data" => NULL]); 
    }
}
