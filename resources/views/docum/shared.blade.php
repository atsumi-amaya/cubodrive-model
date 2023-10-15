@extends('layouts.app')

@section('content')
@if (session('docSend')=='ok')
    <script> Swal.fire('Subido!', 'El documento subido correctamente', 'success') </script>
@endif
@if (session('docBin')=='ok')
    <script> Swal.fire('A la papelera!', 'El documento ha sido movido a la papelera de reciclaje', 'success') </script>
@endif
<script>
    $(document).ready( function () {
        $('#adminTable').DataTable({
            columns: [
                { orderable: false },
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
                                @if (substr(Request::fullUrl(), 35) != '')
                                    <a href="/folder-sharedback/{{ substr(Request::fullUrl(), 35) }}" style="border: 1px solid white" 
                                    class="col-8 btn btn-secondary float-end text-light"><i class="fa-solid fa-arrow-left-long"></i></a>
                                @endif
                                <!--
                                <div class="col-4 dropend p-0">
                                    <button id="btnGroupDrop1" type="button" class="m-0 btn btn-light multiselectbutton" data-bs-toggle="dropdown" 
                                            style="border: 1px solid white" disabled>
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1
                                        <li class = 'dropdown-submenu'>
                                            <button onchange="getCheck()" id="multiselectbutton" type="button" class="m-0 dropdown-toggle dropdown-item multiselectbutton" 
                                                data-bs-toggle="dropdown" style="border: 1px solid white">
                                                <small>mover archivos</small> 
                                            </button>
                                            <ul id="testjs" class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                    <?php

                                                    ?>                                                    
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
                                        </li>
                                        <li>
                                            <form method="post" action="/docum-bineds/" id="binSelect"
                                                class="eliminar-document-form">
                                                @csrf
                                                <button class="dropdown-item" type="submit">
                                                    <small>eliminar archivos</small> 
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>-->
                            </div>
                        </div>
                        <span class="titulo col-md-8">
                            ARCHIVOS COMPARTIDOS
                        </span>
                        <div class="col-md-2 pb-0">
                            <div class="row">
                                @if (substr(Request::fullUrl(), 35) != '')
                                    @foreach ($permissed as $perm)
                                        @if ($perm->document_id == $file->id)
                                            @if ($perm->editar == 1)
                                                <a href="/folder-create/{{ substr(Request::fullUrl(), 35) }}" style="border-rigth: 1px solid white" 
                                                class="btn btn-secondary float-end text-light col"><i class="fa-solid fa-folder"></i></a>
                                                <a href="/docum-upload/{{ substr(Request::fullUrl(), 35) }}" style="border-left: 1px solid white" 
                                                style="border" class="btn btn-success float-end text-light col"><i class="fa-solid fa-upload"></i></a>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </div> 
                        </div>
                    </div>
                    @if ( count($permissed) > 0 )
                    <div class="table-responsive row mt-2">
                        <table class="table mt-5 text-start" id="adminTable">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-start">
                                        <!--
                                        <ul class="list-group list-group-horizontal">
                                            <input onchange="checkingAll(this)" class="col" type="checkbox" id="checkAll">
                                            <div class="col m-0 ms-1">
                                                <i class="fa-solid fa-list-check"></i>
                                            </div>
                                        </ul>
                                        -->
                                    </th>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">FORMATO</th>
                                    <th scope="col">PROPIETARIO</th>
                                    <th scope="col"></th>                                
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($docs as $doc)
                                        @foreach ($permissed as $perm)
                                            @if ($doc->id == $perm->document_id)
                                            <tr>
                                                <td class="text-start">
                                                    <ul class="list-group list-group-horizontal">
                                                        <!--
                                                        <input onchange="checking(this)" class="col selectCheck"  type="checkbox" 
                                                            form="moveSelect" value={{ $doc->filecode }} id="{{ $doc->filecode }}" name="documM[]"
                                                            @if ($perm->eliminar != 1)
                                                                disabled
                                                            @endif>
                                                        <input class="me-1 selectCheck" type="checkbox" style="display:none" 
                                                            form="binSelect" value={{ $doc->filecode }} id="B{{ $doc->filecode }}" name="documB[]">    
                                                        -->
                                                        <div class="col m-0 ms-1">
                                                            @if ( $doc->formato == 'carpeta' )
                                                                <i style="color: darkgoldenrod" class="fa-solid fa-folder"></i>
                                                            @else
                                                                <i class="fa-solid fa-file"></i>
                                                            @endif
                                                        </div>
                                                    </ul>
                                                </td>
                                                <td>{{ $doc->nombre }}</td>
                                                <td>{{ strtoupper($doc->formato) }}</td>
                                                @foreach ($propietarios as $prop)
                                                    @if ($prop->id == $doc->propietario)
                                                        <td>{{ $prop->username }}</td>
                                                    @endif
                                                @endforeach 
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                        @if ( $doc->formato == 'carpeta' )
                                                            @if ($perm->ver == 1)
                                                                <a class="btn" href="/docum-shared/{{ $doc->filecode }}">
                                                                    <i style="color: gray" class="fa-solid fa-folder-open"></i>
                                                                </a>
                                                            @endif
                                                        @else 
                                                            @if ($perm->ver == 1)
                                                            <a class="btn" target="_blank" href="/docum-view/{{$doc->filecode}}">
                                                                <i style="color: gray" class="fa-solid fa-eye"></i>
                                                            </a>
                                                            @endif
                                                            @if ($perm->descargar == 1)
                                                            <form class="btn-group" style="display: inline" method="post" 
                                                                action="/docum-download/{{$doc->id}}" >
                                                                @csrf
                                                                <button class="btn" type="submit">
                                                                    <i style="color: gray" class="fa-solid fa-download"></i>
                                                                </button>
                                                            </form>
                                                            @endif
                                                        @endif
                                                        @if ($perm->mover == 1)
                                                        <div class="btn-group dropend" role="group">
                                                            <button id="btnGroupDrop1" type="button" class="btn" data-bs-toggle="dropdown" 
                                                                aria-expanded="false">
                                                                <i style="color: gray" class="fa-solid fa-up-down-left-right"></i>
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                                                @if (substr(Request::fullUrl(), 35) != '' && Auth::user()->rol != 2)
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
                                                                    @if ($folder->filecode != substr(Request::fullUrl(), 35))
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
                                                        @endif
                                                        @if ($perm->eliminar == 1)
                                                        <form method="post" action="/docum-bined/{{$doc->id}}" 
                                                            class="eliminar-document-form btn-group">
                                                            @csrf
                                                            <button class="btn" type="submit">
                                                                <i style="color: darkred" class="fas fa-solid fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        @endif
                                                    </div>
                                                </td> 
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endforeach 
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        <h5 style="text-align:center; margin-top:10px;">No hay archivos compartidos</h5>
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
        var list = $("input[name='documM[]']:checked").map(function () {
            return this.value;
        }).get();
        Swal.fire({
            title: 'Estas seguro?',
            icon: 'warning',
            text: list,
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
            $('.multiselectbutton').prop('disabled',false);
        } else {
            document.getElementById("B"+valu).checked=false;
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
        } else {
            $('.selectCheck').prop('checked', false);
        }
        if($('input[name="documM[]"]:checked').length > 0) {
            $('.multiselectbutton').prop('disabled',false);
        } else {
            $('.multiselectbutton').prop('disabled',true);
        }
    }

    function getCheck() {
        var list = $("input[name='documM[]']:checked").map(function () {
            return this.value;
        }).get();
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
        var folders = '<?php echo json_encode($folders) ?>';
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