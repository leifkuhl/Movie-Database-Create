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
                <div class="panel-heading"><h1>Setup Users</h1></div>
                <div class="panel-body">
                    <div class="tab-content">
                        <div id="setupUsers">
                            <h4>Setup the users table and adds the default account to enable login on this site.<br>
                                Afterwards you are redirected to login and the setup of the hosts table.<br>
                            </h4>
                            <form action="setupUsers">
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