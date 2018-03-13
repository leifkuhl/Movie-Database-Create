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
                        <li class="active"><a data-toggle="tab" href="#list">List Hosts</a></li>
                        <li><a data-toggle="tab" href="#add">Add Host</a></li>
                        <li><a data-toggle="tab" href="#remove">Remove Host</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="list" class="tab-pane fade in active">
                            <h3>List Hosts</h3>
                            <p>Lists all existing hosts.</p>
                            @yield('hostList')
                            <form action="listHosts#list">
                                <button type="submit" class="btn btn-primary">List Hosts</button>
                            </form>
                        </div>
                        <div id="add" class="tab-pane fade">
                            <h3>Add Host</h3>
                            <p>Adds an additional host with set name and set permissions for all existing accounts.</p>
                            <form action="addHost">
                                <div class="form-group">
                                    <label for="hostName">Host name</label>
                                    <input type="text" class="form-control" id="hostName" name="hostName">     
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                        </div>
                        <div id="remove" class="tab-pane fade">
                            <h3>Remove Host</h3>
                            <p>Removes an host with set name and removes permissions for all existing accounts.</p>
                            <form action="removeHost">
                                <div class="form-group">
                                    <label for="hostName">Host name</label>
                                    <input type="text" class="form-control" id="hostName" name="hostName">     
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
@section('scripts')
<script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script type="text/javascript" src="{{ URL::asset('js/keepSelectedTab.js') }}"></script>
@endsection