<!DOCTYPE html>
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Test</div>
                    <div class="panel-body">
                        @guest
                            <h1> Hallo, Gast: {{ $name }} </h1>
                        @else
                            <h1>Hallo, {{ $name }} oder auch {{ Auth::user()->name }} </h1>
                            <a href="{{ route('home') }}">Home</a>
                        @endguest
                     </div>
                </div>
             </div>
        </div>
    </div>
</div>
@endsection
