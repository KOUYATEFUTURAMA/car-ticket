<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Moteur;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function response;
use function view;

class MoteurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {       
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Moteur";
       $btnModalAjout = "FALSE";
       return view('parametre.moteur.index',compact('menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }
    
    public function listeMoteur()
    {
       $moteurs = DB::table('moteurs')->select('moteurs.*')->Where('deleted_at', NULL)->orderBy('libelle_moteur', 'ASC')->get();
       $jsonData["rows"] = $moteurs->toArray();
       $jsonData["total"] = $moteurs->count();
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
        if ($request->isMethod('post') && $request->input('libelle_moteur')) {

                $data = $request->all(); 

            try {

                $Moteur = Moteur::where('libelle_moteur', $data['libelle_moteur'])->first();
                if($Moteur){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $moteur = new Moteur;
                $moteur->libelle_moteur = $data['libelle_moteur'];
                $moteur->created_by = Auth::user()->id;
                $moteur->save();
                $jsonData["data"] = json_decode($moteur);
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
        
        $moteur = Moteur::find($id);

        if($moteur){
            try {
                $moteur->update([
                    'libelle_moteur' => $request->get('libelle_moteur'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($moteur);
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
        $moteur = Moteur::find($id);
        
        if($moteur){
            try {
                $moteur->update(['deleted_by' => Auth::user()->id]);
                $moteur->delete();
                $jsonData["data"] = json_decode($moteur);
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
