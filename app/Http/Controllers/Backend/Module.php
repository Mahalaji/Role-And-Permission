<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\modules;
use App\Models\menus;
use Illuminate\Support\Facades\DB;
use App\Models\permissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\log;
use Illuminate\Support\Facades\Schema;

class Module extends Controller
{
    public function index()
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        foreach ($tables as $table) {
            foreach ($table as $tableName) {
                $tableNames[] = $tableName;
            }
        }

        return view('Backend.module.index', compact('tableNames'));
    }
    public function mvctable(Request $request)
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        foreach ($tables as $table) {
            foreach ($table as $tableName) {
                $tableNames[] = $tableName;
            }
        }
        $moduleId = $request->input('moduleId');
        $tablename = $request->tableName;
        $columns = Schema::getColumnListing($tablename);
        return view('Backend.module.mvctable', compact('columns', 'moduleId', 'tablename', 'tableNames'));
    }
    public function getColumns($table)
    {
        // Get the column list from the table
        $columns = Schema::getColumnListing($table);

        return response()->json(['columns' => $columns]);
    }
    public function getModuleAjax(Request $request)
    {
        try {
            $query = modules::select('id', 'module_name', 'permission', 'parent_id', 'created_at', 'updated_at');
            $query = modules::where('deletestatus', 1);
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }



            return DataTables::of($query)
                // ->addColumn('addpermissions', function ($row) {
                //     return '<button class="btn btn-sm btn-success" id="permissionsbtn" data-module-id="' . $row->id . '">
                //                 <i class="fas fa-key"></i> 
                //             </button>';
                // })
                ->addColumn('addpermissions', function ($row) {
                    return '<button class="btn btn-sm btn-success" id="permissionsbtn" data-module-id="' . $row->id . '">
                           Add permission
                        </button>';
                })
                // ->addColumn('add', function ($row) {
                //     return '<a href="/submodule/add/' . $row->id . '"style="color:black">Add Module</a>';
                // })
                // ->addColumn('addpermission', function ($row) {
                //     return '<a href="/module/permission/add/' . $row->id . '"style="color:black">Add Permission</a>';
                // })
                ->addColumn('MVCcreate', function ($row) {
                    return "<button class='editModuleButton btn btn-primary' data-id='{$row->id}'>MVC</button>";
                })

                ->addColumn('delete', function ($row) {
                    return '<form action="/destorymodule/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                ->addColumn('time_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                })
                ->addColumn('time_update_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->diffForHumans();
                })
                ->rawColumns(['MVCcreate', 'delete', 'time_ago', 'time_update_ago', 'addpermissions'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function add_submodule($id)
    {
        $module = modules::find($id);

        if (!$module) {
            return redirect()->back()->with('error', 'Module not found');
        }

        if (!is_object($module)) {
            return redirect()->back()->with('error', 'Invalid module data');
        }


        return view('Backend.module.subcreate', compact('module'));
    }
    public function addsubmodule(Request $request)
    {

        $userid = Auth::user();

        $request->validate([
            'module_name' => 'required',
        ]);
        $moduleadd = new modules();
        $moduleadd->module_name = $request->module_name;
        $moduleadd->parent_id = $request->id;
        $moduleadd->user_id = $userid->id;

        $moduleadd->save();

        if ($moduleadd) {
            return redirect('/module')->with('success', 'Module added successfully!');
        } else {
            return back()->with('error', 'Failed to add the module.');
        }
    }
    public function addmodule(Request $request)
    {
        $userid = Auth::user();

        $request->validate([
            'module_name' => 'required',
        ]);

        $moduleadd = new modules();
        $moduleadd->module_name = $request->module_name;
        $moduleadd->user_id = $userid->id;

        $moduleadd->parent_id = $request->parent_id ?? 0;

        $moduleadd->save();

        if ($moduleadd) {
            return redirect('/module')->with('success', 'Module added successfully!');
        } else {
            return back()->with('error', 'Failed to add the module.');
        }
    }

    public function moduleadd(Request $request)
    {
        $module = modules::where('parent_id', 0)->get();
        return view('Backend.module.create', compact('module'));

    }
    public function mvc(Request $request)
    {
        $selectedData = json_decode($request->input('selected_data'), true);
        $inputTypes = $request->input('inputTypes', []);
        $id = $request->moduleId;
        $tablename = $request->tablename;
        $columns = $request->input('columns', []);
        $columnss = Schema::getColumnListing($tablename);
        // dd($tablename);

        // Validate the module
        $module = modules::find($id);
        if (!$module) {
            return redirect()->back()->withErrors('Module not found');
        }

        // Format module names
        $module_name = ucfirst(strtolower(str_replace(' ', '', $module->module_name)));
        $plural_module_name = $module_name . 's';

        // Paths for MVC components
        $modelPath = app_path("Models/$plural_module_name.php");
        $controllerPath = app_path("Http/Controllers/Backend/{$module_name}Controller.php");
        $viewDirectory = resource_path("views/Backend/$module_name");
        // Cleanup existing files
        $this->cleanupFiles($modelPath, $controllerPath, $viewDirectory);
        // Generate Model and Controller
        Artisan::call("make:model $plural_module_name");
        Artisan::call("make:controller Backend/{$module_name}Controller");

        $this->updateModel($modelPath, $tablename);
        // Generate Controller Methods
        $this->updateController($controllerPath, $tablename, $columns, $module_name, $selectedData, $columnss);

        // Generate Views
        $this->generateViews($viewDirectory, $module_name, $tablename, $columns, $inputTypes, $selectedData);

        // Clear View Cache
        Artisan::call('view:clear');

        // Register Routes
        $this->registerRoutes($module_name);

        return redirect('/module')->with('success', 'MVC structure recreated successfully.');
    }
    protected function cleanupFiles($modelPath, $controllerPath, $viewDirectory)
    {
        if (file_exists($modelPath))
            unlink($modelPath);
        if (file_exists($controllerPath))
            unlink($controllerPath);
        if (File::exists($viewDirectory))
            File::deleteDirectory($viewDirectory);
    }
    protected function updateController($controllerPath, $tablename, $columns, $module_name, $selectedData, $columnss)
    {
        $columnsString = implode("', '", $columns);
        $columnss = implode("', '", $columnss);
        // Helper function to generate select2 data fetching code
        $select2DataCode = '';
        foreach ($selectedData ?? [] as $config) {
            if ($config['method'] === 'table') {
                $select2DataCode .= "
                \$select2_{$config['columnName']} = DB::table('{$config['table']}')
                    ->select('{$config['column']}')
                    ->distinct()
                    ->get();";
            }
        }

        // Generate compact variables for select2 data
        $select2CompactVars = '';
        if (!empty($selectedData)) {
            $select2CompactVars = implode(", ", array_map(function ($config) {
                return "select2_{$config['columnName']}";
            }, array_filter($selectedData, function ($config) {
                return $config['method'] === 'table';
            })));
        }

        $methods = <<<EOD
        // Index method
        public function index()
        {
            \$columns = ['$columnsString'];
            \$data = DB::table("$tablename")->select(\$columns)->get();
            return view('Backend.$module_name.index', compact('columns', 'data'));
        }
        
        // Create method
     public function create()
       {
    $select2DataCode
    
    // Check if $select2CompactVars is not empty
    if (!empty("$select2DataCode")) {
        return view('Backend.$module_name.create', compact("{$select2CompactVars}"));
    } else {
        // If $select2CompactVars is empty, pass no variables to the view
        return view('Backend.$module_name.create');
    }
     }
        
        // Edit method
        public function edit(\$id)
        {
            \$columns = ['$columnsString'];
            \$text = DB::table('$tablename')->where('id', \$id)->first();
            $select2DataCode
            // Check if $select2CompactVars is not empty
    if (!empty("$select2DataCode")) {
       return view('Backend.$module_name.edit', compact('text', 'columns', '{$select2CompactVars}'));
    } else {
        // If $select2CompactVars is empty, pass no variables to the view
       return view('Backend.$module_name.edit', compact('text', 'columns'));
    }
            
        }
        
        // Store method
        public function store(Request \$request)
        {
            // Define dynamic validation rules
             \$rules = [];
        
        // Generate validation rules based on column types
        foreach (['$columnss'] as \$col) {
         if (\$col == 'id') {
                continue;
            }
            \$type = \$inputTypes[\$col] ?? 'string';  // Default to 'string' if no type is specified
            
            if (\$type == 'text' || \$type == 'string') {
                \$rules[\$col] = 'required|string|max:255';
            } elseif (\$type == 'number' || \$type == 'integer') {
                \$rules[\$col] = 'required|integer';
            } elseif (\$type == 'email') {
                \$rules[\$col] = 'required|email';
            } elseif (\$type == 'date') {
                \$rules[\$col] = 'required|date';
            } else {
                \$rules[\$col] = 'required';
            }
        }

        // Validate the incoming request data
        \$validatedData = \$request->validate(\$rules);
         \$validatedData['image'] =\$request->image;
        // Insert validated data into the database
        DB::table('$tablename')->insert(\$validatedData);

        return redirect('/$module_name')->with('success', '$module_name created successfully.');
    }
        
        // Update method
        public function update(Request \$request)
        {
            // Define dynamic validation rules
           \$rules = [];
        
        // Generate validation rules based on column types
        foreach (['$columnss'] as \$col) {
            \$type = \$inputTypes[\$col] ?? 'string';  // Default to 'string' if no type is specified
            
            // Skip the 'id' column for validation
            if (\$col == 'id') {
                continue;
            }
            
            if (\$type == 'text' || \$type == 'string') {
                \$rules[\$col] = 'required|string|max:255';
            } elseif (\$type == 'number' || \$type == 'integer') {
                \$rules[\$col] = 'required|integer';
            } elseif (\$type == 'email') {
                \$rules[\$col] = 'required|email';
            } elseif (\$type == 'date') {
                \$rules[\$col] = 'required|date';
            } else {
                \$rules[\$col] = 'required';
            }
        }

        // Validate the incoming request data
        \$validatedData = \$request->validate(\$rules);
        \$validatedData['image'] =\$request->image;
        // Update validated data in the database
        DB::table('$tablename')->where('id', \$request->id)->update(\$validatedData);

        return redirect('/$module_name')->with('success', '$module_name updated successfully.');
    }
        
        // Delete method
        public function delete(\$id)
        {
            DB::table('$tablename')->where('id', \$id)->delete();
            return redirect('/$module_name')->with('success', '$module_name deleted successfully.');
        }
    EOD;

        // Read the controller content
        $controllerContent = file_get_contents($controllerPath);

        // Add use statements
        $controllerContent = preg_replace(
            '/namespace\s+[A-Za-z0-9\\\]+;/',
            "$0\nuse Illuminate\Support\Facades\DB;\nuse Illuminate\Support\Collection;",
            $controllerContent
        );

        // Insert the methods
        $controllerContent = preg_replace('/\{/', "{\n" . $methods, $controllerContent, 1);

        // Write the updated content
        file_put_contents($controllerPath, $controllerContent);
    }
    protected function generateViews($viewDirectory, $module_name, $tablename, $columns, $inputTypes, $selectedData)
    {
        // Helper function to generate select2 field
        function generateSelect2Field($col, $config)
        {
            $coll = ucwords($col);
            if ($config['method'] === 'table') {
                return "
                <div class='input-group'>
                    <label>$coll</label><br>
                    <select class='form-control select2' name='$col' id='$col'>
                        <option value=''>Select $coll</option>
                        @foreach(\$select2_{$config['columnName']} as \$option)
                            <option value='{{ \$option->{$config['column']} }}' {{ isset(\$text) && \$text->$col == \$option->{$config['column']} ? 'selected' : '' }}>
                                {{ \$option->{$config['column']} }}
                            </option>
                        @endforeach
                    </select>
                      @error('$col')
                            <span class='text-danger'>{{ \$message }}</span>
                        @enderror
                </div>";
            } else {
                return "
                <div class='input-group'>
                    <label>$coll</label><br>
                    <select class='form-control select2' name='$col' id='$col'>
                        <option value=''>Select $coll</option>
                        @foreach(explode(',', '{$config['key']}') as \$index => \$key)
                            @php
                                \$value = explode(',', '{$config['value']}')[\$index];
                            @endphp
                            <option value='{{ \$key }}' {{ isset(\$text) && \$text->$col == \$key ? 'selected' : '' }}>
                                {{ \$value }}
                            </option>
                        @endforeach
                    </select>
                      @error('$col')
                            <span class='text-danger'>{{ \$message }}</span>
                        @enderror
                </div>";
            }
        }

        // Create index view content
        $indexContent = <<<EOD
        @extends('Backend.layouts.app')
        <link rel="stylesheet" href="{{ asset('css/Backend/blog.css') }}">
        @section('content')
        <div class="info" style="background: white;">
            <div class="container mt-4">
                <h2>$module_name List</h2>
                <a href="/$module_name/create" class="btn btn-primary">Add $module_name</a>
                <table id="table" class="table">
                    <thead>
                        <tr>
                            @foreach(\$columns as \$col)
                                @if(\$col != 'id')
                                    <th>{{ ucfirst(\$col) }}</th>
                                @endif
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\$data as \$row)
                            <tr>
                                @foreach(\$columns as \$col)
                                    @if(\$col != 'id')
                                        <td>{{ \$row->\$col }}</td>
                                    @endif
                                @endforeach
                                <td>
                                    <a href="/$module_name/edit/{{ \$row->id }}" class="btn btn-warning">Edit</a>
                                    <form action="/$module_name/delete/{{ \$row->id }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endsection
        EOD;

        // Create form fields
        $formFields = '';
        foreach ($inputTypes as $col => $type) {
            if ($col != 'id') {
                if ($type === 'select2') {
                    $config = collect($selectedData)->firstWhere('columnName', $col);
                    if ($config) {
                        $formFields .= generateSelect2Field($col, $config);
                    }
                } elseif ($type === 'file') {
                    $coll = ucwords($col);
                    $formFields .= "
                    <div class='mb-3'>
                        <label class='form-label fw-bold'>$coll</label><br>
                        <div class='d-flex flex-column align-items-center'>
                            <div class='input-group'>
                                <input type='text' id='{$col}_label' class='form-control' name='$col'
                                    placeholder='Select $coll...' aria-label='$coll'>
                                   
                                <button class='btn btn-outline-secondary' type='button' id='button-image'>Select</button>
                              
                            </div>
                               @error('$col')
                            <span class='text-danger'>{{ \$message }}</span>
                        @enderror
                        </div>
                    </div>";
                } else {
                    $coll = ucwords($col);
                    $formFields .= "
                    <div class='input-group'>
                        <label>$coll</label><br>
                        <input type='$type' name='$col' />
                          @error('$col')
                            <span class='text-danger'>{{ \$message }}</span>
                        @enderror
                    </div>";
                }
            }
        }
        $formeditFields = '';
        foreach ($inputTypes as $col => $type) {
            if ($col == 'id') {
                // Hidden input for 'id'
                $formeditFields .= "<input type='hidden' name='$col' value='{{ \$text->$col }}' />";
            } else {
                $coll = ucwords($col); // Capitalize the column name for labels
        
                if ($type === 'select2') {
                    $config = collect($selectedData)->firstWhere('columnName', $col);
                    if ($config) {
                        $formeditFields .= generateSelect2Field($col, $config);
                    }
                } elseif ($type === 'file') {
                    $formeditFields .= "
                    <div class='mb-3'>
                      <label class='form-label fw-bold'>$coll</label><br>
                      <div class='d-flex flex-column align-items-center'>
                          <img src='{{ asset(\$text->$col) }}' alt='Uploaded Image' class='img-thumbnail mb-2' height='100' width='100'>
                          <div class='input-group'>
                              <input type='text' id='image_label' class='form-control' name='image'
                                  placeholder='Select an image...' aria-label='Image'>
                              <button class='btn btn-outline-secondary' type='button' id='button-image'>Select</button>
                          </div>
                             @error('$col')
                            <span class='text-danger'>{{ \$message }}</span>
                        @enderror
                      </div>
                  </div>";
                } else {
                    $formeditFields .= "
                    <div class='input-group'>
                        <label>$coll</label><br>
                        <input type='$type' name='$col' value='{{ old('$col', \$text->$col) }}' />
                        @error('$col')
                            <span class='text-danger'>{{ \$message }}</span>
                        @enderror
                    </div>";
                }
            }
        }

        // Create view content
        $createContent = <<<EOD
        @extends('Backend.layouts.app')
        @section('content')
        <link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
        <main id="main" class="main">
            <h1 class="header">Create $module_name</h1>
            <form class="simple" method="post" action="/$module_name/store" enctype="multipart/form-data">
                <div class="form1">
                    @csrf
                    $formFields
                      
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </main>
        @endsection
    
        @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Select an option'
                });
            });
        </script>
        @endsection
        EOD;

        // Edit view content
        $editContent = <<<EOD
        @extends('Backend.layouts.app')
        @section('content')
        <link rel="stylesheet" href="{{ asset('css/Backend/create.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
        <main id="main" class="main">
            <h1 class="header">Edit $module_name</h1>
            <form class="simple" method="post" action="/$module_name/update" enctype="multipart/form-data">
                <div class="form1">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="tablename" value="$tablename">
                    $formeditFields
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </main>
        @endsection
    
        @section('scripts')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    width: '100%',
                    placeholder: 'Select an option'
                });
            });
        </script>
        @endsection
        EOD;

        // Save views
        File::makeDirectory($viewDirectory, 0755, true);
        file_put_contents("$viewDirectory/index.blade.php", $indexContent);
        file_put_contents("$viewDirectory/create.blade.php", $createContent);
        file_put_contents("$viewDirectory/edit.blade.php", $editContent);
    }
    private function updateModel($modelPath, $tablename)
    {
        if (file_exists($modelPath)) {
            $modelContent = file_get_contents($modelPath);

            // Add the $table property if it doesn't already exist
            if (!str_contains($modelContent, '$table')) {
                $tableProperty = "\n    protected \$table = '$tablename';\n";
                $modelContent = preg_replace('/class\s+\w+\s+extends\s+Model\s*\{/', "$0$tableProperty", $modelContent);
                file_put_contents($modelPath, $modelContent);
            }
        }
    }
    protected function registerRoutes($module_name)
    {
        $routePath = base_path('routes/web.php');
        $routeContent = file_get_contents($routePath);

        // Check if routes already exist
        $routePattern = "/Route::get\('\/$module_name'/";
        if (!preg_match($routePattern, $routeContent)) {
            // Routes don't exist, append new routes
            $routes = <<<EOD
    
    // $module_name Routes
    Route::get('/$module_name', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'index'])->name('$module_name');
    Route::get('/$module_name/create', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'create']);
    Route::post('/$module_name/store', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'store']);
    Route::get('/$module_name/edit/{id}', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'edit']);
    Route::post('/$module_name/update', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'update']);
    Route::post('/$module_name/delete/{id}', [\App\Http\Controllers\Backend\\{$module_name}Controller::class, 'delete']);
    EOD;

            // Append the new routes to the file
            file_put_contents($routePath, $routeContent . $routes);
        }
    }
    public function updatemodule(Request $request)
    {
        $request->validate([
            'module_name' => 'required',
        ]);

        $moduledit = modules::find($request->id);

        if (!$moduledit) {
            return redirect()->back()->withErrors(['error' => 'Module not found']);
        }

        $moduledit->module_name = $request->module_name;
        $moduledit->created_at = $request->created_at;
        $moduledit->updated_at = now();


        $moduledit->save();

        return redirect('/module')->with('success', 'Module updated successfully');
    }
    public function restoremodule($id)
    {
        $modulesdata = modules::find($id);

        $menuData = Menus::where('id', 4)->first();
        if (!$menuData) {
            return redirect()->back()->withErrors('Module not found.');
        }
        $modulesdata->deletestatus = 1;
        $modulesdata->save();
        $jsonoutput = json_decode($menuData->json_output, true);

        $this->recovermoduledata($jsonoutput, $id);

        $menuData->json_output = json_encode($jsonoutput);
        if ($menuData->save()) {
            return redirect()->back()->with('success', 'Module delete successful');
        } else {
            return redirect()->back()->withErrors('Module delete unsuccessful');
        }
    }
    public function destorymodule($id)
    {
        $modulesdata = modules::find($id);

        $menuData = Menus::where('id', 4)->first();
        if (!$menuData) {
            return redirect()->back()->withErrors('Module not found.');
        }
        $modulesdata->deletestatus = 0;
        $modulesdata->save();
        $jsonoutput = json_decode($menuData->json_output, true);

        $this->markModuleAsDeleted($jsonoutput, $id);

        $menuData->json_output = json_encode($jsonoutput);
        if ($menuData->save()) {
            return redirect()->back()->with('success', 'Module delete successful');
        } else {
            return redirect()->back()->withErrors('Module delete unsuccessful');
        }
    }

    private function markModuleAsDeleted(&$menuItems, $moduleId)
    {
        foreach ($menuItems as &$item) {
            if ($item['moduleid'] == $moduleId) {
                $item['deletestatus'] = '0';
                return;
            }

            if (!empty($item['children'])) {
                foreach ($item['children'] as &$child) {
                    if ($child['moduleid'] == $moduleId) {
                        $child['deletestatus'] = '0';
                        return;
                    }
                }
            }
        }
    }

    private function recovermoduledata(&$menuItems, $moduleId)
    {
        foreach ($menuItems as &$item) {
            if ($item['moduleid'] == $moduleId) {
                $item['deletestatus'] = '1';
                return;
            }

            if (!empty($item['children'])) {
                foreach ($item['children'] as &$child) {
                    if ($child['moduleid'] == $moduleId) {
                        $child['deletestatus'] = '1';
                        return;
                    }
                }
            }
        }
    }
    public function savePermissions(Request $request)
    {
        try {
            $moduleId = $request->module_id;
            $guardName = $request->guard_name;
            $permissions = $request->permissions;

            // Validate input
            if (!$permissions || !is_array($permissions)) {
                throw new \Exception('Permissions data is invalid or missing.');
            }

            foreach ($permissions as $permission) {
                if (isset($permission['id'])) {
                    // Update existing permission
                    DB::table('permissions')
                        ->where('id', $permission['id'])
                        ->update([
                            'name' => $permission['name'],
                            'module_id' => $moduleId,
                            'guard_name' => $guardName,
                            'updated_at' => now(),
                        ]);
                } else {
                    // Create new permission
                    DB::table('permissions')->insert([
                        'name' => $permission['name'],
                        'module_id' => $moduleId,
                        'guard_name' => $guardName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            // Log the detailed error
            Log::error('Error saving permissions: ' . $e->getMessage(), [
                'module_id' => $request->module_id,
                'guard_name' => $request->guard_name,
                'permissions' => $request->permissions,
            ]);

            // Return a detailed error response
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    public function ShowPermissions(Request $request)
    {
        $validated = $request->validate([
            'module_id' => 'required|integer',
        ]);
        $moduledata = permissions::where('module_id', $request->input('module_id'))->get();
        return response()->json(['status' => 'success', 'data' => $moduledata]);
    }

    public function deletePermission(Request $request)
    {
        $validated = $request->validate([
            'permission_id' => 'required|integer',
        ]);

        try {
            $permission = permissions::findOrFail($validated['permission_id']);
            $permission->delete();

            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting permission.',
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function getModuleRecycleAjax(Request $request)
    {
        try {
            $query = modules::select('id', 'module_name', 'parent_id', 'created_at', 'updated_at');
            $query = modules::where('deletestatus', 0);
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;

                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }



            return DataTables::of($query)

                ->addColumn('restore', function ($row) {
                    return '<form action="/restoremodule/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-primary" style="border: none; outline: none;"><i class="fas fa-trash-restore"></i></button>
                            </form>';
                })
                ->addColumn('time_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                })
                ->addColumn('time_update_ago', function ($row) {
                    return \Carbon\Carbon::parse($row->updated_at)->diffForHumans();
                })
                ->rawColumns(['restore', 'time_ago', 'time_update_ago'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

}
