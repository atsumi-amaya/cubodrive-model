@extends('layouts.app')
@section('content')
<div class="container mt-5">
    <div class="col-lg-6 offset-lg-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="titulo">SUBIR ARCHIVO(S)</span><!--
                <div class="m-5">
                    <form action="/{{ substr(Request::fullUrl(), 35) }}"  method="post" class="dropzone p-2" id="updatefiles" style="border: 1px solid darkgray;  border-radius:22px;" enctype="multipart/form-data">
                    @csrf
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-2">
                            <a href="{{url()->previous()}}" class="btn btn-secondary text-light">Cancelar</a>
                        </div>
                        <div class="col-8">
                        </div>
                        <div class="col-2">
                            <a href="{{url()->previous()}}" class="btn btn-success text-light">Hecho</a>
                        </div>
                    </div>
                    <form action="/{{ substr(Request::fullUrl(), 35) }}" method="post" class=" p-2" id="test" style="border: 1px solid darkgray;  border-radius:22px;">
                    @csrf
                     <button type="submit">test</button>
                    </form>
                </div>
                -->
                <form class="mt-3" id="my-great-dropzone" method="post" accept-charset="UTF-8" action="{{ substr(Request::fullUrl(), 35) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <input type="file" name="file[]" id="file"
                            class="form-control form-control-lg" multiple>
                            <small for="file">Max. 2GB</small>
                            @error('file')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror
                    </div>
                    <div class="mb-3">
                        <a class="btn btn-secondary btn-lg float-start text-light" href="{{ url()->previous() }}" >VOLVER</a>
                        <button type="submit" class="btn btn-success btn-lg float-end">SUBIR</button>
                    </div>
                </form>
                <!--
                <form action="/{{ substr(Request::fullUrl(), 35) }}" class="dropzone mb-3" id="dropzoneForm" enctype="multipart/form-data">
                    @csrf
                </form>
                <div class="mb-3">
                    <a class="btn btn-secondary btn-lg float-start text-light" href="{{ url()->previous() }}" >VOLVER</a>
                    <button type="submit" class="btn btn-success btn-lg float-end" id="submit-file">SUBIR</button>
                </div>
                
                <form action="/{{ substr(Request::fullUrl(), 35) }}" class="dropzone mb-3" id="dropzoneForm" enctype="multipart/form-data">
                    @csrf
                
                </form>
                <script>
                Dropzone.options.updatefiles = {
                    headers:{
                        'X-CSRF-TOKEN' : { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                    },
                    //chunking: true,
                    //forceChunking: true,
                    //parallelChunkUploads: true,
                    uploadMultiple: true,
                    //acceptedFiles: "*/*",
                    dictDefaultMessage: "Ingrese o arrastre sus imagenes aqui"
                };
    </script>-->
            </div>
        </div>
    </div>
</div>
@endsection