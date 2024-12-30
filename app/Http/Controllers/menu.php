<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\menus;

class menu extends Controller
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
                return '<a href="/news/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black">Add</a>';
            })
                ->addColumn('edit', function ($row) {
                    return '<a href="/news/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorynews/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
}
