<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Application\Chauffeur;
use App\Models\Application\Compagnie;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChauffeurController extends Controller
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
       $titleControlleur = "Liste des chauffeurs";
       (Auth::user()->role == 'Administrateur' or Auth::user()->role == 'Superviseur') ? $btnModalAjout = "FALSE" : $btnModalAjout = "TRUE";
       return view('application.chauffeur.index',compact('compagnies', 'menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }

    public function listeChauffeur()
    {
        if(Auth::user()->compagnie_id!=null){
        $chauffeurs = Chauffeur::where([['chauffeurs.deleted_at', NULL],['chauffeurs.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                        ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                        ->get();
        }else{
        $chauffeurs = Chauffeur::with('compagnie')
                        ->where('chauffeurs.deleted_at', NULL) 
                        ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                        ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                        ->get();
        }
       $jsonData["rows"] = $chauffeurs->toArray();
       $jsonData["total"] = $chauffeurs->count();
       return response()->json($jsonData);
    }
    
    public function listeChauffeurByCompagnie($compagnie){
        $chauffeurs = Chauffeur::with('compagnie')
                        ->where([['chauffeurs.deleted_at', NULL],['chauffeurs.compagnie_id',$compagnie]]) 
                        ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                        ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                        ->get();
        $jsonData["rows"] = $chauffeurs->toArray();
        $jsonData["total"] = $chauffeurs->count();
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
        if ($request->isMethod('post') && $request->input('full_name_chauffeur')) {

                $data = $request->all(); 

            try {
                
                $chauffeur = new Chauffeur;
                $chauffeur->full_name_chauffeur = $data['full_name_chauffeur'];
                $chauffeur->civilite = $data['civilite'];
                $chauffeur->numero_permis = $data['numero_permis'];
                $chauffeur->groupe_sanguin = $data['groupe_sanguin'];
                $chauffeur->adresse_chauffeur = $data['adresse_chauffeur'];
                $chauffeur->contact_chauffeur = $data['contact_chauffeur'];
                $chauffeur->date_prise_service = Carbon::createFromFormat('d-m-Y', $data['date_prise_service']);
                $chauffeur->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $chauffeur->date_fin_permis = Carbon::createFromFormat('d-m-Y', $data['date_fin_permis']);
                $chauffeur->contact_en_cas_urgence = $data['contact_en_cas_urgence'];
                $chauffeur->compagnie_id = Auth::user()->compagnie_id;
                $chauffeur->contact_conjoint = isset($data['contact_conjoint']) && !empty($data['contact_conjoint']) ? $data['contact_conjoint'] : null;
                $chauffeur->created_by = Auth::user()->id;
                $chauffeur->save();
                $jsonData["data"] = json_decode($chauffeur);
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
     * @param  \App\Chauffeur  $chauffeur
     * @return Response
     */
    public function update(Request $request, Chauffeur $chauffeur)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        
         if($chauffeur){
             $data = $request->all(); 
            try {
                 $chauffeur->full_name_chauffeur = $data['full_name_chauffeur'];
                $chauffeur->civilite = $data['civilite'];
                $chauffeur->numero_permis = $data['numero_permis'];
                $chauffeur->groupe_sanguin = $data['groupe_sanguin'];
                $chauffeur->adresse_chauffeur = $data['adresse_chauffeur'];
                $chauffeur->contact_chauffeur = $data['contact_chauffeur'];
                $chauffeur->date_prise_service = Carbon::createFromFormat('d-m-Y', $data['date_prise_service']);
                $chauffeur->date_naissance = Carbon::createFromFormat('d-m-Y', $data['date_naissance']);
                $chauffeur->date_fin_permis = Carbon::createFromFormat('d-m-Y', $data['date_fin_permis']);
                $chauffeur->contact_en_cas_urgence = $data['contact_en_cas_urgence'];
                $chauffeur->contact_conjoint = isset($data['contact_conjoint']) && !empty($data['contact_conjoint']) ? $data['contact_conjoint'] : null;
                $chauffeur->updated_by = Auth::user()->id;
                $chauffeur->save();
                $jsonData["data"] = json_decode($chauffeur);
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
     * @param  \App\Chauffeur  $chauffeur
     * @return Response
     */
    public function destroy(Chauffeur $chauffeur)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($chauffeur){
                try {
             
                $chauffeur->update(['deleted_by' => Auth::user()->id]);
                $chauffeur->delete();
                $jsonData["data"] = json_decode($chauffeur);
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
    
    //Liste des chauffeurs
    public function listeChauffeurPdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeChauffeurs());
        return $pdf->stream('liste_chauffeurs.pdf');
    }
    public function listeChauffeurs(){
        if(Auth::user()->compagnie_id!=null){
        $datas = Chauffeur::where([['chauffeurs.deleted_at', NULL],['chauffeurs.compagnie_id',Auth::user()->compagnie_id]]) 
                        ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                        ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                        ->get();
        }else{
        $datas = Chauffeur::where('chauffeurs.deleted_at', NULL) 
                        ->join('compagnies','compagnies.id','=','chauffeurs.compagnie_id')
                        ->select('chauffeurs.*','compagnies.libelle_compagnie',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                        ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                        ->get();
        }
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des chauffeurs</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="35%" align="center">Nom complet</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date de naissance</th>
                            <th cellspacing="0" border="2" width="22%" align="center">Contact</th>
                            <th cellspacing="0" border="2" width="25%" align="center">Adresse</th>
                            <th cellspacing="0" border="2" width="22%" align="center">Contact en cas urgence</th>
                            <th cellspacing="0" border="2" width="20%" align="center">N° permis</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Groupe Sang.</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date de prise service.</th>';
                        if(Auth::user()->compagnie_id==null){
                            $outPut .='<th cellspacing="0" border="2" width="20%" align="center">Compagnie</th>';
                        }
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_naissances.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->contact_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->adresse_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->contact_en_cas_urgence.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_permis.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->groupe_sanguin.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_prise_services.'</td>';
                            if(Auth::user()->compagnie_id==null){
                                $outPut .= '<td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>';
                            }
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Chauffeur(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
    //Liste des chauffeurs par compagnie
    public function listeChauffeurByCompagniePdf($compagnie){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeChauffeurByCompagnies($compagnie));
        $info_compagnie = Compagnie::find($compagnie);
        return $pdf->stream('liste_chauffeurs_de_compagnie_'.$info_compagnie->libelle_compagnie.'.pdf');
    }
    public function listeChauffeurByCompagnies($compagnie){
        $info_compagnie = Compagnie::find($compagnie);
        $datas = Chauffeur::where([['chauffeurs.deleted_at', NULL],['chauffeurs.compagnie_id',$compagnie]]) 
                        ->select('chauffeurs.*',DB::raw('DATE_FORMAT(chauffeurs.date_fin_permis, "%d-%m-%Y") as date_fin_permiss'),DB::raw('DATE_FORMAT(chauffeurs.date_naissance, "%d-%m-%Y") as date_naissances'),DB::raw('DATE_FORMAT(chauffeurs.date_prise_service, "%d-%m-%Y") as date_prise_services'))
                        ->orderBy('chauffeurs.full_name_chauffeur', 'ASC')
                        ->get();
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des chauffeurs de la compagnie '.$info_compagnie->libelle_compagnie.'</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="35%" align="center">Nom complet</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date de naissance</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Contact</th>
                            <th cellspacing="0" border="2" width="25%" align="center">Adresse</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Contact en cas urgence</th>
                            <th cellspacing="0" border="2" width="20%" align="center">N° permis</th>
                            <th cellspacing="0" border="2" width="10%" align="center">Groupe Sang.</th>
                            <th cellspacing="0" border="2" width="15%" align="center">Date de prise service.</th>';
                    $outPut.='</tr>
                   </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->civilite.'. '.$data->full_name_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_naissances.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->contact_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->adresse_chauffeur.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->contact_en_cas_urgence.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->numero_permis.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->groupe_sanguin.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->date_prise_services.'</td>';
                    $outPut.='</tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Chauffeur(s)';
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
