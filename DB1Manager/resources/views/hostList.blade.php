@extends('manageHosts')
@section('hostList')
<table class="table table-bordered" id ="grantTable">
    <thead>
        <tr>
            <th>Host Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tabledata as $line): ?>
            <tr><td><?php echo $line; ?></td></tr>
        <?php endforeach; ?>
        </div>
    </tbody>
</table>
@endsection