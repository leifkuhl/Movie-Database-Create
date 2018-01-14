@extends('layouts.app')
@section('navbar')
<li><a href="{{ route('manageAccounts') }}">Manage Accounts</a></li>
<li><a href="{{ route('manageHosts') }}">Manage Hosts</a></li>
<li><a href="{{ route('showGrants') }}">Show Grants</a></li>
<li><a href="{{ route('purgeDatabaseServer') }}">Purge Database Server</a></li>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Setup Hosts</h1></div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="setupHosts">
                            <h4>Setup the host table and adds the default hosts.<br>
                                Afterwards you are redirected to the home.<br>
                            </h4>
                            <form action="setupDefaultHosts">
                                <button type="submit" class="btn btn-primary">Setup</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection