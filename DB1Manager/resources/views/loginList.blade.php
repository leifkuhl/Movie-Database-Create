@extends('manageAccounts')

@section('loginList')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Account Name</th>
            <th>Password</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tabledata as $index => $line): ?>
            <tr><td><?php echo key($tabledata);
        next($tabledata); ?></td><td><?php echo $line; ?></td></tr>
<?php endforeach; ?>
        </div>
    </tbody>
</table>

@endsection