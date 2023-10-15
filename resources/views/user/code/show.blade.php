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
        <div class="col-xl-10 ">
            <div class="card w-auto">
                <div class="card-body text-center">
                    <div class="row mb-3">
                        <span class="titulo col-md-10">CODIGOS DE USUARIO</span>
                        <a href="/user-code" class="btn btn-success float-end text-light col-md-2">NUEVO</a>
                    </div>
                    @if ( count($codes) > 0 )
                    <div class="table-responsive row">
                        <table class="table mt-5" id="dataTable">
                            <thead>
                                <tr>
                                    <th scope="col">DESCRIPCION</th>
                                    <th scope="col">DESTINATARIO</th>
                                    <th scope="col"><i class="fa-solid fa-check-to-slot"></i></th>
                                    <th scope="col">FECHA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($codes as $code)
                                <tr>
                                    <td>{{ $code->descripcion }}</td>
                                    <td>{{ $code->email }}</td>
                                    @if($code->estado > 0)
                                        <td class="text-start"><i style="color:red" class="fa-solid fa-circle-xmark"></i></td>
                                    @else
                                        <td class="text-start"><i style="color:green" class="fa-solid fa-circle-check"></i></td>
                                    @endif
                                    <td>{{ date('Y-m-d h:i',strtotime($code->created_at)) }}</td>
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
@endsection