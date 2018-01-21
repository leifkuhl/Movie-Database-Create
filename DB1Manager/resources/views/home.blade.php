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
                <div class="panel-heading"><h1>DB 1 Manager</h1></div>

                <div class="panel-body">
                    <p>
                        This is a collection of php scripts for managing mySQL accounts for the students and tutors in the Database I course. The scripts currently provides the following functionalities:
                    </p>
                    <a href="manageAccounts">
                        <h2>Manage Accounts</h2>
                    </a>
                    <p>
                        Process for <a href="manageAccounts#create">creating</a> and <a href="manageAccounts#delete">deleting</a> 
                        accounts, <a href="manageAccounts#list">listing all existing accounts</a> on hosts, 
                        <a href="manageAccounts#generate">generating password list</a> for all accounts and <a href="manageAccounts#reset">resetting the password</a> for an account.
                    </p>
                    <a href="manageHosts">
                        <h2>Manage Hosts</h2>
                    </a>
                    <p>
                        Process for <a href="manageHosts#add">adding</a>, <a href="manageHosts#delete">deleting</a> or <a href="manageHosts#delete">listing</a> hosts. 
                    </p>
                    <a href="showGrants">
                        <h2>Show Grants</h2>
                    </a>
                    <p>
                        View all grants given to accounts on every/specific host(s).
                    </p>
                    <a href="purge">
                        <h2>Purge Database Server</h2>
                    </a>
                    <p>
                        Delete all accounts and their personal databases.
                    </p>
                    <a href="setup">
                        <h2>Setup Server</h2>
                    </a>
                    <p>
                        Setup the server for first time use (required):<br><a href="setup">Setup the users table and adds the default account to enable login on this site.</a><br>
                        <a href="setupHosts">Setup the host table and adds the default hosts.</a>
            </div>
        </div>
    </div>
</div>
@endsection
