@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-body text-center">
                <span class="titulo">INVITAR USUARIO</span>
                <form class="mt-3" method="post" accept-charset="UTF-8" action="/docum-invite" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $file->id }}" name="file">
                    <input type="hidden" value="old" name="opt" id="opt">
                        <div class="mb-3">
                            <select class="multiselectbutton form-select form-select-lg" name="invitado" id="invitado" aria-label="Default select example" required>
                                <option value="" disabled selected>Seleccione usuario</option>
                                @foreach ($users as $user)  
                                        @if ($user->id != Auth::user()->id)
                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                        @endif
                                @endforeach
                            </select>
                            @error('invitado')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror
                        </div>
                    <div class="mb-3">
                        <input type="text" name="username" id="username" style="display: none"
                            class="form-control form-control-lg" placeholder="username">
                            @error('username')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" id="email" style="display: none"
                            class="form-control form-control-lg" placeholder="email">
                            @error('email')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" id="password" style="display: none"
                            class="form-control form-control-lg" placeholder="contraseña">
                            @error('password')
                                <small style="color: darkred">*{{$message}}</small>
                            @enderror
                    </div>
                    <div class="mb-3 row p-2">
                        <div class="col-1">
                            <input onchange="checking(this)" class="col selectCheck"  type="checkbox" 
                            form="moveSelect" value="" id="" name="documM[]">
                        </div>
                        <div class="col-11 text-start">
                            <label for="">Crear Invitado</label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <a class="btn btn-secondary btn-lg float-start text-light col" href="{{ url()->previous() }}" >VOLVER</a>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-success btn-lg float-end ">INVITAR</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>    
<script>
    function checking(chec) {
        var valu = chec.value;
        if (chec.checked == true) {
            document.getElementById('opt').value='new';
            document.getElementById('username').style.display='';
            document.getElementById('email').style.display='';
            document.getElementById('password').style.display='';
            document.getElementById('username').required=true;
            document.getElementById('email').required=true;
            document.getElementById('password').required=true;
            $('.multiselectbutton').prop('disabled',true);
            $('.multiselectbutton').prop('required',false);
        } else {
            document.getElementById('opt').value='old';
            document.getElementById('username').style.display='none';
            document.getElementById('email').style.display='none';
            document.getElementById('password').style.display='none';
            document.getElementById('username').required=false;
            document.getElementById('email').required=false;
            document.getElementById('password').required=false;
            $('.multiselectbutton').prop('disabled',false);
            $('.multiselectbutton').prop('required',true);
        }
    }
</script> 

@endsection