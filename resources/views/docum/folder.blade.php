@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="titulo">CREAR CARPETA</span>
                <form class="mt-3" method="post" accept-charset="UTF-8" action="{{ substr(Request::fullUrl(), 36) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="test" name="name" id="name"
                            class="form-control form-control-lg">
                    </div>
                    <div class="mb-3">
                        <a class="btn btn-secondary btn-lg float-start text-light" href="{{ url()->previous() }}" >VOLVER</a>
                        <button type="submit" class="btn btn-success btn-lg float-end">CREAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>     
@endsection