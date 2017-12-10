@extends('layouts.app')
@section('navbar')
<li><a href="{{ route('manageAccounts') }}">Manage Accounts</a></li>
<li class="active"><a href="{{ route('manageHosts') }}">Manage Hosts</a></li>
<li><a href="{{ route('showGrants') }}">Show Grants</a></li>
<li><a href="{{ route('purgeDatabaseServer') }}">Purge Database Server</a></li>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Manage Hosts</h1></div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#list">List Accounts</a></li>
                        <li><a data-toggle="tab" href="#add">Add Host</a></li>
                        <li><a data-toggle="tab" href="#remove">Remove Host</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="list" class="tab-pane fade in active">
                            <h3>List Accounts</h3>
                            <form action="/action_page.php">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType">
                                        <option>All</option>
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate List</button>

                            </form>
                        </div>
                        <div id="add" class="tab-pane fade">
                            <h3>Add Host</h3>
                            <form action="/action_page.php">
                                <div class="form-group">
                                    <label for="HostName">Host name</label>
                                    <input type="text" class="form-control" id="HostName">     
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                        <div id="remove" class="tab-pane fade">
                            <h3>Remove Host</h3>
                            <form action="/action_page.php">
                                <div class="form-group">
                                    <label for="HostName">Host name</label>
                                    <input type="text" class="form-control" id="HostName">     
                                </div>
                                <button type="submit" class="btn btn-danger">Remove</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
