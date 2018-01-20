@extends('showGrants')
@section('grantList')
<table class="table table-bordered" id ="grantTable">
    <thead>
        <tr>
            <th>Account Name</th>
            <th>Host</th>
            <th>Database</th>
            <th>Privileges</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $index => $user): ?>
            <tr><td><?php echo $user; ?></td><td><?php echo $hosts[$index]; ?></td><td><?php echo $databases[$index]; ?></td><td><?php echo $privileges[$index]; ?></td></tr>
        <?php endforeach; ?>
        </div>
    </tbody>
</table>
@endsection
