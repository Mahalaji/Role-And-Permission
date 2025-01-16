@extends('Backend.layouts.app')
<style>
    :root {
    --table-bg: white;
    --table-text: black;
    --table-border: #ddd;
    --table:white;
    --border:#ddd;
}

/* Dark Mode */
.dark {
    --table:#333;
    --table-bg: #1e1e1e;
    --table-text: #f0f0f0;
    --table-border: #333;
    --border:grey;

}
#Tables {
    width: 100% !important;
    border-collapse: collapse;
    background-color: var(--table-bg);
    /* Dark background */
    color: var(--table-bg);
    /* Light text color */
    border: 2px solid var(--table-border);
    border-radius: 5px;
}

/* Table Header */
#Tables thead th {
    background-color: var(--table);
    /* Header background */
    color: var(--table-text);
    /* Header text color */
    padding: 12px;
    text-align: center;
    border-bottom: 2px solid white;
    /* Header bottom border */
    font-weight: bold;
    border: 2px solid var(--border) !important;

}

/* Table Body Rows */
#Tables tbody tr {
    border-bottom: 1px solid var(--table-border);
    /* Row separator */
}

#Tables tbody tr:nth-child(odd) {
    background-color: #1e1e1e;
    /* Odd row background */
}

#Tables tbody tr:nth-child(even) {
    background-color: #2a2a2a;
    /* Even row background */
}

/* Row Hover */
#Tables tbody tr:hover {
    background-color: #374151;
    /* Row hover background */
    color: #ffffff;
    /* Row hover text color */
}

/* Table Cells */
#Tables tbody td {
    padding: 12px;
    text-align: left;
    background-color: var(--table);
    color: var(--table-text);
    border: 2px solid var(--border);

}

/* Status Column */
#Tables th#status,
#Tables td#status {
    text-align: center;
    font-weight: bold;
    color: var(--table-text);
    /* Highlight color for status */
}

/* Edit and Delete Buttons */
#Tables tbody td:last-child,
#Tables tbody td:nth-last-child(2) {
    text-align: center;
}

#Tables tbody td a {
    display: inline-block;
    padding: 6px 12px;
    background-color: #4caf50;
    /* Green for Edit */
    color: var(--table-bg);
    border-radius: 4px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    font-size: 14px;
}

#Tables tbody td a.delete {
    background-color: #e74c3c;
    /* Red for Delete */
}

#Tables tbody td a:hover {
    background-color: #45a049;
    /* Darker green for Edit hover */
}

#Tables tbody td a.delete:hover {
    background-color: #c0392b;
}
.dataTables_wrapper .dataTables_length select{
    background-color: var(--table-border) !important;
}
</style>
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Role Management</h2>
        </div>
        <div class="pull-right">
        @can('role-create')
            <a class="btn btn-success btn-sm mb-2" href="{{ route('roles.create') }}"><i class="fa fa-plus"></i> Create New Role</a>
            @endcan
        </div>
    </div>
</div>

@session('success')
    <div class="alert alert-success" role="alert"> 
        {{ $value }}
    </div>
@endsession

<table id="Tables" class="table table-bordered">
    <thead>
  <tr>
     <th width="100px">No</th>
     <th>Name</th>
     <th width="340px">Action</th>
  </tr>
  </thead>
    @foreach ($roles as $key => $role)
    <tr>
        <td>{{ ++$i }}</td>
        <td>{{ $role->name }}</td>
        <td style="padding: 7px;">
            <a class="btn btn-info btn-sm" href="{{ route('roles.show',$role->id) }}"><i class="fa-solid fa-list"></i> Show</a>
            @can('role-edit')
                <a class="btn btn-primary btn-sm" href="{{ route('roles.edit',$role->id) }}"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
            @endcan
            <a class="btn btn-info btn-sm" href="{{route('access',$role->id)}}">Access</a>
            @can('role-delete')
            <form method="POST" action="{{ route('roles.destroy', $role->id) }}" style="display:inline">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i> Delete</button>
            </form>
            @endcan
        </td>
    </tr>
    @endforeach
</table>

{!! $roles->links('pagination::bootstrap-5') !!}

@endsection
