@extends('layouts.app')

@section('content')
@if (session('passSend')=='ok')
    <script>
          Swal.fire(
                'Enviado!',
                'Revise su email para seguir con el proceso',
                'success'
            )
    </script>
@endif
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6 ">
            <div class="card w-auto">
                <div class="card-body text-center">
                    <div class="row">
                        <span class="titulo col-md">INICIO DE SESION </span>
                        <form class="mt-3" method="post" action="/login">
                            @csrf
                            <input type="hidden" name="prevUrl" value="{{ url()->previous() }}">
                            <div class="mb-3">
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg" value="{{ old('email') }}" placeholder="email">
                                @error('email')
                                    <small style="color: darkred">*{{$message}}</small>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <input type="password" name="password" id="password" 
                                    class="form-control form-control-lg" placeholder="contraseña">
                                @error('password')
                                    <small style="color: darkred">*{{$message}}</small>
                                @enderror
                            </div>
                            <div class="mb-2">
                                <a href="/user-recovery" style="font-size: 15px">¿Olvidaste tu contraseña?</a>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success btn-lg float-end">ACEPTAR</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection