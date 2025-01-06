@extends('layouts.app')
<style> 
/* General Form Styling */
form {
    background-color: #ffffff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin: 20px 0;
}

form .form-group {
    margin-bottom: 15px;
}

/* Input Fields Styling */
form input[type="text"],
form input[type="email"],
form input[type="password"],
form select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    color: #333;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    border-radius: 4px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

form input[type="text"]:focus,
form input[type="email"]:focus,
form input[type="password"]:focus,
form select:focus {
    border-color: #3498db;
    box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
    outline: none;
}

/* Label Styling */
form label,
form strong {
    display: block;
    font-size: 14px;
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
}

/* Select Box Styling */
form select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 20 20"><polygon points="0,0 10,10 20,0" fill="%233498db"/></svg>');
    background-repeat: no-repeat;
    background-position: right 10px center;
    background-size: 10px 10px;
}

/* Error Message Styling */
form p {
    font-size: 12px;
    color: #e74c3c;
    margin-top: 5px;
}

/* Button Styling */
form button {
    background-color: #3498db;
    color: white;
    border: none;
    padding: 10px 20px;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #2980b9;
}

/* Additional Styling for Icons */
form button i {
    margin-right: 5px;
}

/* Responsive Design */
@media (max-width: 768px) {
    form {
        padding: 15px;
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="password"],
    form select {
        font-size: 14px;
    }

    form button {
        font-size: 12px;
        padding: 8px 15px;
    }
}
</style>
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Create New User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary btn-sm mb-2" href="{{ route('users.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</div>

@if (count($errors) > 0)
    <div class="alert alert-danger">
      <strong>Whoops!</strong> There were some problems with your input.<br><br>
      <ul>
         @foreach ($errors->all() as $error)
           <li>{{ $error }}</li>
         @endforeach
      </ul>
    </div>
@endif

<form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                <input type="text" name="name" placeholder="Name" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Email:</strong>
                <input type="email" name="email" placeholder="Email" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Password:</strong>
                <input type="password" name="password" placeholder="Password" class="form-control">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Confirm Password:</strong>
                <input type="password" name="confirm-password" placeholder="Confirm Password" class="form-control">
            </div>
        </div>
        <div class="input-group">
            <label>Department</label>
            <select id="department_id" name="department_id">
                <option value="">Select Department</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->departmentname }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="input-group">
            <label>Degination</label>
            <select id="designation_id" name="designation_id">
                <option value="">Select Designation</option>
                @foreach($designation as $designations)
                <option value="{{ $designations->id }}">{{ $designations->designationname }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="input-group">
            <label>Role</label>
            <select id="roles[]" name="roles[]" >
                <option value="">Select Role</option>
                @foreach($roles as $value => $label)
                <option value="{{ $value }}">{{ $label }}
                </option>
                @endforeach
            </select>
        </div>
       
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            <button type="submit" class="btn btn-primary btn-sm mt-2 mb-3"><i class="fa-solid fa-floppy-disk"></i> Submit</button>
        </div>
    </div>
</form>

@endsection
