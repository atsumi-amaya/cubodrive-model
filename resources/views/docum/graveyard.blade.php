@extends('layouts.app')

@section('content')
@if (session('docDelete')=='ok')
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
                                <div class="col-4 dropend p-0">
                                    <button id="btnGroupDrop1" type="button" class="multiselectbutton m-0 btn btn-light" data-bs-toggle="dropdown" 
                                        style="border: 1px solid white" disabled>
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li>
                                            <form method="post" action="/docum-restores" id="restoreSelect"
                                                class="recuperar-document-form">
                                                @csrf
                                                <input type="hidden" value="hola">
                                                <button class="dropdown-item" type="submit">
                                                    <small>recuperar archivos</small> 
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="post" action="/docum-deletes" id="deleteSelect"
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
                        <span class="titulo col-md-8">ARCHIVOS ELIMINADOS</span>
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
                                    <th scope="col" class="text-start">
                                        <ul class="list-group list-group-horizontal">
                                            <input onchange="checkingAll(this)" class="form-check-input"  type="checkbox" id="checkAll">
                                            <div class="col m-0 ms-1">
                                                <i class="fa-solid fa-list-check"></i>
                                            </div>
                                        </ul>
                                    </th>
                                    <th scope="col">NOMBRE</th>
                                    <th scope="col">FORMATO</th>
                                    @if (Auth::user()->rol == 0)
                                        <th scope="col">PROPIETARIO</th>
                                    @endif
                                    <th scope="col">ELIMINADO DESDE</th>
                                    <th scope="col"></th>                                
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($docs as $doc)
                                <tr>
                                    <td scope="col" class="text-start">
                                        <ul class="list-group list-group-horizontal">
                                            <input onchange="checking(this)" class="form-check-input selectCheck"  type="checkbox" form="restoreSelect" value="{{ $doc->filecode }}" id="{{ $doc->filecode }}" name="documR[]">
                                            <input class="me-1 selectCheck" type="checkbox" style="display:none" form="deleteSelect" value="{{ $doc->filecode }}" id="B{{ $doc->filecode }}" name="documD[]">    
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
                                    @if (Auth::user()->rol == 0)
                                        <td>{{ $doc->propietario }}</td>
                                    @endif
                                    <td>{{ date('Y-m-d H:i',strtotime($doc->last_deleted)) }}</td>
                                    <td class="text-end">
                                        <div class="btn-group" role="group">
                                            <form method="post" action="/docum-restore/{{$doc->id}}" 
                                                class="btn-group">
                                                @csrf
                                                <button class="btn" type="submit">
                                                    <i style="color: gray" class="fas fa-solid fa-rotate-left"></i>
                                                </button>
                                            </form>
                                            <form method="post" action="/docum-delete/{{$doc->id}}" 
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
        } else {
            document.getElementById("B"+valu).checked=false;
            $('.multiselectbutton').prop('disabled',true);
        }
        if($('input[name="documR[]"]:checked').length > 0) {
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
        if($('input[name="documR[]"]:checked').length > 0) {
            $('.multiselectbutton').prop('disabled',false);
        } else {
            $('.multiselectbutton').prop('disabled',true);
        }
    }
</script>
@endsection