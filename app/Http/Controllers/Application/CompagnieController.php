<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Models\Application\Compagnie;
use App\Notifications\RegistredUserNotification;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CompagnieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
       $menuPrincipal = "Application";
       $titleControlleur = "Compagnie";
       (Auth::user()->role == 'Concepteur' or Auth::user()->role == 'Administrateur') ? $btnModalAjout = "TRUE" : $btnModalAjout = "FALSE";
       return view('application.compagnie.index',compact('menuPrincipal', 'titleControlleur','btnModalAjout')); 
    }

    public function listeCompagnie()
    {
      $compagnies = Compagnie::where('compagnies.deleted_at', NULL) 
                        ->select('compagnies.*')
                        ->orderBy('compagnies.libelle_compagnie', 'ASC')
                        ->get();
       $jsonData["rows"] = $compagnies->toArray();
       $jsonData["total"] = $compagnies->count();
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
        if ($request->isMethod('post') && $request->input('libelle_compagnie')) {

                $data = $request->all(); 

            try {
                $User = User::where('email', $data['email_compagnie'])->first();
                if($User){
                    return response()->json(["code" => 0, "msg" => "Ce compte existe déjà. Vérifier l'adresse mail", "data" => NULL]);
                }
                $compagnie = new Compagnie;
                $compagnie->libelle_compagnie = $data['libelle_compagnie'];
                $compagnie->adresse_complet = $data['adresse_complet'];
                $compagnie->contact_compagnie = $data['contact_compagnie'];
                $compagnie->email_compagnie = $data['email_compagnie'];
                $compagnie->responsable = $data['responsable'];
                $compagnie->contact_responsable = $data['contact_responsable'];
                $compagnie->longitude = isset($data['longitude']) && !empty($data['longitude']) ? $data['longitude'] : null;
                $compagnie->latitude = isset($data['latitude']) && !empty($data['latitude']) ? $data['latitude'] : null;
                
                //Ajout de l'image du matériel s'il y a en 
                if(isset($data['logo'])){
                    $logo = request()->file('logo');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$logo->getClientOriginalName()));
                    //Vérification du format de fichier
                    $extensions = array('.png','.jpg', '.jpeg');
                    $extension = strrchr($file_name, '.');
                    //Début des vérifications de sécurité...
                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                    {
                        return response()->json(["code" => 0, "msg" => "Vous devez uploader un fichier de type jpeg png, jpg", "data" => NULL]);
                    }
                    $path = public_path().'/img/logo/';
                    $logo->move($path,$file_name);
                    $compagnie->logo = 'img/logo/'.$file_name;
                }               
                
                $compagnie->created_by = Auth::user()->id;
                $compagnie->save();
                
                if($compagnie){
                    $user = new User;
                    $user->full_name = $data['responsable'];
                    $user->role = 'Compagnie';
                    $user->admin_compagnie = TRUE;
                    $user->compagnie_id = $compagnie->id;
                    $user->contact = $data['contact_responsable'];
                    $user->email = $data['email_compagnie'];
                    $user->password = bcrypt(Str::random(10)); 
                    $user->confirmation_token = str_replace('/', '', bcrypt(Str::random(16))); 
                    $user->created_by = Auth::user()->id;
                    $user->save();
                    $user->notify(new RegistredUserNotification());
                }
                $jsonData["data"] = json_decode($compagnie);
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
     * @param  \App\Compagnie  $compagnie
     * @return Response
     */
    public function updateCompagnie(Request $request)
    {
        $jsonData = ["code" => 1, "msg" => "Modification effectuée avec succès."];
        $data = $request->all(); 
        $compagnie = Compagnie::find($data['idCompagnie']);
        
        if($compagnie){
            try {
                $old_email_compagnie = $compagnie->email_compagnie;
                $compagnie->libelle_compagnie = $data['libelle_compagnie'];
                $compagnie->adresse_complet = $data['adresse_complet'];
                $compagnie->contact_compagnie = $data['contact_compagnie'];
                $compagnie->email_compagnie = $data['email_compagnie'];
                $compagnie->responsable = $data['responsable'];
                $compagnie->contact_responsable = $data['contact_responsable'];
                $compagnie->longitude = isset($data['longitude']) && !empty($data['longitude']) ? $data['longitude'] : null;
                $compagnie->latitude = isset($data['latitude']) && !empty($data['latitude']) ? $data['latitude'] : null;
                
                //Ajout de l'image du matériel s'il y a en 
                if(isset($data['logo'])){
                    $logo = request()->file('logo');
                    $file_name = str_replace(' ', '_', strtolower(time().'.'.$logo->getClientOriginalName()));
                    //Vérification du format de fichier
                    $extensions = array('.png','.jpg', '.jpeg');
                    $extension = strrchr($file_name, '.');
                    //Début des vérifications de sécurité...
                    if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
                    {
                        return response()->json(["code" => 0, "msg" => "Vous devez uploader un fichier de type jpeg png, jpg", "data" => NULL]);
                    }
                    $path = public_path().'/img/logo/';
                    $logo->move($path,$file_name);
                    $compagnie->logo = 'img/logo/'.$file_name;
                }  
                $compagnie->updated_by = Auth::user()->id;
                $compagnie->save();
                
                if($compagnie->email_compagnie!=$old_email_compagnie){
                    $user = User::where('email',$old_email_compagnie)->first();
                    $user->full_name = $data['responsable'];
                    $user->contact = $data['contact_responsable'];
                    $user->email = $data['email_compagnie'];
                    $user->password = bcrypt(Str::random(10)); 
                    $user->confirmation_token = str_replace('/', '', bcrypt(Str::random(16))); 
                    $user->updated_by = Auth::user()->id;
                    $user->save();
                    $user->notify(new RegistredUserNotification());
                }else{
                    $user = User::where('email',$compagnie->email_compagnie)->first();
                    $user->full_name = $data['responsable'];
                    $user->contact = $data['contact_responsable'];
                    $user->updated_by = Auth::user()->id;
                    $user->save();
                }
           
                $jsonData["data"] = json_decode($compagnie);
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
     * @param  \App\Compagnie  $compagnie
     * @return Response
     */
    public function destroy($id)
    {
        $compagnie = Compagnie::find($id);
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];
            if($compagnie){
                try {
               
                //On veroue le compte avant de suprimer la compagnie
                $user = User::where('email',$compagnie->email_compagnie)->first();
                $user->statut_compte = FALSE;
                $user->save();
                
                $compagnie->update(['deleted_by' => Auth::user()->id]);
                $compagnie->delete();
                $jsonData["data"] = json_decode($compagnie);
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
    
     //Liste des agences
    public function listeCompagniePdf(){
        $pdf = \App::make('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->setPaper('A4', 'landscape');
        $pdf->loadHTML($this->listeCompagnies());
        return $pdf->stream('liste_compagnies.pdf');
    }
    public function listeCompagnies(){
        $datas = Compagnie::where('compagnies.deleted_at', NULL) 
                        ->select('compagnies.*')
                        ->orderBy('compagnies.libelle_compagnie', 'ASC')
                        ->get();
        
        $outPut = $this->header();
        $outPut .= '<div class="container-table"><h3 align="center"><u>Liste des compagnies</h3>
                    <table border="2" cellspacing="0" width="100%">
                        <tr>
                            <th cellspacing="0" border="2" width="25%" align="center">Compagnie</th>
                            <th cellspacing="0" border="2" width="30%" align="center">Adresse géo.</th>
                            <th cellspacing="0" border="2" width="20%" align="center">Contact</th>
                            <th cellspacing="0" border="2" width="20%" align="center">E-mail</th>
                            <th cellspacing="0" border="2" width="25%" align="center">Responsable</th>
                            <th cellspacing="0" border="2" width="25%" align="center">Contact respon.</th>
                        </tr>
                    </div>';
         $total = 0; 
       foreach ($datas as $data){
           $total = $total + 1;
           $outPut .= '<tr>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->libelle_compagnie.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->adresse_complet.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->contact_compagnie.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->email_compagnie.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->responsable.'</td>
                            <td  cellspacing="0" border="2" align="left">&nbsp;&nbsp;'.$data->contact_responsable.'</td>
                        </tr>';
       }
        $outPut .='</table>';
        $outPut.='<br/> Nombre totale:<b> '.number_format($total, 0, ',', ' ').'</b> Compagnie(s)';
        $outPut.= $this->footer();
        return $outPut;
    }
    
     //Header and footer des pdf
    public function header(){
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
            <img src="images/logo.png" width="200" height="160"/>
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
