@extends('layouts.app')

@section('content')
<style>
    iframe
    {
        width: 100%;
        height: 600px;
    }
</style>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 ">
            <div class="card " style="background-color: darkgray;">
                @if ($check == 'office')
                    <iframe src="https://docs.google.com/gview?url={{$preauth}}{{$doc->filecode}}&embedded=true" frameborder="0"></iframe>
                @else
                    <iframe src="{{$preauth}}{{$doc->filecode}}" frameborder="0"></iframe>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection