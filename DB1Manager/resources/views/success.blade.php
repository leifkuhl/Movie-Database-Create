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
            <div class="panel panel-success">
                <div class="panel-heading"><h1>Success!</h1></div>

                <div class="panel-body">
                    <h4>Operation: "{{$operation}}" Succeded:</h4><br> 
                    <div class="well">
                        <?php
                            echo nl2br($message);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
