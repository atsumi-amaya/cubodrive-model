<?php

namespace App\Http\Controllers;

use Hitrov\OCI\Signer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\document;
use App\Models\format;
use App\Models\invitado;
use App\Models\User;
use App\Models\Oracle;
use App\Http\Requests\invite;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\upload;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Mail;
use App\Mail\InvitationMail;


class documController extends Controller
{
    public function index(string $location = '', Request $request)
    {
        if(substr($request->fullUrl(), 0, 31) == 'https://cubodrive.com/index.php'){
            return Redirect::to('https://cubodrive.com/docum');
        }
        $filename = '';
        $propietarios = User::all();
        if ($location == ''){
            if (Auth::user()->rol == 0) {
                $docs = document::where('local_dir','/')->where('estado', 0)->get();
            } else {
                $docs = document::where('local_dir','/')->where('propietario',Auth::user()->id)->where('estado', 0)->get();
            }            
        } else {
            $file = document::where('filecode',$location)->first();
            if (Auth::user()->rol == 0) {
                $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('estado', 0)->where('propietario', $file->propietario)->get();
            } else {
                $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('propietario', $file->propietario)->where('estado', 0)->get();
            }
            $filename = $file->nombre;
        }
        if (Auth::user()->rol == 0) {
            $folders = document::where('estado', 0)->where('formato', 'carpeta')->get();
        } else {
            $folders = document::where('propietario',Auth::user()->id)->where('estado', 0)->where('formato', 'carpeta')->get();
        }     
        return view('docum.show', [ 'docs' => $docs , 'filename' => $filename , 'folders' => $folders, 'propietarios' => $propietarios ]);
    }
    
    //Seccion de compartidos
    public function shared(Request $request, string $location = '')
    {
        if(substr($request->fullUrl(), 0, 31) == 'https://cubodrive.com/index.php'){
            return Redirect::to('https://cubodrive.com/docum-shared');
        }
        $propietarios = User::all();
        $file = '';
        $permissed = invitado::where('invitado_id', Auth::user()->id)->get();
        if ($location == ''){
            $docs = document::where('local_dir','/')->where('estado', 0)->get();
        } else {
            $file = document::where('filecode',$location)->first();
            $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('estado', 0)->get();
        }
        if (Auth::user()->rol == 0) {
            $folders = document::where('estado', 0)->where('formato', 'carpeta')->get();
        } else {
            $folders = document::where('propietario',Auth::user()->id)->where('estado', 0)->where('formato', 'carpeta')->get();
        }     
        return view('docum.shared', [ 'docs' => $docs , 'file' => $file , 'folders' => $folders , 'permissed' => $permissed, 'propietarios' => $propietarios]);
    }

    public function guests(string $location = '')
    {  
        $file = document::where('filecode',$location)->first();
        $users = User::where('rol',2)->get();
        $permissions = invitado::where('document_id', $file->id)->get();
        return view('docum.guests', [ 'users' => $users, 'permissions' => $permissions, 'file' => $file ]);
    }

    public function guestsback(string $location = '')
    {  
        $file = document::where('filecode',$location)->first();
        if ($file->local_dir == '/') {
            return redirect("/docum".$file->local_dir);
        } else {
            $x = (strrpos(substr($file->local_dir,0,-1),'/'));
            $y = (strlen($file->local_dir)-$x-1)*-1;
            $locationTo = substr($file->local_dir,0,$y);
            $locationToName = substr(substr($file->local_dir,$y),0,-1);
            $folderTo = document::where('local_dir',$locationTo)->where('nombre',$locationToName)->first();
            return redirect("/docum/".$folderTo->filecode);
        }
    }
    
    //Invitar
    public function invite(string $location = '')
    {  
        $file = document::where('filecode',$location)->first();
        $permissions = invitado::where('document_id', $file->id)->get();
        $users = User::where('rol',2)->where('lider', Auth::user()->id)->get();
        return view('docum.invite', [ 'users' => $users, 'permissions' => $permissions, 'file' => $file ]);
    }

    public function inviteUser(invite $request)
    {
        
        $file = document::where('id',$request->get('file'))->first();
        
        if ($request->get('opt') == 'new') {
            $user = new User([
                'username' => strtoupper($request->get('username')),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'rol' => 2,
                'lider' => Auth::user()->id,
                'pre_auth' => '',
                'pre_auth_id' => '',
                'pre_auth_expire' => NULL,
                'plan' => 1,
                'limite' => 21474836480,
                'uso' => 0
            ]);
            $user->save();
            $user = User::where('rol',2)->where('lider', Auth::user()->id)->where('email', $request->get('email'))->first();
            $inv = $user->id;
            Mail::to($request->get('email'))->send(new InvitationMail(Auth::user()->username, Auth::user()->email, $request->get('password')));
        } else {
            $exist = invitado::where('invitado_id',$request->get('invitado'))->where('document_id',$request->get('file'))->get();
            if (count($exist) > 0)
            {
                throw ValidationException::withMessages([
                    'invitado' => 'Este usuario ya esta invitado'
                ]);
            }
            else
            {
                $inv = $request->get('invitado');
            }
            
        }
        if($file->formato == 'carpeta')
        {
            $folderPlace = $file->local_dir.$file->nombre.'/';
            
            $docums = document::all();
            foreach ($docums as $docum)
            {
                if (str_contains($docum->local_dir, $folderPlace) === true)
                {
                    $guest = new invitado([
                        'invitado_id' => $inv,
                        'document_id' => $docum->id,
                        'ver' => 1,
                        'mover' => 0,
                        'editar' => 0,
                        'eliminar' => 0,
                        'descargar' => 0
                    ]);
                    $guest->save();
                }
            }
        }
        $guest = new invitado([
            'invitado_id' => $inv,
            'document_id' => $request->get('file'),
            'ver' => 1,
            'mover' => 0,
            'editar' => 0,
            'eliminar' => 0,
            'descargar' => 0
        ]);
        $guest->save();
        //Mail::to($request->get('email'))->send(new InvitationMail(Auth::user()->username, Auth::user()->email, $request->get('password')));
        return redirect("/docum-guests/".$file->filecode);
    }

    public function uninviteUser(int $id)
    {
        $guest = invitado::find($id);
        $file = document::find($guest->document_id);
        if($file->formato == 'carpeta')
        {
            $folderPlace = $file->local_dir.$file->nombre.'/';
            $docums = document::all();
            foreach ($docums as $docum)
            {
                if (str_contains($docum->local_dir, $folderPlace) === true)
                {
                    $guestTo = invitado::where('document_id',$docum->id)->where('invitado_id',$guest->invitado_id)->first();
                    if($guestTo)
                    {
                        $guestTo->delete();
                    }
                }
            }
        }
        $guest->delete();
        return redirect()->back()->with('guestDelete', 'ok');
    }
    
    //Borrar archivos
    public function papelera(string $location = '')
    {
        $propietarios = User::all();
        $filename = '';
        if ($location == ''){
            if (Auth::user()->rol == 0) {
                $docs = document::where('local_dir','/')->where('estado', 1)->get();
            } else {
                $docs = document::where('local_dir','/')->where('propietario',Auth::user()->id)->where('estado', 1)->get();
            }            
        } else {
            $file = document::where('filecode',$location)->first();
            if (Auth::user()->rol == 0) {
                $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('estado', 1)->get();
            } else {
                $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('propietario',Auth::user()->id)->where('estado', 1)->get();
            }
            $filename = $file->nombre;     
        }
        return view('docum.bin', [ 'docs' => $docs , 'filename' => $filename, 'propietarios' => $propietarios ]);
    }

    public function graveyard(string $location = '')
    {
        $filename = '';
        if ($location == ''){
            if (Auth::user()->rol == 0) {
                $docs = document::where('local_dir','/')->where('estado', 2)->get();
            } else {
                $docs = document::where('local_dir','/')->where('propietario',Auth::user()->id)->where('estado', 2)->get();
            }            
        } else {
            $file = document::where('filecode',$location)->first();
            if (Auth::user()->rol == 0) {
                $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('estado', 2)->get();
            } else {
                $docs = document::where('local_dir',$file->local_dir.$file->nombre.'/')->where('propietario',Auth::user()->id)->where('estado', 2)->get();
            }
            $filename = $file->nombre;     
        }
        return view('docum.graveyard', [ 'docs' => $docs , 'filename' => $filename ]);
    }
    
    public function bin(int $id)
    {
        $doc = document::find($id);
        $docPlace = $doc->local_dir.$doc->nombre.'/';
        $doc->local_dir = '/';
        if ($doc->formato == 'carpeta') {
            $docums = document::where('estado', 0)->get();
            foreach ($docums as $docum) {
                if (str_contains($docum->local_dir, $docPlace) === true) {
                    $subPlace = substr($docum->local_dir, strlen($docPlace));
                    $docum->local_dir = '/'.$doc->nombre.'/'.$subPlace;
                    $docum->estado = 1;
                    $docum->last_binned = date("Y-m-d H:i:s");
                    $docum->save();
                }
            }
        } 
        $doc->estado = 1;
        $doc->last_binned = date("Y-m-d H:i:s");
        $doc->save();
        return redirect()->back()->with('docBin', 'ok');
    }

    public function grave(int $id)
    {
        $doc = document::find($id);
        $docPlace = $doc->local_dir.$doc->nombre.'/';
        $doc->local_dir = '/';
        if ($doc->formato == 'carpeta') {
            $docums = document::where('estado', 1)->get();
            foreach ($docums as $docum) {
                if (str_contains($docum->local_dir, $docPlace) === true) {
                    $subPlace = substr($docum->local_dir, strlen($docPlace));
                    $docum->local_dir = '/'.$doc->nombre.'/'.$subPlace;
                    $docum->estado = 2;
                    $docum->last_deleted = date("Y-m-d H:i:s");
                    $docum->save();
                }
            }
        } 
        $doc->estado = 2;
        $doc->last_deleted = date("Y-m-d H:i:s");
        $doc->save();
        return redirect()->back()->with('docGrave', 'ok');
    }

    public function destroy(int $id)
    {
        $doc = document::find($id);
        $user = User::find($doc->propietario);
        $name = $user->username;
        if($user->rol == 2){
            $lider = User::find($user->lider);
            $name = $lider->username;
        }
        if ($doc->formato == 'carpeta') {
            $folderPlace = $doc->local_dir.$doc->nombre.'/';
            $docums = document::all();
            foreach ($docums as $docum) {
                if (str_contains($docum->local_dir, $folderPlace) === true) {
                    $signer = new Signer();
                    $curl = curl_init();
            
                    $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.$name.'/o/'.$docum->filecode;
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
                    $docum->delete();
                    if($user->rol == 2){
                        $lider = User::find($user->lider);
                        $lider->uso -= $docum->size;
                    } else {
                        $user->uso -= $docum->size;
                    }
                }
            }
            $doc->delete();
            return redirect()->back()->with('docDelete', 'ok');
        } else {
            $signer = new Signer();
            $curl = curl_init();
    
            $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.$name.'/o/'.$doc->filecode;
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
            if($user->rol == 2){
                $lider = User::find($user->lider);
                $lider->uso -= $doc->size;
            } else {
                $user->uso -= $doc->size;
            }
            $doc->delete();
            return redirect()->back()->with('docDelete', 'ok');
        }
    }
        
    //Botones back
    public function backFolder(string $location = '')
    {
        $folder = document::where('filecode',$location)->first();
        if ($folder->local_dir == '/') {
            return redirect("/docum".$folder->local_dir);
        } else {
            $x = (strrpos(substr($folder->local_dir,0,-1),'/'));
            $y = (strlen($folder->local_dir)-$x-1)*-1;
            $locationTo = substr($folder->local_dir,0,$y);
            $locationToName = substr(substr($folder->local_dir,$y),0,-1);
            $folderTo = document::where('local_dir',$locationTo)->where('nombre',$locationToName)->first();
            return redirect("/docum/".$folderTo->filecode);
        }
    }

    public function backFolderShared(string $location = '')
    {
        $folder = document::where('filecode',$location)->first();
        if ($folder->local_dir == '/') {
            return redirect("/docum-shared".$folder->local_dir);
        } else {
            $x = (strrpos(substr($folder->local_dir,0,-1),'/'));
            $y = (strlen($folder->local_dir)-$x-1)*-1;
            $locationTo = substr($folder->local_dir,0,$y);
            $locationToName = substr(substr($folder->local_dir,$y),0,-1);
            $folderTo = document::where('local_dir',$locationTo)->where('nombre',$locationToName)->first();
            return redirect("/docum-shared/".$folderTo->filecode);
        }
    }

    //Ver archivos
    public function view(string $id)
    {   
        $doc = document::where('filecode',$id)->first();
        $prop = user::find($doc->propietario);
        //$format = format::where('nombre', $doc->formato)->first();
        $check = 'general';
        $format = $doc->formato;
        if ($format == 'doc' || $format == 'docx' || $format == 'rtf' || $format == 'xls' || $format == 'xlsx' || $format == 'ppt' || $format == 'pptx'){
            $check = 'office';
        }
        return view('docum.view', [ 'doc' => $doc , 'check' => $check, 'preauth' => $prop->pre_auth ]);
    }

    //Mover un archivos
    public function move(Request $request,int $id)
    {
        $doc = document::where('id',$id)->first();
        $docPlace = $doc->local_dir.$doc->nombre.'/';
        if ($doc->formato == 'carpeta') {
            if ($request->get('destino') == 'raiz') {
                $newPlace = '/';
            } else {
                $dest = document::where('filecode',$request->get('destino'))->first();
                $newPlace = $dest->local_dir.$dest->nombre.'/';
            }
            $doc->local_dir = $newPlace;
            $docums = document::all();
            foreach ($docums as $docum) {
                if (str_contains($docum->local_dir, $docPlace) === true) {
                    $subPlace = substr($docum->local_dir, strlen($docPlace));
                    $docum->local_dir = $newPlace.$doc->nombre.'/'.$subPlace;
                    $docum->save();
                }
            }
        } else {
            if ($request->get('destino') == 'raiz') {
                $doc->local_dir = '/';
            } else {
                $dest = document::where('filecode',$request->get('destino'))->first();
                $doc->local_dir = $dest->local_dir.$dest->nombre.'/';
            }
        }
        $doc->save();
        return redirect()->back();
    }

    //Descargar archivo
    public function download(int $id)
    {
        $doc = document::find($id);
        $prop = User::find($doc->propietario);
        $file = basename($doc->direccion);
        //$myFile = public_path($doc->nombre);
        $myFile = '/home/cubodrive/public_html/CDDownload/'.$doc->nombre;
        if (file_put_contents('CDDownload/'.$doc->nombre, file_get_contents($prop->pre_auth.$doc->filecode)))
        {
            return response()->download($myFile)->deleteFileAfterSend(true);
        }
        else
        {
            return "File downloading failed.";
        }
    }
    
    //Subida de archivos
    public function create()
    {
        return view('docum.upload');
    }
   
    public function store(upload $request, string $location = '')
    {
        set_time_limit(3600);
        if($request->file('file')) {
            $totalsize = 0;
            if (Auth::user()->rol == 2){
                $prop = User::where('id', Auth::user()->lider)->first();
                $limitsize = $prop->limite;
                $usosize = $prop->uso;
                $username = $prop->username;
            } else {
                $prop = User::where('id', Auth::user()->id)->first();
                $limitsize = $prop->limite;
                $usosize = $prop->uso;
                $username = $prop->username;
            }
            foreach ($request->file('file') as $file) {
                $totalsize += filesize($file);
            }
            $postsize = $usosize + $totalsize;
            if($limitsize > $postsize || $limitsize == 0) {
                foreach ($request->file('file') as $file) {
                    //$file = $request->file('file');
                    $folder = document::where('filecode', $location)->first();
                    if ($location != '') {
                        $locDir = $folder->local_dir.$folder->nombre.'/';
                    } else {
                        $locDir = '/';
                    }
                    $filecode = Str::random(33);         
                    //$format = format::where('nombre', $file->getClientOriginalExtension())->first();
                    $signer = new Signer();
                    $curl = curl_init();
            
                    $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.strtoupper($username).'/o/'.$filecode;
                    $method = 'PUT';
                    $body = file_get_contents($file);
        
                    $headers = $signer->getHeaders($url, $method, $body, $file->getMimeType());
                    //var_dump($headers);
        
                    $curlOptions = [
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => false,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 3600,
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
                    $doc = new document([
                        'nombre' => $file->getClientOriginalName(),
                        'filecode' => $filecode,
                        'formato' => $file->getClientOriginalExtension(),
                        'direccion' =>  $url,
                        'size' => filesize($file),
                        'local_dir' => $locDir,
                        'propietario' => $prop->id,
                        'estado' => 0
                    ]);
                    $doc->save();
                    $prop->uso +=  filesize($file);
                    $prop->save();
                    if($location != '') {
                        $teammates = invitado::where('document_id', $folder->id)->get();
                        $thisdoc = document::where('filecode',$filecode)->first();
                        foreach($teammates as $teammate) {
                            if ($teammate->invitado_id == Auth::user()->id) {
                                $mover = 1;
                                $editar = 1;
                                $eliminar = 1;
                                $descargar = 1;
                            } else {
                                $mover = 0;
                                $editar = 0;
                                $eliminar = 0;
                                $descargar = 0;
                            }
                            $guest = new invitado([
                                'invitado_id' => $teammate->invitado_id,
                                'document_id' => $thisdoc->id,
                                'ver' => 1,
                                'mover' => $mover,
                                'editar' => $editar,
                                'eliminar' => $eliminar,
                                'descargar' => $descargar
                            ]);
                            $guest->save();
                        };
                    }
                } 
            } else {
                return redirect()->route('docum')->with('overlimit', 'ok');
            }
            if($location == '') {
                return redirect("/docum");
            } else {
                if (Auth::user()->rol != 2) {
                    return redirect("/docum/".$folder->filecode);
                } else {
                    return redirect("/docum-shared/".$folder->filecode);
                }
            }
            /*
            if ($location == '/') {
                return redirect("/docum".$location);
            } else {
                $x = (strrpos(substr($location,0,-1),'/'));
                $y = (strlen($location)-$x-1)*-1;
                $locationTo = substr($location,0,$y);
                $locationToName = substr(substr($location,$y),0,-1);
                $permissed = invitado::where('document_id', $folder->id)->get();
                $thisdoc = document::where('filecode',$filecode)->first();
                foreach($permissed as $perm) {
                    if ($perm->invitado_id == Auth::user()->id) {
                        $mover = 1;
                        $editar = 1;
                        $eliminar = 1;
                        $descargar = 1;
                    } else {
                        $mover = 0;
                        $editar = 0;
                        $eliminar = 0;
                        $descargar = 0;
                    }
                    $guest = new invitado([
                        'invitado_id' => $perm->invitado_id,
                        'document_id' => $thisdoc->id,
                        'ver' => 1,
                        'mover' => $mover,
                        'editar' => $editar,
                        'eliminar' => $eliminar,
                        'descargar' => $descargar
                    ]);
                    $guest->save();
                };
                if (Auth::user()->rol != 2) {
                    return redirect("/docum/".$folder->filecode);
                } else {
                    return redirect("/docum-shared/".$folder->filecode);
                }
                
            }*/
            //return $res;
        }
    }
    
    //Creacion de folders
    public function createFolder()
    {
        return view('docum.folder');
    }

    public function storeFolder(Request $request, string $location = '')
    {
        $request->validate([
            'name' => 'required'
        ]);
        $folder = document::where('filecode', $location)->first();
        if ($location != '') {
            $locDir = $folder->local_dir.$folder->nombre.'/';
        } else {
            $locDir = '/';
        }
        $filecode = Str::random(33);
        if(Auth::user()->rol == 2) {
            $prop = $folder->propietario;
        } else {
            $prop = Auth::user()->id;
        }
        $doc = new document([
            'nombre' => $request->get('name'),
            'filecode' => $filecode,
            'formato' => 'carpeta',
            'size' => 0,
            'direccion' =>  '',
            'local_dir' => $locDir,
            'propietario' => $prop,
            'estado' => 0
        ]);
        $doc->save();
        if($location != '') {
            $teammates = invitado::where('document_id', $folder->id)->get();
            $thisdoc = document::where('filecode',$filecode)->first();
            foreach($teammates as $teammate) {
                if ($teammate->invitado_id == Auth::user()->id) {
                    $mover = 1;
                    $editar = 1;
                    $eliminar = 1;
                    $descargar = 1;
                } else {
                    $mover = 0;
                    $editar = 0;
                    $eliminar = 0;
                    $descargar = 0;
                }
                $guest = new invitado([
                    'invitado_id' => $teammate->invitado_id,
                    'document_id' => $thisdoc->id,
                    'ver' => 1,
                    'mover' => $mover,
                    'editar' => $editar,
                    'eliminar' => $eliminar,
                    'descargar' => $descargar
                ]);
                $guest->save();
            };
        }
        if ($location == '') {
            return redirect("/docum");
        } else {
            if (Auth::user()->rol != 2) {
                return redirect("/docum/".$folder->filecode);
            } else {
                return redirect("/docum-shared/".$folder->filecode);
            }
        }
    }
    
    //restaurar un archivo
    public function restore(int $id)
    {
        $doc = document::find($id);
        $docPlace = $doc->local_dir.$doc->nombre.'/';
        if ($doc->formato == 'carpeta') {
            if ($doc->estado == 2){
                $docums = document::where('estado', 2)->get();
            } else {
                $docums = document::where('estado', 1)->get();
            }
            foreach ($docums as $docum) {
                if (str_contains($docum->local_dir, $docPlace) === true) {
                    $subPlace = substr($docum->local_dir, strlen($docPlace));
                    $docum->local_dir = '/'.$doc->nombre.'/'.$subPlace;
                    $docum->estado = 0;
                    $docum->save();
                }
            }
        }
        $doc->estado = 0;
        $doc->save();
        return redirect()->back()->with('docRestore', 'ok');
    }

    public function moves(Request $request)
    {
        //$docum = $request->get('docum');
        $documents = $request->get('docum');
        /*
        $doc = document::where('id',$id)->first();
        $docPlace = $doc->local_dir.$doc->nombre.'/';
        if ($doc->formato == 'carpeta') {
            if ($request->get('destino') == 'raiz') {
                $newPlace = '/';
            } else {
                $dest = document::where('filecode',$request->get('destino'))->first();
                $newPlace = $dest->local_dir.$dest->nombre.'/';
            }
            $doc->local_dir = $newPlace;
            $docums = document::all();
            foreach ($docums as $docum) {
                if (str_contains($docum->local_dir, $docPlace) === true) {
                    $subPlace = substr($docum->local_dir, strlen($docPlace));
                    $docum->local_dir = $newPlace.$doc->nombre.'/'.$subPlace;
                    $docum->save();
                }
            }
        } else {
            if ($request->get('destino') == 'raiz') {
                $doc->local_dir = '/';
            } else {
                $dest = document::where('filecode',$request->get('destino'))->first();
                $doc->local_dir = $dest->local_dir.$dest->nombre.'/';
            }
        }
        $doc->save();
        return redirect()->back()->with('docMove', 'ok');*/
        return $documents;
    }

    public function bins(Request $request)
    {
        $documents = $request->get('documB');

        foreach ($documents as $document) {
            $doc = document::where('filecode', $document)->first();
            
            $docPlace = $doc->local_dir.$doc->nombre.'/';
            
            $doc->local_dir = '/';
            if ($doc->formato == 'carpeta') {
                $docums = document::where('estado', 0)->get();
                foreach ($docums as $docum) {
                    if (str_contains($docum->local_dir, $docPlace) === true) {
                        $subPlace = substr($docum->local_dir, strlen($docPlace));
                        $docum->local_dir = '/'.$doc->nombre.'/'.$subPlace;
                        $docum->estado = 1;
                        $docum->last_binned = date("Y-m-d H:i:s");
                        $docum->save();
                    }
                }
            } 
            $doc->estado = 1;
            $doc->last_binned = date("Y-m-d H:i:s");
            $doc->save();
        }
        return redirect()->back()->with('docBin', 'ok');
        //return $ehe;
    }

    public function graves(Request $request)
    {
        $documents = $request->get('documG');
        foreach ($documents as $document) {
            $doc = document::where('filecode', $document)->first();
            $docPlace = $doc->local_dir.$doc->nombre.'/';
            $doc->local_dir = '/';
            if ($doc->formato == 'carpeta') {
                $docums = document::where('estado', 1)->get();
                foreach ($docums as $docum) {
                    if (str_contains($docum->local_dir, $docPlace) === true) {
                        $subPlace = substr($docum->local_dir, strlen($docPlace));
                        $docum->local_dir = '/'.$doc->nombre.'/'.$subPlace;
                        $docum->estado = 2;
                        $docum->last_deleted = date("Y-m-d H:i:s");
                        $docum->save();
                    }
                }
            } 
            $doc->estado = 2;
            $doc->last_deleted = date("Y-m-d H:i:s");
            $doc->save();
        }
        return redirect()->back()->with('docGrave', 'ok');
    }

    public function destroys(Request $request)
    {
        $documents = $request->get('documD');
        foreach ($documents as $document) {
            $doc = document::where('filecode', $document)->first();
            if ($doc->formato == 'carpeta') {
                $folderPlace = $doc->local_dir.$doc->nombre.'/';
                $docums = document::all();
                foreach ($docums as $docum) {
                    $prop = user::find($docum->propietario);
                    if (str_contains($docum->local_dir, $folderPlace) === true) {
                        $signer = new Signer();
                        $curl = curl_init();
                
                        $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.$prop->username.'/o/'.$docum->filecode;
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
                        $docum->delete();
                    }
                }
            } else {
                $signer = new Signer();
                $curl = curl_init();
        
                $url = 'https://objectstorage.us-ashburn-1.oraclecloud.com/n/idnpcnfewtg7/b/'.Auth::user()->username.'/o/'.$document;
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
        return redirect()->back()->with('docDelete', 'ok');
    }

    public function restores(Request $request)
    {
        $documents = $request->get('documR');
        
        foreach ($documents as $document) {
            $doc = document::where('filecode', $document)->first();
            $docPlace = $doc->local_dir.$doc->nombre.'/';
            if ($doc->formato == 'carpeta') {
                if ($doc->estado == 2){
                    $docums = document::where('estado', 2)->get();
                } else {
                    $docums = document::where('estado', 1)->get();
                }
                foreach ($docums as $docum) {
                    if (str_contains($docum->local_dir, $docPlace) === true) {
                        $subPlace = substr($docum->local_dir, strlen($docPlace));
                        $docum->local_dir = '/'.$doc->nombre.'/'.$subPlace;
                        $docum->estado = 0;
                        $docum->save();
                    }
                }
            }
            $doc->estado = 0;
            $doc->save();
        }
        return redirect()->back()->with('docRestore', 'ok');
        //return $documents;
    }
}
