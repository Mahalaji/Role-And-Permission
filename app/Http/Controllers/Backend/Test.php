<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Test extends Controller
{
    
// Index method
public function index()
{
    $columns = ['id', 'title', 'description', 'created_at', 'updated_at']; // Array of selected columns
    $data = DB::table("pages")->select($columns)->get(); // Fetch data from the specified table
    return view('Backend.Test.index', ['columns' => $columns, 'data' => $data]); // Pass as an array
}

// Create method
public function create()
{   
    return view('Backend.Test.create');
}

// Edit method
public function edit($id)
{
   $columns = ['id', 'title', 'description', 'created_at', 'updated_at']; // Columns to edit
    $item = DB::table('pages')->where('id', $id)->first(); // Fetch the item to edit
    return view('Backend.Test.edit', ['item' => $item, 'columns' => $columns]);
}

// Store method
public function store(Request $request)
{
    // Get the table name and columns from the request
    $tablename = $request->input('tablename');
    $columns = $request->input('columns', []);  // Default to an empty array if no columns are selected

    // Prepare the validation rules dynamically based on the columns
    $rules = [];
    foreach ($columns as $column) {
        // Skip the 'id' column, as it's auto-incremented
        if (strtolower($column) !== 'id') {
            $rules[$column] = 'required';  // You can adjust the rules (e.g., 'string', 'max:255', etc.)
        }
    }

    // Validate the incoming request data based on the rules
    $validatedData = $request->validate($rules);

    // Insert the validated data into the table
    DB::table($tablename)->insert($validatedData);

    // Redirect to the module's index page with a success message
    return redirect("/Test")->with('success', "Test created successfully.");
}

// Update method
public function update(Request $request)
{
    $tablename = $request->input('tablename');
    $columns = $request->input('columns', []);  // Columns from the form
    $id = $request->input('id'); // Hidden input field with ID

    $rules = [];
    foreach ($columns as $column) {
        if (strtolower($column) !== 'id') {
            $rules[$column] = 'required'; // Adjust validation rules
        }
    }

    $validatedData = $request->validate($rules);
    DB::table($tablename)->where('id', $id)->update($validatedData);

    return redirect("/Test")->with('success', "Test updated successfully.");
}

// Delete method
public function delete($id)
{
    $tablename = request()->input('tablename'); // Get the table name from the request

    // Delete the record with the given ID
    DB::table($tablename)->where('id', $id)->delete();

    // Redirect to the module's index page with a success message
    return redirect("/Test")->with('success', "Test deleted successfully.");
}

    //
}
