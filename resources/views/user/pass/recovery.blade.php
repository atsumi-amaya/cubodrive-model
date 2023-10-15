@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card w-auto">
                <div class="card-body text-center">
                    <div class="row">
                        <span class="titulo col-md">ENVIAR CORREO DE RECUPERACION</span>
                        <form class="mt-3" method="post" action="/user-recovery">
                            @csrf
                            <div class="mb-3">
                                <input type="email" name="email" id="email"
                                    class="form-control form-control-lg" placeholder="email">
                                @error('email')
                                    <small style="color: darkred">*{{$message}}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-success btn-lg float-end">ENVIAR</button>
                                <a href="/login" class="btn btn-secondary btn-lg float-start text-light">CANCELAR</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection