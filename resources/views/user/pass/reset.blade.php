@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body text-center">
                @if ($valid == 'yes')
                    <span class="titulo">CAMBIAR CONTRASEÑA</span>
                    <form class="mt-3" method="post" action="/user-passR/{{ $user->id }}">
                        @csrf
                        <div class="mb-3">
                            <label for=""><b>USUARIO: {{ $user->username }}({{ $user->email }})</b></label>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" id="password"
                                class="form-control form-control-lg" placeholder="nueva contraseña">
                            @error('password')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror    
                        </div>
                        <div class="mb-3">
                            <input type="password" name="confirm_password" id="confirm_password"
                                class="form-control form-control-lg" placeholder="repita contraseña">
                            @error('confirm_password')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-success btn-lg float-end">ACEPTAR</button>
                        </div>
                    </form>
                @else
                    <span class="titulo">EXPIRADO</span>
                    <label>Este enlace esta cadudado o fue remplazado, para realizar el cambio de contraseña vuelva a enviar el correo <a href="http://localhost:8000/user-recovery">aqui</a>.</label>
                @endif
            </div>
        </div>
    </div>
</div> 
@endsection