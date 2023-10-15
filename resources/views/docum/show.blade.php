@extends('layouts.app')

@section('content')
@if (session('docSend')=='ok')
    <script> Swal.fire('Subido!', 'El documento subido correctamente', 'success') </script>
@endif
@if (session('docBin')=='ok')
    <script> Swal.fire('A la papelera!', 'El documento ha sido movido a la papelera de reciclaje', 'success') </script>
@endif
@if (session('overlimit')=='ok')
    <script> Swal.fire('Sin espacio!', 'Espacio insuficiente para el archivo', 'error') </script>
@endif
<script>
    $(document).ready( function () {
        $('#adminTable').DataTable({
            columns: [
                { orderable: false },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: false }
            ],
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrando _MENU_ archivos por pagina',
                zeroRecords: 'Nothing found - sorry',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No records available',
                infoFiltered: '(filtrado de _MAX_ archivos totales)',
            },
        });
        $('#userTable').DataTable({
            columns: [
                { orderable: false },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: true },
                { orderable: false }
            ],
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrando _MENU_ archivos por pagina',
                zeroRecords: 'Nothing found - sorry',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No records available',
                infoFiltered: '(filtrado de _MAX_ archivos totales)',
            },
        });
    });
</script>
<div class="container-fluid mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 ">
            <div class="card w-auto">
                <div class="card-body text-center">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="row">
                                @if (substr(Request::fullUrl(), 28) != '')
                                    <a href="/folder-back/{{ substr(Request::fullUrl(), 28) }}" style="border: 1px solid white" 
                                    class="col-8 btn btn-secondary float-end text-light"><i class="fa-solid fa-arrow-left-long"></i></a>
                                @endif
                                <div class="col-4 dropend p-0">
                                    <button id="btnGroupDrop1" type="button" class="m-0 btn btn-light multiselectbutton" data-bs-toggle="dropdown" 
                                        style="border: 1px solid white" disabled>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <!--
                                        <li class = 'dropdown-submenu'>
                                            <button onchange="getCheck()" id="multiselectbutton" type="button" class="m-0 dropdown-toggle dropdown-item multiselectbutton" 
                                                data-bs-toggle="dropdown" style="border: 1px solid white">
                                                <small>mover archivos</small> 
                                            </button>
                                            <ul id="testjs" class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                
                                                @if (substr(Request::fullUrl(), 28) != '')
                                                    <form method="post" action="/docum-moves" 
                                                        class="move-document-form">
                                                        @csrf
                                                        <input type="hidden" name="destino" value="raiz">
                                                        <button class="dropdown-item" type="submit">
                                                            Raiz <small class="fw-light">(/)</small>
                                                        </button>
                                                    </form>
                                                @endif
                                                @foreach ($folders as $folder)
                                                    @if ($folder->filecode != substr(Request::fullUrl(), 28))
                                                    <form method="post" action="/docum-moves" 
                                                        class="move-document-form">
                                                        @csrf
                                                        <input type="hidden" name="destino" value="{{ $folder->filecode }}">
                                                        <button class="dropdown-item" type="submit" >
                                                            {{ $folder->nombre }} <small class="fw-light">({{ $folder->local_dir }}{{ $folder->nombre }})</small> 
                                                        </button>
                                                    </form>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>-->
                                        <li>
                                            <form method="POST" action="/docum-bineds" id="binSelect"
                                                class="eliminar-document-form">
                                                @csrf
                                                <input type="hidden" value="hola">
                                                <button class="dropdown-item" type="submit">
                                                    <small>eliminar archivos</small> 
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <span class="titulo col-md-8">
                            @if (substr(Request::fullUrl(), 28) != '')
                                MI UNIDAD ({{$filename}})
                            @else
                                MI UNIDAD (RAIZ)
                            @endif
                        </span>
                        <div class="col-md-2 pb-0">
                            <div class="row">
                                <a href="/folder-create/{{ substr(Request::fullUrl(), 28) }}" style="border-rigth: 1px solid white" 
                                class="btn btn-secondary float-end text-light col"><i class="fa-solid fa-folder"></i></a>
                                <a href="/docum-upload/{{ substr(Request::fullUrl(), 28) }}" style="border-left: 1px solid white" 
                                style="border" class="btn btn-success float-end text-light col"><i class="fa-solid fa-upload"></i></a>    
                            </div> 
                        </div>
                    </div>
                    @if ( count($docs) > 0 )
                    <div class="table-responsive row mt-2">
                        <table class="table mt-5 text-start" 
                        @if (Auth::user()->rol == 0)
                            id="adminTable"
                        @else
                            id="userTable"
                        @endif>
                            <thead>
                                <tr>
                                    <th scope="col" class="w-5">
                                        <ul class="list-group list-group-horizontal">
                                            <input onchange="checkingAll(this)" class="form-check-input" style="border: 1px solid grey;"  type="checkbox" id="checkAll">
                                        </ul>
                                    </th>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">FORMATO</th>
                                    @if (Auth::user()->rol == 0)
                                        <th scope="col">PROPIETARIO</th>
                                    @endif
                                    <th scope="col">TAMA&NtildeO</th>
                                    <th scope="col">FECHA DE SUBIDA</th>
                                    <th scope="col" class="w-5"></th>                                
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($docs as $doc)
                                    <tr class="rowDoc" id="R{{ $doc->filecode }}">
                                        <td scope="col">
                                            <ul class="list-group list-group-horizontal">
                                                <input onchange="checking(this)" class="form-check-input selectCheck"  type="checkbox" style="border: 1px solid grey;"
                                                    form="moveSelect" value="{{ $doc->filecode }}" id="{{ $doc->filecode }}" name="documM[]">
                                                <input class="me-1 selectCheck" type="checkbox" style="display:none" 
                                                    form="binSelect" value="{{ $doc->filecode }}" id="B{{ $doc->filecode }}" name="documB[]">    
                                            </ul>
                                        </td>
                                        <td>
                                            <div class="col m-0 ms-1">
                                                @if ( $doc->formato == 'carpeta' )
                                                    <i style="color: darkgoldenrod" class="fa-solid fa-folder"></i>
                                                @else
                                                    <i class="fa-solid fa-file"></i>
                                                @endif
                                                {{ $doc->nombre }}
                                            </div>    
                                        </td>
                                        <td>{{ strtoupper($doc->formato) }}</td>
                                        @if (Auth::user()->rol == 0)
                                            @foreach ($propietarios as $prop)
                                                @if ($prop->id == $doc->propietario)
                                                    <td>{{ $prop->username }}</td>
                                                @endif
                                            @endforeach 
                                        @endif
                                        @if($doc->formato != 'carpeta')
                                            @if ($doc->size >= 1024)
                                                @if ($doc->size >= 1048576)
                                                    @if ($doc->size >= 1073741824)
                                                        @if ($doc->size >= 1099511627776)
                                                            <td>{{ round($doc->size/1099511627776, 2) }} TB</td>
                                                        @else
                                                            <td>{{ round($doc->size/1073741824, 2) }} GB</td>
                                                        @endif
                                                    @else
                                                        <td>{{ round($doc->size/1048576, 2) }} MB</td>
                                                    @endif
                                                @else
                                                    <td>{{ round($doc->size/1024, 2) }} KB</td>
                                                @endif
                                            @else
                                                <td>{{ $doc->size }} B</td>
                                            @endif
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ date('Y-m-d H:i',strtotime($doc->created_at)) }}</td>
                                        <td class="text-end">
                                            <div class="dropstart text-end">
                                                <button class="btn btn-light" style="background-color: #f8f9fa;" type="button" id="fileOptions" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="fileOptions">
                                                    @if ( $doc->formato == 'carpeta' )
                                                    <li>
                                                        <a class="dropdown-item text-dark" href="/docum/{{ $doc->filecode }}">
                                                            Abrir
                                                        </a>
                                                    </li>
                                                    @else 
                                                        @if ( $doc->formato != 'zip' && $doc->formato != 'rar')
                                                        <li>
                                                            <a class="dropdown-item text-dark" target="_blank" href="/docum-view/{{$doc->filecode}}">
                                                                Ver
                                                            </a>
                                                        </li>
                                                        @endif
                                                        <li>
                                                            <form method="post" action="/docum-download/{{$doc->id}}" >
                                                                @csrf
                                                                <button class="dropdown-item text-dark" type="submit">
                                                                    Descargar
                                                                </button>
                                                            </form>
                                                        </li>
                                                    @endif
                                                    @if (substr(Request::fullUrl(), 28) != '' || $doc->formato == 'carpeta')
                                                    <li>
                                                        <a class="dropdown-item text-dark" href="/docum-guests/{{ $doc->filecode }}">
                                                            Invitar
                                                        </a>
                                                    </li>
                                                    @endif
                                                    <li class="dropstart">
                                                        <a class="dropdown-item text-dark" role="button" id="moveTo" data-bs-toggle="dropdown" aria-expanded="false">
                                                            Mover a  . . .
                                                        </a>
                                                        <ul class="dropdown-menu" aria-labelledby="moveTo">
                                                            @if (substr(Request::fullUrl(), 28) != '')
                                                                <form method="post" action="/docum-move/{{$doc->id}}" 
                                                                    class="move-document-form">
                                                                    @csrf
                                                                    <input type="hidden" name="destino" value="raiz">
                                                                    <button class="dropdown-item text-dark" type="submit">
                                                                        Raiz <small class="fw-light">(/)</small>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            @foreach ($folders as $folder)
                                                                @if ($folder->filecode != substr(Request::fullUrl(), 28))
                                                                    @if ($doc->filecode != $folder->filecode) 
                                                                        @if (str_contains($doc->local_dir, $folder->local_dir) === true)
                                                                            <form method="post" action="/docum-move/{{$doc->id}}" 
                                                                                class="move-document-form">
                                                                                @csrf
                                                                                <input type="hidden" name="destino" value="{{ $folder->filecode }}">
                                                                                <button class="dropdown-item text-dark" type="submit" >
                                                                                    {{ $folder->nombre }} <small class="fw-light">({{ $folder->local_dir }}{{ $folder->nombre }})</small> 
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @endif   
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form method="post" action="/docum-bined/{{$doc->id}}" 
                                                            class="eliminar-document-form">
                                                            @csrf
                                                            <button class="dropdown-item text-danger" type="submit">
                                                                Eliminar
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!--
                                            <div class="btn-group" role="group">
                                                @if (substr(Request::fullUrl(), 28) != '' || $doc->formato == 'carpeta')
                                                <a class="btn" href="/docum-guests/{{ $doc->filecode }}">
                                                    <i style="color: gray" class="fa-solid fa-users"></i>
                                                </a>
                                                @endif
                                                @if ( $doc->formato == 'carpeta' )
                                                    <a class="btn" href="/docum/{{ $doc->filecode }}">
                                                        <i style="color: gray" class="fa-solid fa-folder-open"></i>
                                                    </a>
                                                @else 
                                                    @if ( $doc->formato != 'zip' && $doc->formato != 'rar')
                                                    <a class="btn" target="_blank" href="/docum-view/{{$doc->filecode}}">
                                                        <i style="color: gray" class="fa-solid fa-eye"></i>
                                                    </a>
                                                    @endif
                                                    <form class="btn-group" style="display: inline" method="post" 
                                                        action="/docum-download/{{$doc->id}}" >
                                                        @csrf
                                                        <button class="btn" type="submit">
                                                            <i style="color: gray" class="fa-solid fa-download"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <div class="btn-group dropend" role="group">
                                                    <button id="btnGroupDrop1" type="button" class="btn" data-bs-toggle="dropdown" 
                                                        aria-expanded="false">
                                                        <i style="color: gray" class="fa-solid fa-up-down-left-right"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                        @if (substr(Request::fullUrl(), 28) != '')
                                                            <form method="post" action="/docum-move/{{$doc->id}}" 
                                                                class="move-document-form">
                                                                @csrf
                                                                <input type="hidden" name="destino" value="raiz">
                                                                <button class="dropdown-item" type="submit">
                                                                    Raiz <small class="fw-light">(/)</small>
                                                                </button>
                                                            </form>
                                                        @endif
                                                        @foreach ($folders as $folder)
                                                            @if ($folder->filecode != substr(Request::fullUrl(), 28))
                                                                @if ($doc->filecode != $folder->filecode) 
                                                                    @if (str_contains($doc->local_dir, $folder->local_dir) === true)
                                                                        <form method="post" action="/docum-move/{{$doc->id}}" 
                                                                            class="move-document-form">
                                                                            @csrf
                                                                            <input type="hidden" name="destino" value="{{ $folder->filecode }}">
                                                                            <button class="dropdown-item" type="submit" >
                                                                                {{ $folder->nombre }} <small class="fw-light">({{ $folder->local_dir }}{{ $folder->nombre }})</small> 
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endif   
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <form method="post" action="/docum-bined/{{$doc->id}}" 
                                                    class="eliminar-document-form btn-group">
                                                    @csrf
                                                    <button class="btn" type="submit">
                                                        <i style="color: darkred" class="fas fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            -->
                                        </td> 
                                    </tr>
                                    @endforeach 
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        <h5 style="text-align:center; margin-top:10px;">No hay archivos subidos</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.eliminar-document-form').submit( function (e) {
        e.preventDefault();

        Swal.fire({
            title: 'Estas seguro?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ELIMINAR'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        })
    })
    function checking(chec) {
        var valu = chec.value;
        if (chec.checked == true) {
            document.getElementById("B"+valu).checked=true;
            //$("#"+valu).prop('style', 'background-color: #CFF3F5;');
            $("#R"+valu).prop('style', 'background-color: #CFF3F5;');
            $('.multiselectbutton').prop('disabled',false);
        } else {
            document.getElementById("B"+valu).checked=false;
            //$("#"+valu).prop('style', 'background-color: white;');
            $("#R"+valu).prop('style', 'background-color: white;');
            $('.multiselectbutton').prop('disabled',true);
        }
        if($('input[name="documM[]"]:checked').length > 0) {
            $('.multiselectbutton').prop('disabled',false);
        } else {
            $('.multiselectbutton').prop('disabled',true);
        }
    }

    function checkingAll(chec)  {
        if (chec.checked == true) {
            $('.selectCheck').prop('checked', true);
            $('.rowDoc').prop('style', 'background-color: #CFF3F5;');
        } else {
            $('.selectCheck').prop('checked', false);
            $('.rowDoc').prop('style', 'background-color: white;');
        }
        if($('input[name="documM[]"]:checked').length > 0) {
            $('.multiselectbutton').prop('disabled',false);
        } else {
            $('.multiselectbutton').prop('disabled',true);
        }
    }
/*
	//Metodo Generación Formulario
	function GeneraForm(){
		////Crear el objeto formulario
		let formulario=document.createElement("form");
		////Crear el objeto caja de texto Nombres
		let direccion=document.createElement("input");
 
		////Crear el objeto boton
		let boton=document.createElement("input");
 
		    ////Asignar atributos al objeto formulario
        	formulario.setAttribute('method', "post");//Asignar el atributo method
        	formulario.setAttribute('action', "/docum-bineds");//Asignar el atributo action
        	formulario.setAttribute('style', "width:300px;margin: 0px auto");//Asignar el atributo style
 
        	////Asignar atributos al objeto caja de texto de Nombres
            direccion.setAttribute('name', "hola");
        	direccion.setAttribute('type', "hidden");//Asignar el atributo type
        	direccion.setAttribute('value', 1);//Asignar el atributo style

		    ////Asignar atributos al objeto boton
		    boton.setAttribute('type', "submit");//Asignar el atributo type	
        	boton.setAttribute('value', "Enviar");//Asignar el atributo value
        	boton.setAttribute('style', "width:100px;margin: 10px 0px;padding: 10px;background:#F05133;color:#fff;border:solid 1px #000;");//Asignar el atributo style
 
        	formulario.appendChild(direccion);//Agregar el objeto caja de texto Nombres al objeto formulario
        	formulario.appendChild(boton);//Agregar el objeto boton al objeto formulario
        	document.getElementById('ContentFormulario').appendChild(formulario);//Agregar el formulario a la etiquete con el ID			
	}
*//*
    function generateList(){
        var element = document.getElementById("testjs");
        while (element.firstChild) {
        element.removeChild(element.firstChild);
        }
        var list = $("input[name='documM[]']:checked").map(function () {
            return this.value;
        }).get();
        var folders = echo json_encode($folders) ;
        var f = folders.split("},{");

        var fold = 0; 
        while (fold < f.length) {
            for (var i = 0; i < list.length; i++) {
                if (f[fold].includes(list[i])) {
                    f.splice(fold, 1);
                }
            }
            fold += 1;
        }
        while (fc < f.length) {
            var f_code = f[f_check[fc]].indexOf('"filecode":"');
            var formB = f[f_check[fc]].indexOf('"formato":');
            var formE = f[f_check[fc]].indexOf('","direccion":');
            var form = f[f_check[fc]].substring(formB+11,formE);
            let li_menu=document.createElement("li");
            let label_menu=document.createElement("LABEL");
            label_menu.innerHTML = f[f_check[fc]]+form;
            li_menu.appendChild(label_menu);
            document.getElementById("testjs").appendChild(li_menu);
            fc += 1;
        }
        let li_menu = document.createElement("li");
        let label_menu = document.createElement("LABEL");
        label_menu.innerHTML = f;
        li_menu.appendChild(label_menu);
        document.getElementById("testjs").appendChild(li_menu);
        /*
        while (fc < f_check.length) {
            var f_code = f[f_check[fc]].indexOf('"filecode":"');
            var formB = f[f_check[fc]].indexOf('"formato":');
            var formE = f[f_check[fc]].indexOf('","direccion":');
            var form = f[f_check[fc]].substring(formB+11,formE);
            let li_menu=document.createElement("li");
            let label_menu=document.createElement("LABEL");
            label_menu.innerHTML = f[f_check[fc]]+form;
            li_menu.appendChild(label_menu);
            document.getElementById("testjs").appendChild(li_menu);
            fc += 1;
        }*/
        /*
        var fold = 0; 
        while (fold < f.length) {
            let li_menu=document.createElement("li");
            let label_menu=document.createElement("LABEL");
            for (var i = 0; i < list.length; i++) {
                if (f[fold].includes(list[i])) {

                } else {
                    var f_code = f[fold].indexOf(i);
                    var formB = f[fold].indexOf('"formato":');
                    var formE = f[fold].indexOf('","direccion":');
                    var form = f[fold].substring(formB+11,formE);
                    if (form == "carpeta") {
                        let li_menu=document.createElement("li");
                        let label_menu=document.createElement("LABEL");
                        label_menu.innerHTML = f[fold]+form;
                        li_menu.appendChild(label_menu);
                        document.getElementById("testjs").appendChild(li_menu);
                    }
                }
            }
            var index = f[fold].indexOf()
            label_menu.innerHTML = f[fold];
            li_menu.appendChild(label_menu);
            document.getElementById("testjs").appendChild(li_menu);
            fold += 1;
        }
        
        let li_menu=document.createElement("li");
        let label_menu=document.createElement("LABEL");
        label_menu.innerHTML = folders;
        li_menu.appendChild(label_menu);
        document.getElementById("testjs").appendChild(li_menu);
        //formulario.innerHTML = folders;
        //formulario.innerHTML = list[0];
        //document.getElementById('ContentFormulario').appendChild(formulario);
    }*/
    
</script>
@endsection