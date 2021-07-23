<?php

namespace App\Http\Controllers\Parametre;

use App\Http\Controllers\Controller;
use App\Models\Parametre\Puissance;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use function response;
use function view;

class PuissanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {       
       $menuPrincipal = "Paramètre";
       $titleControlleur = "Puissance";
       $btnModalAjout = "FALSE";
       return view('parametre.puissance.index',compact('menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }
    
    public function listePuissance()
    {
       $puissances = DB::table('puissances')->select('puissances.*')->Where('deleted_at', NULL)->orderBy('libelle_puissance', 'ASC')->get();
       $jsonData["rows"] = $puissances->toArray();
       $jsonData["total"] = $puissances->count();
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
        if ($request->isMethod('post') && $request->input('libelle_puissance')) {

                $data = $request->all(); 

            try {

                $Puissance = Puissance::where('libelle_puissance', $data['libelle_puissance'])->first();
                if($Puissance){
                    return response()->json(["code" => 0, "msg" => "Cet enregistrement existe déjà dans la base", "data" => NULL]);
                }

                $puissance = new Puissance;
                $puissance->libelle_puissance = $data['libelle_puissance'];
                $puissance->created_by = Auth::user()->id;
                $puissance->save();
                $jsonData["data"] = json_decode($puissance);
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
        
        $puissance = Puissance::find($id);

        if($puissance){
            try {
                $puissance->update([
                    'libelle_puissance' => $request->get('libelle_puissance'),
                    'updated_by' => Auth::user()->id,
                ]);
                $jsonData["data"] = json_decode($puissance);
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
        $puissance = Puissance::find($id);
        
        if($puissance){
            try {
                $puissance->update(['deleted_by' => Auth::user()->id]);
                $puissance->delete();
                $jsonData["data"] = json_decode($puissance);
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
