@extends('layouts.app')

@section('content')
@if (session('userCreate')=='ok')
    <script>
          Swal.fire(
                'Creado!',
                'El usuario se creo correctamente.',
                'success'
            )
    </script>
@endif
@if (session('userDelete')=='ok')
    <script>
          Swal.fire(
                'Eliminado!',
                'El usuario se elimino correctamente.',
                'success'
            )
    </script>
@endif
<script>
    $(document).ready( function () {
        $('#dataTable').DataTable({
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
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 ">
            <div class="card w-auto">
                <div class="card-body text-center">
                    <div class="row mb-3">
                        <span class="titulo col-md-10">LISTA DE USUARIOS</span>
                        <a href="/user-create" class="btn btn-success float-end text-light col-md-2">NUEVO</a>
                    </div>
                    @if ( count($users) > 0 )
                    <div class="table-responsive row">
                        <table class="table mt-5" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">USUARIO</th>
                                    <th scope="col">EMAIL</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <a class="btn btn-warning" href="/user-pass/{{$user->id}}">
                                            <i style="color: black" class="fa-solid fa-key"></i>
                                        </a>
                                        <form style="display: inline" method="post" action="/user-delete/{{$user->id}}" 
                                            class="eliminar-user-form">
                                            @csrf
                                            <button class="btn btn-danger" type="submit">
                                                <i class="fas fa-solid fa-trash"></i>
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
                        <h5 style="text-align:center; margin-top:10px;">No hay usuarios registrados</h5>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $('.eliminar-user-form').submit( function (e) {
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
</script>
@endsection