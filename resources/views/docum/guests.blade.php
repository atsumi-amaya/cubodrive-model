@extends('layouts.app')

@section('content')
@if (session('guestDelete')=='ok')
    <script> Swal.fire('expulsado!', 'El invitado ha sido removido', 'success') </script>
@endif
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
            columns: [                
                { orderable: true },
                { orderable: false },
                { orderable: false }
            ],
            language: {
                search: 'Buscar:',
                lengthMenu: 'Mostrando _MENU_ usuarios por pagina',
                zeroRecords: 'Nothing found - sorry',
                info: 'Mostrando pagina _PAGE_ de _PAGES_',
                infoEmpty: 'No records available',
                infoFiltered: '(filtrado de _MAX_ usuarios totales)',
            },
        });
    });
</script>
<div class="container-lg mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 ">
            <div class="card w-auto">
                <div class="card-body">
                    <div class="row mb-3 text-center">
                        <div class="col-md-2">
                            <a class="btn btn-secondary float-start text-light" href="/docum-guestsback/{{ substr(Request::fullUrl(), 35) }}" >VOLVER</a>
                        </div>
                        <span class="titulo col-md-8">
                            @if ($file->formato == 'carpeta')
                                INVITADOS DE LA CARPETA '{{ $file->nombre }}'
                            @else
                                INVITADOS DE '{{ $file->nombre }}'
                            @endif
                        </span>
                        <div class="col-md-2">
                            <a href="/docum-invite/{{ substr(Request::fullUrl(), 35) }}" class="btn btn-success float-end text-light">NUEVO</a>
                        </div>
                    </div>
                    @if ( count($permissions) > 0 )
                    <div class="table-responsive row">
                        <table class="table mt-5" id="dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col" colspan="2">USUARIO</th>
                                    <th scope="col">PERMISOS</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                <tr>
                                    @foreach ($users as $user)
                                        @if ($permission->invitado_id == $user->id)
                                            <td colspan="2">{{ $user->username }}</td>
                                        @endif
                                    @endforeach
                                    <td style="width: 300px">
                                        <form style="display: inline" method="post" action="/user-guestp/{{$permission->id}}" >
                                            @csrf
                                            <input type="hidden" value="ver" name="permiso">
                                            @if ( $permission->ver == 1 )
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                            @endif
                                        </form>
                                        @if ($file->formato == 'carpeta')
                                        <form style="display: inline" method="post" action="/user-guestp/{{$permission->id}}" >
                                            @csrf
                                            <input type="hidden" value="editar" name="permiso">
                                            <button 
                                                @if ( $permission->editar == 1 )
                                                    class="btn btn-success"
                                                @else
                                                    class="btn btn-danger"
                                                @endif
                                                type="submit">
                                                @if ( $file->formato != 'carpeta')
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                @else
                                                    <i class="fa-solid fa-upload"></i>
                                                @endif
                                            </button>
                                        </form>  
                                        @endif
                                        <!--
                                        <form style="display: inline" method="post" action="/user-guestp/{{$permission->id}}" >
                                            @csrf
                                            <input type="hidden" value="mover" name="permiso">
                                            @if ( $permission->mover == 1 )
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa-solid fa-up-down-left-right"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="fa-solid fa-up-down-left-right"></i>
                                                </button>
                                            @endif
                                        </form>
                                    -->
                                        @if ($file->formato != 'carpeta')
                                        <form style="display: inline" method="post" action="/user-guestp/{{$permission->id}}" >
                                            @csrf
                                            <input type="hidden" value="descargar" name="permiso">
                                            @if ( $permission->descargar == 1 )
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fa-solid fa-download"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="fa-solid fa-download"></i>
                                                </button>
                                            @endif
                                        </form>  
                                        @endif
                                        
                                        <form style="display: inline" method="post" action="/user-guestp/{{$permission->id}}" >
                                            @csrf
                                            <input type="hidden" value="eliminar" name="permiso">
                                            @if ( $permission->eliminar == 1 )
                                                <button class="btn btn-success" type="submit">
                                                    <i class="fas fa-solid fa-trash"></i>
                                                </button>
                                            @else
                                                <button class="btn btn-danger" type="submit">
                                                    <i class="fas fa-solid fa-trash"></i>
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                    <td style="width: 200px">
                                        <form style="display: inline" method="post" action="/docum-uninvite/{{$permission->id}}" 
                                            class="eliminar-guest-form">
                                            @csrf
                                            <button class="btn btn-danger" type="submit">
                                                <i class="fa-solid fa-person-running"></i> expulsar invitado
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="row">
                        <h5 style="text-align:center; margin-top:10px;">No hay usuarios invitados</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.eliminar-guest-form').submit( function (e) {
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
</script>
@endsection