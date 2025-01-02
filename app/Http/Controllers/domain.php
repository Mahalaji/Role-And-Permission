<?php

namespace App\Http\Controllers;
use App\Models\domains;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\str;

use Illuminate\Http\Request;

class domain extends Controller
{
    public function getDomainAjax(Request $request)
    {
        try {
            $query = domains::select('id', 'domainname', 'companyname', 'tomailid',  'created_at', 'updated_at');

            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        
           
        
            return DataTables::of($query)
                ->addColumn('edit', function ($row) {
                    return '<a href="/domain/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorydomain/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
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
                ->rawColumns(['edit', 'delete','time_ago','time_update_ago'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    function adddomain(Request $request) {

        $request->validate([
            'domainname' => 'required',
            'companyname' => 'required',
            'mailheader' => 'required',
            'mailfooter' => 'required',
            'port' => 'required',
            'authentication' => 'required',
            'username' => 'required',
            'password' => 'required',
            'tomailid' => 'required',
            'serveraddress' => 'required',

        ]);
    
        $domainadd = new domains();
        $domainadd->domainname = $request->domainname;
        $domainadd->companyname = $request->companyname;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->mailheader); 
        $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $domainadd->mailheader = $cleanDescription;
        $cleanDescriptions = preg_replace('/<\/?p>|<\/?strong>/', '', $request->mailfooter); 
        $cleanDescriptions = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $domainadd->mailfooter = $cleanDescriptions;
        $domainadd->port = $request->port;
        $domainadd->authentication = $request->authentication;
        $domainadd->username =$request->username;
        $domainadd->password =$request->password;
        $domainadd->tomailid =$request->tomailid;
        $domainadd->serveraddress =$request->serveraddress;

    
        $domainadd->save();
    
        if ($domainadd) {
            return redirect('/domain')->with('success', 'Domain added successfully!');
        } else {
            return back()->with('error', 'Failed to add the domain.');
        }
    }
    public function editdomain($id){
        $domain = domains::find($id); 
    
        if (!$domain) {
            return redirect()->back()->with('error', 'Domain not found');
        }
 
        if (!is_object($domain)) {
            return redirect()->back()->with('error', 'Invalid domain data');
        }
    
        return view('domain.edit', compact('domain'));
    }
    public function updatedomain(Request $request)
    {
        $request->validate([
            'domainname' => 'required',
            'companyname' => 'required',
            'mailheader' => 'required',
            'mailfooter' => 'required',
            'port' => 'required',
            'authentication' => 'required',
            'username' => 'required',
            'password' => 'required',
            'tomailid' => 'required',
            'serveraddress' => 'required',
        ]);
    
        $domainedit = domains::find($request->id);
    
        if (!$domainedit) {
            return redirect()->back()->withErrors(['error' => 'News not found']);
        }
    
        $domainedit->domainname = $request->domainname;
        $domainedit->companyname = $request->companyname;
        $cleanDescription = preg_replace('/<\/?p>|<\/?strong>/', '', $request->mailheader); 
        $cleanDescription = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $domainedit->mailheader = $cleanDescription;
        $cleanDescriptions = preg_replace('/<\/?p>|<\/?strong>/', '', $request->mailfooter); 
        $cleanDescriptions = str_replace(['&nbsp;', '&#39;'], [' ', "'"], $cleanDescription);
        $domainedit->mailfooter = $cleanDescriptions;
        $domainedit->port = $request->port;
        $domainedit->authentication = $request->authentication;
        $domainedit->username =$request->username;
        $domainedit->password =$request->password;
        $domainedit->tomailid =$request->tomailid;
        $domainedit->serveraddress =$request->serveraddress;
        $domainedit->updated_at = now();
        $domainedit->created_at =$request->created_at;
    
        $domainedit->save();
    
        return redirect('/domain')->with('success', 'Domain updated successfully');
    }
    public function destorydomain($id){
        $domain = domains::find($id); 
    
        if (!$domain) {
            return redirect()->back()->withErrors(['error' => 'Domain not found']);
        }
    
        $domain->delete();
    
        return redirect('/domain')->with('success', 'domain deleted successfully');
    }
}