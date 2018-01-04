@extends('manageAccounts')
@section('accountList')
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Account Name</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($formdata as $line): ?>
            <tr><td><?php echo $line; ?></td></tr>
        <?php endforeach; ?>
        </div>
    </tbody>
</table>

@endsection