<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Notifications\RegistredUserNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $menuPrincipal = "Auth";
        $titleControlleur = "Gestion des utilisateurs";
        $btnModalAjout = "TRUE";
        return view('auth.user.index', compact('btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }
    
    public function userCompagnieVue(){
        $menuPrincipal = "Auth";
        $titleControlleur = "Utilisateur des compagnies";
        $btnModalAjout = "FALSE";
        return view('auth.user.user-compagnie', compact('btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }

    public function listeUser() {
        $users = DB::table('users')
                ->select(DB::raw('DATE_FORMAT(users.last_login_at, "%d-%m-%Y à %H:%i:%s") as last_login'),'users.*')
                ->orderBy('users.full_name', 'ASC')
                ->where([['users.deleted_at', NULL],['users.id','!=',1],['users.role','!=','Compagnie']])
                ->get();

       $jsonData["rows"] = $users->toArray();
       $jsonData["total"] = $users->count();
       
        return response()->json($jsonData);
    }
    
    public function listeUserCompagnie() {
        $users = User::with('compagnie')
                ->select('users.*',DB::raw('DATE_FORMAT(users.last_login_at, "%d-%m-%Y à %H:%i:%s") as last_login'))
                ->orderBy('users.full_name', 'ASC')
                ->where([['users.deleted_at', NULL],['users.compagnie_id','!=',Null]])
                ->get();

       $jsonData["rows"] = $users->toArray();
       $jsonData["total"] = $users->count();
       
        return response()->json($jsonData);
    }
    
    public function profil() {
        $user = DB::table('users')
                ->select('users.*',DB::raw('DATE_FORMAT(users.last_login_at, "%d-%m-%Y à %H:%i:%s") as last_login'),DB::raw('DATE_FORMAT(users.created_at, "%d-%m-%Y à %H:%i:%s") as created'))
                ->where('users.id', Auth::user()->id)
                ->first();

        $menuPrincipal = "Auth";
        $titleControlleur = "Profil utilisateur";
        $btnModalAjout = "FALSE";
        return view('auth.user.profil', compact('user','btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }
    
    public function infosProfiTolUpdate(){
         $user = DB::table('users')
                ->select('users.*')
                ->where('users.id', Auth::user()->id)
                ->first();
        $menuPrincipal = "Auth";
        $titleControlleur = "Informations du profil à modifier";
        $btnModalAjout = "FALSE";
        return view('auth.user.infos-profil-to-update', compact('user','btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }
    
    public function updatePasswordPage(){
         $user = DB::table('users')
                ->select('users.*')
                ->where('users.id', Auth::user()->id)
                ->first();
        $menuPrincipal = "Auth";
        $titleControlleur = "Modification du mot de passe";
        $btnModalAjout = "FALSE";
        return view('auth.user.update-password', compact('user','btnModalAjout', 'menuPrincipal', 'titleControlleur'));
    }

    public function updateProfil(Request $request, $id){
        $jsonData = ["code" => 1, "msg" => "Modification effectué avec succès."];
        $user = User::find($id);
        
        if ($user) {
            $data = $request->all();
            
            try {
              
                $user->full_name = $data['full_name'];
                $user->contact = $data['contact'];
                $user->email = $data['email'];
                $user->updated_by = Auth::user()->id;
                $user->save();
                    
                $jsonData["data"] = json_decode($user);
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
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
        if ($request->isMethod('post') && $request->input('full_name')) {

            $data = $request->all();
          
            $user = User::where('email', $data['email'])->first();
            if($user){
                return response()->json(["code" => 0, "msg" => "Ce compte existe déjà. Vérifier l'adresse mail", "data" => NULL]);
            }else{
                try {
                    $users = new User;
                    $users->full_name = $data['full_name'];
                    $users->role = $data['role'];
                    $users->contact = $data['contact'];
                    $users->email = $data['email'];
                    $users->password = bcrypt(Str::random(10)); 
                    $users->confirmation_token = str_replace('/', '', bcrypt(Str::random(16))); 
                    $users->created_by = Auth::user()->id;
                    $users->save();
                    $users->notify(new RegistredUserNotification());

                    $jsonData["data"] = json_decode($users);

                    return response()->json($jsonData);
                } catch (Exception $exc) {
                    $jsonData["code"] = -1;
                    $jsonData["data"] = NULL;
                    $jsonData["msg"] = $exc->getMessage();
                    return response()->json($jsonData);
                } 
            }
        }
        return response()->json(["code" => 0, "msg" => "Saisie invalide", "data" => NULL]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
              
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $menuPrincipal = "Auth";
        $titleControlleur = "Modifier mes informations";
        return view('auth.user.profil_update', compact('user', 'menuPrincipal', 'titleControlleur'));
  
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

        $user = User::find($id);
        
        if ($user) {
            $data = $request->all();
            
            try {
                if($user->contact == $data['contact']){
                    
                }else{
                   $userCont = User::where('contact', $data['contact'])->first();
                    if($userCont){
                        return response()->json(["code" => 0, "msg" => "Ce compte existe déjà. Vérifier le contact", "data" => NULL]);
                    }
                }
                
                if($user->email == $data['email']){
                    $user->full_name = $data['full_name'];
                    $user->role = $data['role'];
                    $user->contact = $data['contact']; 
                    $user->updated_by = Auth::user()->id;
                    $user->save();
                }else{
                    $userMail = User::where('email', $data['email'])->first();
                    if($userMail){
                        return response()->json(["code" => 0, "msg" => "Ce compte existe déjà. Vérifier l'adresse mail", "data" => NULL]);
                    }
                   
                    $user->full_name = $data['full_name'];
                    $user->role = $data['role'];
                    $user->contact = $data['contact'];
                    $user->email = $data['email'];
                    $user->password = bcrypt(Str::random(10)); 
                    $user->confirmation_token = str_replace('/', '', bcrypt(Str::random(16))); 
                    $user->notify(new RegistredUserNotification());
                    $user->updated_by = Auth::user()->id;
                    $user->save();
                }
                    
                $jsonData["data"] = json_decode($user);
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
     * Activer ou désactiver un utilisateur.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès."];

        try {
                $user = User::find($id);
                if($user->statut_compte == 1){
                      $user->statut_compte = FALSE;
                }else{
                       $user->statut_compte = TRUE; 
                }
                $user->save();
                $jsonData["data"] = json_decode($user);
                return response()->json($jsonData);
        } catch (Exception $exc) {
            $jsonData["code"] = -1;
            $jsonData["data"] = NULL;
            $jsonData["msg"] = $exc->getMessage();
            return response()->json($jsonData);
        }
    }

//Réinitialisation du mot de passe par l'administrateur
    public function resetPasswordManualy($id){
         $jsonData = ["code" => 1, "msg" => " Opération effectuée avec succès"];
             
             $user = User::find($id);
             $password = "";
            if($user && $user->statut_compte != 0){ 
                try {
                     //Geration du passsword à 8 chiffre
                    $ranges = array(range('a', 'z'), range('A', 'Z'), range(1, 9));
                    $password = '';
                    for ($i = 0; $i < 8; $i++) {
                        $rkey = array_rand($ranges);
                        $vkey = array_rand($ranges[$rkey]);
                        $password.= $ranges[$rkey][$vkey];
                    }
                    $user->password = bcrypt($password);
                    $user->updated_by = $user->id;
                    $user->save();
                   $to_name = $user->full_name;
                    $to_email = $user->email;
                    $data = array("name"=>$user->full_name, "body" => "Vous avez démandé à rénitialiser votre mot de passe. Votre nouveau mot de passse est : ".$password);
  
                    Mail::send('auth/user/mail', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                    ->subject('Rénitialisation de votre mot de passe Car-Ticket');
                    $message->from('cart-ticket@gesi-ci.com','Car-Ticket');
                    });
                    $jsonData["data"] = json_decode($user);
                    return response()->json($jsonData);
                } catch (Exception $exc) {
                   $jsonData["code"] = -1;
                   $jsonData["data"] = NULL;
                   $jsonData["msg"] = $exc->getMessage();
                   return response()->json($jsonData); 
                }
            }
            return response()->json(["code" => 0, "msg" => "Ce compte n'existe pas ou a été fermé !", "data" => NULL]);
    }

//Modification du mot de passe par l'utilisateur
    public function updatePasswordProfil(Request $request){
         $data = $request->all();
        $user = User::find($data['idUser']);
        if ($user) {
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)){
               return redirect()->back()->with('error', 'Votre ancien mot de passe est incorrect.');
            }
          
            $request->validate([
                'new_password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$@%]).*$/|confirmed|'
            ]);
            $user->password = bcrypt($data['new_password']);
            $user->updated_by = $user->id;
            $user->save();
            return redirect()->route('auth.profil-informations');
        }
    }
}
