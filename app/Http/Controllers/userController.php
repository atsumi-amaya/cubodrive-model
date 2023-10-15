<?php

namespace App\Http\Controllers;

use Hitrov\OCI\Signer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use App\Models\User;
use App\Models\document;
use App\Models\code;
use App\Models\invitado;
use App\Http\Requests\createUser;
use App\Http\Requests\sendEmail;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Mail\RecoveryMail;
use App\Mail\CodeMail;


class userController extends Controller
{
    
    //Lista de usuarios
    public function index()
    {  
        $users = User::where('rol',1)->get();
        return view('user.show', [ 'users' => $users ]);
    }
    
    //Codigo de cliente
    public function code()
    {
        $codes = code::all();
        return view('user.code.show', [ 'codes' => $codes ]);
    }
    
    public function newcode()
    {
        return view('user.code.create');
    }
    
    public function createcode(Request $request)
    {
        $email = $request->get('email');
        $code = Str::random(9);
        Mail::to($email)->send(new Codemail($code));
        
        $newcode = new code([
                'descripcion' => $request->get('descripcion'),
                'email' => $request->get('email'),
                'code' => $code,
                'estado' => 0,
            ]);
        $newcode->save();
        return redirect()->route('code');
    }
    
    //Crear usuario
    public function create()
    {  
        return view('user.create');
    }
    
    //Creacion de usuario
    public function store(createUser $request): RedirectResponse
    {
        $signer = new Signer();
        $curl = curl_init();

        $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/';
        $method = 'POST';
        $body = '{ "compartmentId":"ocid1.compartment.oc1..aaaaaaaami5zzwbzhfpoy6dwwivuve6nssduka2gdok3vgq6tbiz7xv4e3xa", "name": "'.strtoupper($request->get('username')).'"}';

        $headers = $signer->getHeaders($url, $method, $body, 'application/json');
        //var_dump($headers);

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($body) {
            // not needed for GET or HEAD requests
            $curlOptions[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $curlOptions);

        curl_exec($curl);
        curl_close($curl);
        //preauth create
        $signer = new Signer();
        $curl = curl_init();
        $fecha_actual = date("d-m-Y");
        $expireTime=date("c",strtotime($fecha_actual."+ 1 month")); 
        $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.strtoupper($request->get('username')).'/p/';
        $method = 'POST';
        $body = '{ "accessType":"AnyObjectReadWrite", "name": "'.strtoupper($request->get('username')).'","timeExpires":"'.$expireTime.'"}';

        $headers = $signer->getHeaders($url, $method, $body, 'application/json');
        //var_dump($headers);

        $curlOptions = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ];

        if ($body) {
            // not needed for GET or HEAD requests
            $curlOptions[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $curlOptions);

        $response = curl_exec($curl);
        curl_close($curl);
        $json=json_decode($response);
        //return $json->fullPath;
        $user = new User([
            'username' => strtoupper($request->get('username')),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
            'lider' => '',
            'rol' => 1,
            'limite' => 21474836480,
            'uso' => 0,
            'plan' => 1,
            'pre_auth' => $json->fullPath,
            'pre_auth_id' => $json->id,
            'pre_auth_expire' => strtotime($fecha_actual."+ 1 month"),
        ]);
        $user->save();
        return redirect()->route('user')->with('userCreate', 'ok');
    }

    //Invitados
    public function guest()
    {  
        $liders = User::where('rol','!=',2)->get();
        if (Auth::user()->rol == 0) {
            $users = User::where('rol',2)->get();
        } else {
            $users = User::where('rol',2)->where('lider',Auth::user()->id)->get();
        }
        return view('user.guest', [ 'users' => $users, 'liders' => $liders  ]);
    }

    //Cambio de password
    public function pass(int $id)
    {
        $user = User::find($id);
        return view('user.pass', ['user' => $user]);
    }

    public function newpass(Request $request,int $id): RedirectResponse
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required',
        ]);
        $user = User::find($id);
        if ($request->get('password') != $request->get('confirm_password')) {
            throw ValidationException::withMessages([
                'confirm_password' => 'no coincide con la nueva contraseña'
            ]);
        } 
        $user->password = Hash::make($request->get('password'));
        $user->save();
        if (Auth::user()->id == $id) {
            if ($user->rol != 2) {
                return redirect()->route('docum');
            } else {
                return redirect()->route('shared');
            }
        } else {
            if ($user->rol != 2) {
                return redirect()->route('user');
            } else {
                return redirect()->route('guest');
            }
        }
        
    }

    //Recuperacion de password
    public function recoverypass()
    {
        return view('user.pass.recovery');
    }

    public function sendrecoverypass(sendEmail $req)
    {
        $ramdon = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 11); 
        $email = $req->get('email');
        $user = User::where('email', $email)->first();
        $code = $ramdon.$user->id;
        $user->remember_token = $code;
        $user->save();
        //$post = "http://localhost:8000/user-passR/{$code}";
        $post = "https://cubodrive.com/user-passR/{$code}";
        Mail::to($email)->send(new RecoveryMail($post));
        return redirect()->route('login')->with('passSend', 'ok');
    }

    public function resetpassView(string $code)
    {
        $id = substr($code,-1);
        $user = User::where('id', $id)->first();
        $valid = 'no';
        if ($user->remember_token == $code){
            $valid = 'yes';
        }
        //return view('user.pass.email', [ 'post' => $post]);
        return view('user.pass.reset', [ 'user' => $user,  'valid' => $valid]);
    }

    public function resertpass(Request $request, int $id)
    {
         $request->validate([
            'password' => 'required',
            'confirm_password' => 'required',
        ]);
        $user = User::find($id);
        if ($request->get('password') != $request->get('confirm_password')) {
            throw ValidationException::withMessages([
                'confirm_password' => 'no coincide con la nueva contraseña'
            ]);
        } 
        $user->password = Hash::make($request->get('password'));
        $user->remember_token = '';
        $user->save();
        return view('user.pass.resetsuccess');
    }

    //Eliminar usuario
    public function destroy(int $id): RedirectResponse
    {
        $user = User::find($id);
        $rol = $user->rol;
        if ($rol != 2) {
            $docs = document::where('propietario',$id)->get();
            if(count($docs)>0)
            {
                foreach ($docs as $doc) 
                {
                    if($doc->formato != 'carpeta')
                    {
                        $signer = new Signer();
                        $curl = curl_init();
                
                        $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.$user->username.'/o/'.$doc->filecode;
                        $method = 'DELETE';
                        $body = '{}';
            
                        $headers = $signer->getHeaders($url, $method, $body, 'application/json');
                        //var_dump($headers);
                
                        $curlOptions = [
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 5,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => $method,
                            CURLOPT_HTTPHEADER => $headers,
                        ];
                
                        if ($body) {
                            // not needed for GET or HEAD requests
                            $curlOptions[CURLOPT_POSTFIELDS] = $body;
                        }
                
                        curl_setopt_array($curl, $curlOptions);
                
                        $response = curl_exec($curl);
                    }
                    $doc->delete();
                }
            }
            
            
            $signer = new Signer();
            $curl = curl_init();
    
            $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.$user->username.'/p/'.$user->pre_auth_id;
            $method = 'DELETE';
            $body = '{ }';
    
            $headers = $signer->getHeaders($url, $method, $body, 'application/json');
            //var_dump($headers);
    
            $curlOptions = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
            ];
    
            if ($body) {
                // not needed for GET or HEAD requests
                $curlOptions[CURLOPT_POSTFIELDS] = $body;
            }
    
            curl_setopt_array($curl, $curlOptions);
    
            curl_exec($curl);
            curl_close($curl);
            
            //delete bucket
            $signer = new Signer();
            $curl = curl_init();
    
            $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.$user->username;
            $method = 'DELETE';
            $body = '{ "compartmentId":"ocid1.compartment.oc1..aaaaaaaami5zzwbzhfpoy6dwwivuve6nssduka2gdok3vgq6tbiz7xv4e3xa", "name": "'.$user->username.'"}';
    
            $headers = $signer->getHeaders($url, $method, $body, 'application/json');
            //var_dump($headers);
    
            $curlOptions = [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers,
            ];
    
            if ($body) {
                // not needed for GET or HEAD requests
                $curlOptions[CURLOPT_POSTFIELDS] = $body;
            }
    
            curl_setopt_array($curl, $curlOptions);
    
            curl_exec($curl);
            curl_close($curl);
            
            $guests = User::where('lider',$id)->get();
            foreach ($guests as $guest) 
            {   
                $perms = invitation::where('invitado_id',$guest->id)->get();
                foreach ($perms as $perm) 
                {
                    $perm->delete();
                }
                $guest->delete();
            }
        } 
        $user->delete();
        if ($rol != 2) {
            return redirect()->route('user')->with('userDelete', 'ok');
        } else {
            $perms = invitado::where('invitado_id', $id)->get();
            foreach ($perms as $perm) {
                $perm->delete();
            }
            return redirect()->route('guest')->with('userDelete', 'ok');
        }
    }
    
    //Cambiar permisos
    public function guestp(int $id, Request $request)
    {
        $process = $request->get('permiso');
        $guest = invitado::find($id);
        switch ($process) {
            case 'ver':
                if ($guest->ver == 0) {
                    $guest->ver = 1;
                } else {
                    $guest->ver = 0;
                }
                break;
            case 'editar':
                if ($guest->editar == 0) {
                    $guest->editar = 1;
                } else {
                    $guest->editar = 0;
                }
                break;
            case 'mover':
                if ($guest->mover == 0) {
                    $guest->mover = 1;
                } else {
                    $guest->mover = 0;
                }
                break;
            case 'descargar':
                if ($guest->descargar == 0) {
                    $guest->descargar = 1;
                } else {
                    $guest->descargar = 0;
                }
                break;
            case 'eliminar':
                if ($guest->eliminar == 0) {
                    $guest->eliminar = 1;
                } else {
                    $guest->eliminar = 0;
                }
                break;
            default:
                # code...
                break;
        }
        $guest->save();
        return redirect()->back();
    }
}
