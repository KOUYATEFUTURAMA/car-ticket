<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\TypeVehicule;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TypeVehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Type de véhicule";
       $btnModalAjout = "FALSE";
       return view('parametre.type-vehicule.index',compact('menuPrincipal', 'titleControlleur','btnModalAjout')); 
  
    }

    public function listeTypeVehicule()
    {
       $type_vehicules = DB::table('type_vehicules')->select('type_vehicules.*')->Where('deleted_at', NULL)->orderBy('libelle_type_vehicule', 'ASC')->get();
       $jsonData["rows"] = $type_vehicules->toArray();
       $jsonData["total"] = $type_vehicules->count();
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
        if ($request->isMethod('post') && $request->input('libelle_type_vehicule')) {

                $data = $request->all(); 

            try {

                $TypeVehicule = TypeVehicule::where('libelle_type_vehicule', $data['libelle_type_vehicule'])->first();
                if($TypeVehicule){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $typeVehicule = new TypeVehicule;
                $typeVehicule->libelle_type_vehicule = $data['libelle_type_vehicule'];
                $typeVehicule->created_by = Auth::user()->id;
                $typeVehicule->save();
                $jsonData["data"] = json_decode($typeVehicule);
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
     * @param  \App\TypeVehicule  $typeVehicule
     * @return Response
     */
    public function update(Request $request, $id)
    {
         $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
        $typeVehicule = TypeVehicule::find($id);

        if($typeVehicule){
            try {
                $typeVehicule->update([
                    'libelle_type_vehicule' => $request->get('libelle_type_vehicule'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($typeVehicule);
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
     * @param  \App\TypeVehicule  $typeVehicule
     * @return Response
     */
    public function destroy($id)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
        $typeVehicule = TypeVehicule::find($id);
        
        if($typeVehicule){
            try {
                $typeVehicule->update(['deleted_by' => Auth::user()->id]);
                $typeVehicule->delete();
                $jsonData["data"] = json_decode($typeVehicule);
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
