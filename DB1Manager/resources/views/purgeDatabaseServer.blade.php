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
                <div class="panel-heading"><h1>Purge Database Server</h1></div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#purge">Purge</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="purge" class="tab-pane fade in active">
                            <h3>Purge Database Server</h3>
                            <p>
                                Deletes all accounts on all hosts and removes all personal databases.
                            </p>
                            <form action="purge">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType" name="accType">
                                        <option>All</option>
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" id="sure" name="sure" value="yes">Yes I am sure</label>
                                </div>
                                <button type="submit" class="btn btn-danger">Purge</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
