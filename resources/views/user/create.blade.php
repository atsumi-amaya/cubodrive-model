@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="titulo">CREAR USUARIO</span>
                <form class="mt-3" method="post" action="/user-create">
                    @csrf
                    <div class="mb-3">
                        <input type="text" name="username" id="username" 
                            class="form-control form-control-lg" placeholder="username">
                            @error('username')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" id="email" 
                            class="form-control form-control-lg" placeholder="email">
                            @error('email')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" id="password" 
                            class="form-control form-control-lg" placeholder="contraseña">
                            @error('password')
                            <small style="color: darkred">*{{$message}}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <a class="btn btn-secondary btn-lg float-start text-light" href="{{ url()->previous() }}" >VOLVER</a>
                        <button type="submit" class="btn btn-success btn-lg float-end">ACEPTAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>     
@endsection