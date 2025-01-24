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
        $columns = ['id', 'title', 'name', 'username', 'slug']; // Array of selected columns
        $data = DB::table("blogs")->select($columns)->get(); // Fetch data from the specified table
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
        $item = DB::table('blogs')->where('id', $id)->first(); // Fetch the item to edit
        return view('Backend.Test.edit', ['item' => $item]);
    }

    //
}
