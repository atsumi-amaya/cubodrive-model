@extends('layouts.app')

@section('content')
@if (session('docGrave')=='ok')
    <script> Swal.fire('Eliminado!', 'El documento se elimmino permanentemente.', 'success') </script>
@endif
@if (session('docRestore')=='ok')
    <script> Swal.fire('Restaurado!', 'El documento se restauro correctamente.', 'success') </script>
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
                                <div class="col-4 dropend p-0">
                                    <button id="multiselectbutton" type="button" class="multiselectbutton m-0 btn btn-light" data-bs-toggle="dropdown" 
                                        style="border: 1px solid white" disabled>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li>
                                            <form method="post" action="/docum-restores" id="restoreSelect"
                                                class="">
                                                @csrf
                                                <input type="hidden" value="hola">
                                                <button class="dropdown-item" type="submit">
                                                    <small>recuperar archivos</small> 
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="post" action="/docum-graves" id="graveSelect"
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
                        <span class="titulo col-md-8">PAPELERA</span>
                        <div class="col-md-2">
                            <div class="row"></div>
                        </div>
                    </div>
                    @if ( count($docs) > 0 )
                    <div class="table-responsive row mt-2">
                        <table class="table mt-5 text-start" 
                        @if (Auth::user()->rol == 0)
                            id="adminTable"
                        @else
                            id="userTable"
                        @endif >
                            <thead>
                                <tr>
                                    <th scope="col" class="w-5">
                                        <ul class="list-group list-group-horizontal">
                                            <input onchange="checkingAll(this)" class="form-check-input" style="border: 1px solid grey;" type="checkbox" id="checkAll">
                                        </ul>
                                    </th>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">FORMATO</th>
                                    @if (Auth::user()->rol == 0)
                                        <th scope="col">PROPIETARIO</th>
                                    @endif
                                    <th scope="col">TAMAÑO</th>
                                    <th scope="col">BORRADO DESDE</th>
                                    <th scope="col"></th>                                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($docs as $doc)
                                <tr class="rowDoc" id="R{{ $doc->filecode }}">
                                    <td scope="col">
                                        <ul class="list-group list-group-horizontal">
                                            <input onchange="checking(this)" class="form-check-input selectCheck" style="border: 1px solid grey;" type="checkbox" form="restoreSelect" value="{{ $doc->filecode }}" id="{{ $doc->filecode }}" name="documR[]">
                                            <input class="selectCheck" type="checkbox" style="display:none" form="graveSelect" value="{{ $doc->filecode }}" id="B{{ $doc->filecode }}" name="documG[]">    
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
                                    <td>{{ date('Y-m-d H:i',strtotime($doc->last_binned)) }}</td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <form method="post" action="/docum-restore/{{$doc->id}}" 
                                                class="btn-group">
                                                @csrf
                                                <button class="btn" type="submit">
                                                    <i style="color: gray" class="fas fa-solid fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            <form method="post" action="/docum-grave/{{$doc->id}}" 
                                                class="eliminar-document-form btn-group">
                                                @csrf
                                                <button class="btn" type="submit">
                                                    <i style="color: darkred" class="fas fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td> 
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        <h5 style="text-align:center; margin-top:10px;">Papelera vacia</h5>
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
            text: 'se elimnara de forma permanente',
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
            $('.multiselectbutton').prop('disabled',false);
            $("#R"+valu).prop('style', 'background-color: #CFF3F5;');
        } else {
            document.getElementById("B"+valu).checked=false;
            $('.multiselectbutton').prop('disabled',true);
            $("#R"+valu).prop('style', 'background-color: white;');
        }
        if($('input[name="documG[]"]:checked').length > 0) {
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
        if($('input[name="documG[]"]:checked').length > 0) {
            $('.multiselectbutton').prop('disabled',false);
        } else {
            $('.multiselectbutton').prop('disabled',true);
        }
    }
</script>
@endsection