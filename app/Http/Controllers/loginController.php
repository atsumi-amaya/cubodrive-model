<?php

namespace App\Http\Controllers;

use Hitrov\OCI\Signer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\code;
use App\Http\Requests\login;
use App\Http\Requests\createUser;
use Illuminate\Support\Facades\Redirect;
use App\Models\document;
use App\Models\format;
use DateTime;
use App\Models\invitado;

class loginController extends Controller
{
    public function index()
    { 
        if (Auth::check()) 
        {
            if (Auth::user()->rol == 2) 
            {
                return redirect()->route('shared');
            } 
            else 
            {
                return redirect()->route('docum');
            }
        } 
        else 
        {
            return redirect('https://cubodrive.com/user-registe');
        }
    }
    
    public function register()
    {
        return view('register');
    }
    
    public function registerUser(createUser $request)
    {
        if($request->get('password') != $request->get('password1'))
        {
            throw ValidationException::withMessages([
                'password1' => 'contraseñas no coinciden'
            ]);
        }
        
        $PlanLimit = 5368709120;
        $Plan = 2;
        
        if($request->get('code') != '')
        {
            $code = code::where('code', $request->get('code'))->first();
            if($code === null)
            {
                throw ValidationException::withMessages([
                    'code' => 'Codigo no valido'
                ]);
            }
            else
            {
                if($code->estado != 0)
                {
                    throw ValidationException::withMessages([
                        'code' => 'Codigo no valido'
                    ]);
                }
                $code->estado = 1;
                $PlanLimit = 21474836480;
                $Plan = 1;
                $code->save();
            }
        }
        $signer = new Signer();
        $curl = curl_init();

        $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/';
        $method = 'POST';
        $body = '{ "compartmentId":"ocid1.compartment.oc1..aaaaaaaami5zzwbzhfpoy6dwwivuve6nssduka2gdok3vgq6tbiz7xv4e3xa", "name": "'.strtoupper($request->get('username')).'"}';

        $headers = $signer->getHeaders($url, $method, $body, 'application/json');
        //var_dump($headers);

        $curlOptions = 
        [
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

        if ($body) 
        {
            // not needed for GET or HEAD requests
            $curlOptions[CURLOPT_POSTFIELDS] = $body;
        }

        curl_setopt_array($curl, $curlOptions);

        curl_exec($curl);
        curl_close($curl);
        //preauth create
        $signer = new Signer();
        $curl = curl_init();
        $fecha_actual = date("Y-m-d H:i:s");
        $expireTime=date("c",strtotime($fecha_actual."+ 1 month")); 
        $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.strtoupper($request->get('username')).'/p/';
        $method = 'POST';
        $body = '{ "accessType":"AnyObjectReadWrite", "name": "'.strtoupper($request->get('username')).'","timeExpires":"'.$expireTime.'"}';

        $headers = $signer->getHeaders($url, $method, $body, 'application/json');
        //var_dump($headers);

        $curlOptions = 
        [
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

        if ($body) 
        {
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
            'limite' => $PlanLimit,
            'uso' => 0,
            'plan' => $Plan,
            'pre_auth' => $json->fullPath,
            'pre_auth_id' => $json->id,
            'pre_auth_expire' => date("Y-m-d H:i:s",strtotime($fecha_actual."+ 1 month"))
        ]);
        $user->save();
        
        return redirect()->route('login');
    }
    
    public function log()
    {
        return view('index');
    }
    
    public function login(login $req)
    {
        //$rem = $req->filled('remember');
        $user = User::where('email',$req->get('email'))->first();
        if($user->plan == 2)
        {
            $date1 = new DateTime($user->created_at);
            $date2 = new DateTime("now");
            $diff = $date1->diff($date2);
            if($diff->days >= 30)
            {
                throw ValidationException::withMessages([
                    'email' => 'Cuenta caducada, comuniquece con el administrador'
                ]);
            }
        }
        if (Auth::attempt($req->only('email', 'password'))) 
        {
            $req->session()->regenerate();
            $user = User::find(Auth::user()->id);
            if(Auth::user()->rol == 2)
            {
                $user = User::find(Auth::user()->lider);
            } 
            $expiredate = new DateTime($user->pre_auth_expire);
            $today = new DateTime("now");
            if($today <= $expiredate)
            {
                //preauth delete
                
                $signer = new Signer();
                $curl = curl_init();
                $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.strtoupper($user->username).'/p/'.$user->pre_auth_id;
                $method = 'DELETE';
                $body = '{}';
            
                $headers = $signer->getHeaders($url, $method, $body, 'application/json');
                //var_dump($headers);
            
                $curlOptions = 
                [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 60,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $method,
                    CURLOPT_HTTPHEADER => $headers,
                ];
            
                if ($body) 
                {
                    // not needed for GET or HEAD requests
                    $curlOptions[CURLOPT_POSTFIELDS] = $body;
                }
            
                curl_setopt_array($curl, $curlOptions);
            
                curl_exec($curl);
                curl_close($curl);


                //preauth create
                    
                $signer = new Signer();
                $curl = curl_init();
                $fecha_actual = date("Y-m-d H:i:s");
                $expireTime=date("c",strtotime($fecha_actual."+ 1 month")); 
                $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.strtoupper($user->username).'/p/';
                $method = 'POST';
                $body = '{ "accessType":"AnyObjectReadWrite", "name": "'.strtoupper($user->username).'","timeExpires":"'.$expireTime.'"}';
            
                $headers = $signer->getHeaders($url, $method, $body, 'application/json');
                //var_dump($headers);
            
                $curlOptions = 
                [
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
            
                if ($body) 
                {
                    // not needed for GET or HEAD requests
                    $curlOptions[CURLOPT_POSTFIELDS] = $body;
                }
            
                curl_setopt_array($curl, $curlOptions);
            
                $response = curl_exec($curl);
                curl_close($curl);
                $json=json_decode($response);
                $user->pre_auth = $json->fullPath;
                $user->pre_auth_id = $json->id;
                $user->pre_auth_expire = date("Y-m-d H:i:s",strtotime($fecha_actual."+ 1 month"));
                $user->save();
            }
            /*
            $docums = document::where('propietario',Auth::user()->id)->get();
            $invs = User::where('lider',Auth::user()->id)->get();
            foreach ($invs as $i) {
                $i->pre_auth = Auth::user()->pre_auth;
                $i->save();
            }
            foreach ($docums as $d) {
                $filename = substr($d->direccion, -33);
                $d->direccion = Auth::user()->pre_auth.$filename;
                $d->save();*
            }*/
            if (substr($req->get('prevUrl'), 0, 22) == 'https://cubodrive.com/')
            {
                if (Auth::user()->rol == 2) 
                {
                    return redirect()->route('shared');
                } 
                else 
                {
                    return redirect()->route('docum');
                }
            }
            /*
            if (substr($req->get('prevUrl'), 0, 22) == 'https://cubodrive.com/' && substr($req->get('prevUrl'), 0, 27) != 'https://cubodrive.com/es-pe' && substr($req->get('prevUrl'), 0, 26) != 'https://cubodrive.com/test' && substr($req->get('prevUrl'), 0, 34) != 'https://cubodrive.com/user-registe') {
                if(substr($req->get('prevUrl'), 21,14)!='/user-recovery/' && substr($req->get('prevUrl'), 21,13)!='/user-recovery'){
                    if(substr($req->get('prevUrl'), 21,12)!='/user-passR/' && substr($req->get('prevUrl'), 21,11)!='/user-passR'){
                        if (Auth::user()->rol != 0) {
                            if (Auth::user()->rol == 2) {
                                return redirect()->route('shared');
                            }
                            if (substr($req->get('prevUrl'), 21, 6) == '/user/' || substr($req->get('prevUrl'), -5) == '/user') {
                                return redirect()->route('docum');
                            }
                            if (substr($req->get('prevUrl'), 21, 17) == '/docum-graveyard/' || substr($req->get('prevUrl'), -16) == '/docum-graveyard') {
                                return redirect()->route('docum');
                            }
                        }
                        return Redirect::away($req->get('prevUrl'));
                    } else {
                        if (Auth::user()->rol == 2) {
                            return redirect()->route('shared');
                        } else {
                            return redirect()->route('docum');
                        }
                    }
                } else {
                    if (Auth::user()->rol == 2) {
                        return redirect()->route('shared');
                    } else {
                        return redirect()->route('docum');
                    }
                }
            } else {
                if (Auth::user()->rol == 2) {
                    return redirect()->route('shared');
                } else {
                    return redirect()->route('docum');
                }
            }*/
        } 
        else 
        {
            throw ValidationException::withMessages([
                'password' => 'contraseña incorrecta'
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('index');
    }
}
