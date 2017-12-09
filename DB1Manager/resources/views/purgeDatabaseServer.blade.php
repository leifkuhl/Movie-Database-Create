@extends('layouts.app')
@section('navbar')
<li><a href="{{ route('manageAccounts') }}">Manage Accounts</a></li>
<li><a href="{{ route('manageHosts') }}">Manage Hosts</a></li>
<li><a href="{{ route('showGrants') }}">Show Grants</a></li>
<li class="active"><a href="{{ route('purgeDatabaseServer') }}">Purge Database Server</a></li>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-danger">
                <div class="panel-heading">Purge Database Server</div>
                <div class="panel-body">
                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
