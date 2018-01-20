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
                        <li><a data-toggle="tab" href="#delete">Delete Accounts</a></li>
                        <li><a data-toggle="tab" href="#list">List Accounts</a></li>
                        <li><a data-toggle="tab" href="#generate">Generate Login List</a></li>
                        <li><a data-toggle="tab" href="#reset">Reset Password</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="create" class="tab-pane fade in active">
                            <h3>Create New Accounts</h3>
                            The account name has the following pattern:<br>
                            db_<strong><font color="#428bca">{semester}</font></strong><strong><font color="#d9534f">{year}</font></strong>_<strong><font color="#5cb85c">{type}</font></strong><strong><font color="#5bc0de">{index}</font></strong><br>
                            e.g. db_ws1718_s1 or db_ss18_s1<br>
                            The year and index are optional and selected automatically when left blanc. The year is set to the current year and the index continues from the account with the highest index.
                            <form action="createAccounts">
                                <div class="form-group">
                                    <label for="accType">Select account <strong><font color="#5cb85c">type</font></strong>:</label>
                                    <select class="form-control" id="accType" name = "accType">
                                        <option>Student</option>
                                        <option>Tutor</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="semesterType">Summer or winter <strong><font color="#428bca">semester</font></strong>?</label>
                                    <select class="form-control" id="semesterType" name = "semesterType">
                                        <option>SS</option>
                                        <option>WS</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="semesterType"><strong><font color="#d9534f">Year</font></strong> (e.g. 17 for SS 2017 and 1718 for WS 2017/18) (can be left blanc to be autofilled with current year):</label>
                                    <input type="text" class="form-control" id="semesterYear" name="semesterYear">     
                                </div>
                                <div class="form-group">
                                    <label for="startIndex">Start <strong><font color="#5bc0de">index</font></strong> (can be left blanc to continue from the highest index):</label>
                                    <input type="text" class="form-control" id="startIndex" name="startIndex">     
                                </div>
                                <div class="form-group">
                                    <label for="count">Number of accounts:</label>
                                    <input type="text" class="form-control" id="count" name="count">     
                                </div>
                                <button type="submit" class="btn btn-primary">Create Accounts:</button>
                            </form>
                        </div>
                        <div id="delete" class="tab-pane fade">
                            <h3>Delete Accounts</h3>
                            <form action="deleteAccounts">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" class="select-all checkbox" name="select-all" />
                                            </th>
                                            <th>Account Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($accounts as $index => $account): ?>
                                        <tr>
                                            <td> 
                                                <input type="checkbox" class="select-item checkbox" name={{$account}}>                                                           </label>
                                            </td>
                                            <td>
                                                <?php echo $account ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                        <div id="list" class="tab-pane fade">
                            <h3>List All Existing Accounts</h3>
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
                            <h3>Reset Password to Default</h3>
                            <form action="resetPassword">
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
@section('scripts')
<script src="//cdn.bootcss.com/jquery/2.2.1/jquery.min.js"></script>
<script src="//cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script>
$(function () {

    //button select all or cancel
    $("#select-all").click(function () {
        var all = $("input.select-all")[0];
        all.checked = !all.checked
        var checked = all.checked;
        $("input.select-item").each(function (index, item) {
            item.checked = checked;
        });
    });

    //button select invert
    $("#select-invert").click(function () {
        $("input.select-item").each(function (index, item) {
            item.checked = !item.checked;
        });
        checkSelected();
    });

    //button get selected info
    $("#selected").click(function () {
        var items = [];
        $("input.select-item:checked:checked").each(function (index, item) {
            items[index] = item.value;
        });
        if (items.length < 1) {
            alert("no selected items!!!");
        } else {
            var values = items.join(',');
            console.log(values);
            var html = $("<div></div>");
            html.html("selected:" + values);
            html.appendTo("body");
        }
    });

    //column checkbox select all or cancel
    $("input.select-all").click(function () {
        var checked = this.checked;
        $("input.select-item").each(function (index, item) {
            item.checked = checked;
        });
    });

    //check selected items
    $("input.select-item").click(function () {
        var checked = this.checked;
        console.log(checked);
        checkSelected();
    });

    //check is all selected
    function checkSelected() {
        var all = $("input.select-all")[0];
        var total = $("input.select-item").length;
        var len = $("input.select-item:checked:checked").length;
        console.log("total:" + total);
        console.log("len:" + len);
        all.checked = len === total;
    }
});
</script>
@endsection