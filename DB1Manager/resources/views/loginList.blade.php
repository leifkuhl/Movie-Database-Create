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
        <?php foreach($formdata as $index=>$line): ?>
            <tr><td><?php echo key($formdata);next($formdata); ?></td><td><?php echo $line; ?></td></tr>
        <?php endforeach; ?>
        </div>
    </tbody>
</table>

@endsection
