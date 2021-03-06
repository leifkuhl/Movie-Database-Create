@extends('layouts.app')
@section('navbar')
<li><a href="{{ route('manageAccounts') }}">Manage Accounts</a></li>
<li><a href="{{ route('manageHosts') }}">Manage Hosts</a></li>
<li class="active"><a href="{{ route('showGrants') }}">Show Grants</a></li>
<li><a href="{{ route('purgeDatabaseServer') }}">Purge Database Server</a></li>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Show Grants</h1></div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#show">Show Grants</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="show" class="tab-pane fade in active">
                            <h3>Show Grants</h3>
                            <p>
                                Shows Grants an seleced host or all hosts if none selected<br>
                            </p>
                            @yield('grantList')
                            <form action="show">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType" name="accType">
                                        <option>All</option>
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="hostName">Host name (can be left blank for a general search)</label>
                                    <input type="text" class="form-control" id="hostName" name="hostName">     
                                </div>
                                <button type="submit" class="btn btn-primary">Show</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
