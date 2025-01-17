<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\menus;
use App\Models\modules;

class Menu extends Controller
{
    public function getMenuAjax(Request $request)
    {
        try {
            $query = menus::select('id', 'category', 'permission');
       
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
            ->addColumn('addmenu', function ($row) {
                return '<a href="/menu/add/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black">Add</a>';
            })
                ->addColumn('edit', function ($row) {
                    return '<a href="/menu/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorymenu/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                // ->addColumn('time_ago', function ($row) {
                //     return \Carbon\Carbon::parse($row->created_at)->diffForHumans();
                // })
                // ->addColumn('time_update_ago', function ($row) {
                //     return \Carbon\Carbon::parse($row->updated_at)->diffForHumans();
                // })
                ->rawColumns(['addmenu','edit', 'delete'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    public function Addmenubar($id)
    {
        
        $menu = menus::whereid($id)->first();
        $finalmenu_output = json_decode($menu, true);
        // dd($finalmenu_output);
        if ($finalmenu_output ) {
            return view('Backend.menu.create', compact('finalmenu_output'));
        } else {
            return redirect()->route('menulist')->with('error', 'Menu not found');
        }
       
      
        
    }

    function updatejsondata(Request $request)
{
    $blog = menus::find($request->id);

    if ($blog) {
        modules::truncate();

        $jsonDataArray = json_decode($request->input('json_output'), true);
// dd($jsonDataArray);
        if (is_array($jsonDataArray)) {
            // $lastElement = end($jsonDataArray); // Get the last element of the array
            // dd($lastElement);
            function saveModule($data, $parentId = 0)
            {
                foreach ($data as $item) {
                    $module = new modules();
                    $module->module_name = $item['text'] ?? 'Unnamed Module';
                    $module->parent_id = $parentId;
                    
                    $module->created_at = now();
                    $module->updated_at = now();
                    $module->save();

                    if (!empty($item['children'])) {
                        saveModule($item['children'], $module->id);
                    }
                }
            }

            saveModule($jsonDataArray);
        }

        $blog->json_output = json_encode($jsonDataArray);

        if ($blog->save()) {
            return redirect()->route('menu')->with('success', 'Blog and modules updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update the blog.');
        }
    } else {
        return redirect()->route('addmenubar')->with('error', 'Blog not found.');
    }
}
public function addmenu(Request $request){

    $request->validate([
        'category' => 'required',
        'permission' => 'required',
    ]);

    $menuadd = new menus();
    $menuadd->category = $request->category;
    $menuadd->permission = $request->permission;



    $menuadd->save();

    if ($menuadd) {
        return redirect('/menu')->with('success', 'Menu added successfully!');
    } else {
        return back()->with('error', 'Failed to add the menu.');
    }
}  
public function editmenu($id){
    $editmenu = menus::find($id); 
        
            if (!$editmenu) {
                return redirect()->back()->with('error', 'Menu not found');
            }
     
            if (!is_object($editmenu)) {
                return redirect()->back()->with('error', 'Invalid menu data');
            }
        
    
            return view('Backend.menu.edit', compact('editmenu'));
} 
public function updatemenu(Request $request){
    $request->validate([
        'category' => 'required',
        'permission' => 'required',
    ]);

    $menuedit = menus::find($request->id);

    if (!$menuedit) {
        return redirect()->back()->withErrors(['error' => 'Menu not found']);
    }

    $menuedit->category = $request->category;
    $menuedit->permission = $request->permission;
    $menuedit->created_at = $request->created_at;
    $menuedit->updated_at = now();


    $menuedit->save();

    return redirect('/menu')->with('success', 'Menu updated successfully');
}
public function destorymenu($id){
    $menu = menus::find($id); 

    if (!$menu) {
        return redirect()->back()->withErrors(['error' => 'Menu not found']);
    }

    $menu->delete();

    return redirect('/menu')->with('success', 'menu deleted successfully');
}
}
