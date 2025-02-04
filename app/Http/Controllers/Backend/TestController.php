<?php

namespace App\Http\Controllers\Backend;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    // Index method
    public function index()
    {
        $columns = ['id', 'name', 'title', 'updated_at'];
        $data = DB::table("test")->select($columns)->get();
        return view('Backend.Test.index', compact('columns', 'data'));
    }
    
    // Create method
 public function create()
   {


// Check if  is not empty
if (!empty("")) {
    return view('Backend.Test.create', compact(""));
} else {
    // If  is empty, pass no variables to the view
    return view('Backend.Test.create');
}
 }
    
    // Edit method
    public function edit($id)
    {
        $columns = ['id', 'name', 'title', 'updated_at'];
        $text = DB::table('test')->where('id', $id)->first();
        
        // Check if  is not empty
if (!empty("")) {
   return view('Backend.Test.edit', compact('text', 'columns', ''));
} else {
    // If  is empty, pass no variables to the view
   return view('Backend.Test.edit', compact('text', 'columns'));
}
        
    }
    
    // Store method
    public function store(Request $request)
    {
        // Define dynamic validation rules
         $rules = [];
    
    // Generate validation rules based on column types
    foreach (['id', 'name', 'title', 'image', 'updated_at'] as $col) {
     if ($col == 'id') {
            continue;
        }
        $type = $inputTypes[$col] ?? 'string';  // Default to 'string' if no type is specified
        
        if ($type == 'text' || $type == 'string') {
            $rules[$col] = 'required|string|max:255';
        } elseif ($type == 'number' || $type == 'integer') {
            $rules[$col] = 'required|integer';
        } elseif ($type == 'email') {
            $rules[$col] = 'required|email';
        } elseif ($type == 'date') {
            $rules[$col] = 'required|date';
        } else {
            $rules[$col] = 'required';
        }
    }

    // Validate the incoming request data
    $validatedData = $request->validate($rules);
     $validatedData['image'] =$request->image;
    // Insert validated data into the database
    DB::table('test')->insert($validatedData);

    return redirect('/Test')->with('success', 'Test created successfully.');
}
    
    // Update method
    public function update(Request $request)
    {
        // Define dynamic validation rules
       $rules = [];
    
    // Generate validation rules based on column types
    foreach (['id', 'name', 'title', 'image', 'updated_at'] as $col) {
        $type = $inputTypes[$col] ?? 'string';  // Default to 'string' if no type is specified
        
        // Skip the 'id' column for validation
        if ($col == 'id') {
            continue;
        }
        
        if ($type == 'text' || $type == 'string') {
            $rules[$col] = 'required|string|max:255';
        } elseif ($type == 'number' || $type == 'integer') {
            $rules[$col] = 'required|integer';
        } elseif ($type == 'email') {
            $rules[$col] = 'required|email';
        } elseif ($type == 'date') {
            $rules[$col] = 'required|date';
        } else {
            $rules[$col] = 'required';
        }
    }

    // Validate the incoming request data
    $validatedData = $request->validate($rules);
    $validatedData['image'] =$request->image;
    // Update validated data in the database
    DB::table('test')->where('id', $request->id)->update($validatedData);

    return redirect('/Test')->with('success', 'Test updated successfully.');
}
    
    // Delete method
    public function delete($id)
    {
        DB::table('test')->where('id', $id)->delete();
        return redirect('/Test')->with('success', 'Test deleted successfully.');
    }
    //
}
