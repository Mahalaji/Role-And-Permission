<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index(Request $request)

    {   

        return view('Backend.filemanager.index');

    }
}
