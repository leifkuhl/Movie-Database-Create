@extends('layouts.app')
@section('navbar')
<li class="active"><a href="{{ route('manageAccounts') }}">Manage Accounts</a></li>
<li><a href="{{ route('manageHosts') }}">Manage Hosts</a></li>
<li><a href="{{ route('showGrants') }}">Show Grants</a></li>
<li><a href="{{ route('purgeDatabaseServer') }}">Purge Database Server</a></li>
@endsection
@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h1>Manage Accounts</h1></div>
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#create">Create Accounts</a></li>
                        <li><a data-toggle="tab" href="#list">List Accounts</a></li>
                        <li><a data-toggle="tab" href="#generate">Generate Login List</a></li>
                        <li><a data-toggle="tab" href="#reset">Reset Password</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="create" class="tab-pane fade in active">
                            <h3>Create New Accounts</h3>
                            <form action="createAccounts">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType" name = "accType">
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="semesterType">Summer or winter semester?</label>
                                    <select class="form-control" id="semesterType" name = "semesterType">
                                        <option>SS</option>
                                        <option>WS</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="semesterType">Year (e.g. 17 for SS 2017 and 1718 for WS 2017/18):</label>
                                    <input type="text" class="form-control" id="semesterYear" name="semesterYear">     
                                </div>
                                <div class="form-group">
                                    <label for="startIndex">Start index:</label>
                                    <input type="text" class="form-control" id="startIndex" name="startIndex">     
                                </div>
                                <div class="form-group">
                                    <label for="count">Number of accounts</label>
                                    <input type="text" class="form-control" id="count" name="count">     
                                </div>
                                <button type="submit" class="btn btn-primary">Create Accounts</button>
                            </form>
                        </div>
                        <div id="list" class="tab-pane fade">
                            <h3>List All Existing Accounts</h3>
                            @yield('accountList')
                            <form action="listAccounts">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType" name="accType"> 
                                        <option>All</option>
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">List Accounts</button>
                            </form>
                        </div>
                        <div id="generate" class="tab-pane fade">
                            <h3>Generate Default Login List</h3>
                            @yield('loginList')
                            <form action="generateLoginList">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType" name="accType">
                                        <option>All</option>
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate List</button>
                            </form>
                        </div>
                        <div id="reset" class="tab-pane fade">
                            <h3>Resets Password to Default</h3>
                            <form action="resetPassword">
                                <div class="form-group">
                                    <label for="accType">Select account type:</label>
                                    <select class="form-control" id="accType" name="accType">
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label for="AccountName">Full account name (e.g. db_ws1718_s1):</label>
                                    <input type="text" class="form-control" id="accountName" name="accountName">     
                                </div>
                                <button type="submit" class="btn btn-primary">Reset</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection