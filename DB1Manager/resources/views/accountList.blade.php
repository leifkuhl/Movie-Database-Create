@extends('manageAccounts')
@section('accountList')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Account Name</th>
            <th>Host Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tabledataAccNames as $index => $line): ?>
            <tr><td><?php echo $line ?></td><td><?php echo $tabledataHostNames[$index]; ?></td></tr>
        <?php endforeach; ?>
        </div>
    </tbody>
</table>
@endsection