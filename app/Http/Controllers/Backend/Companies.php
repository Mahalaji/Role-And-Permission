<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Models\company;
use App\Models\companyaddress;
use Illuminate\Http\Request;

class Companies extends Controller
{
    public function getCompanyAjax(Request $request)
    {
        try {
            $query = company::select('id', 'companyname', 'companytype', 'companyemail');
    
            if ($request->has('start_date') && $request->has('end_date')) {
                $startDate = $request->start_date;
                $endDate = $request->end_date;
    
                if ($startDate && $endDate) {
                    $query->whereBetween('Date', [$startDate, $endDate]);
                }
            }
    
            return DataTables::of($query)
            ->addColumn('address', function ($row) {
                return '<button data-company-id="' . $row->id . '" 
                                class="btn btn-sm btn-primary view-address-btn">
                            View/Edit Address
                        </button>';
            })
                ->addColumn('edit', function ($row) {
                    return '<a href="/company/edit/' . $row->id . '" class="btn btn-sm btn-primary"style="color:black"><i class="fas fa-edit"></i></a>';
                })
                ->addColumn('delete', function ($row) {
                    return '<form action="/destorycompany/' . $row->id . '" method="POST" onsubmit="return confirm(\'Are you sure?\');">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger" style="border: none; outline: none;"><i class="fas fa-trash"></i></button>
                            </form>';
                })
                ->rawColumns(['address','edit', 'delete'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
    function createcompany(Request $request) {
        $request->validate([
            'companyname' => 'required',
            'companytype' => 'required',
            'companyemail' => 'required',
        ]);
    
        $companyadd = new company();
        $companyadd->companyname = $request->companyname;
        $companyadd->companytype = $request->companytype;
        $companyadd->companyemail = $request->companyemail;
    
        $companyadd->save();
    
        if ($companyadd) {
            return redirect('/company')->with('success', 'Company added successfully!');
        } else {
            return back()->with('error', 'Failed to add the company.');
        }
    }
    public function editcompany($id){
        $company = company::find($id); 
    
        if (!$company) {
            return redirect()->back()->with('error', 'Company not found');
        }
 
        if (!is_object($company)) {
            return redirect()->back()->with('error', 'Invalid company data');
        }
    
        return view('Backend.company.edit', compact('company'));
    }
    public function updatecompany(Request $request)
    {
        $request->validate([
            'companyname' => 'required',
            'companytype' => 'required',
            'companyemail' => 'required',
        ]);
    
        $companyedit = company::find($request->id);
    
        if (!$companyedit) {
            return redirect()->back()->withErrors(['error' => 'Company not found']);
        }
    
        $companyedit->companyname = $request->companyname;
        $companyedit->companytype = $request->companytype;
        $companyedit->companyemail = $request->companyemail;

        $companyedit->save();
    
        return redirect('/company')->with('success', 'Company updated successfully');
    }
    public function destorycompany($id){
        $company = company::find($id); 
    
        if (!$company) {
            return redirect()->back()->withErrors(['error' => 'Company not found']);
        }
    
        $company->delete();
    
        return redirect('/company')->with('success', 'Company deleted successfully');
    }
    public function getCompanyaddress(Request $request)
    {
        try {
            $addresses = companyaddress::where('company_id', $request->company_id)->get();
            return response()->json(['status' => 'success', 'data' => $addresses]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
    public function deleteaddress(Request $request)
{
    try {
        $address = companyaddress::find($request->address_id);
        if (!$address) {
            return response()->json(['status' => 'error', 'message' => 'Address not found']);
        }

        $address->delete(); 

        return response()->json(['status' => 'success', 'message' => 'Address deleted successfully']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
public function saveCompanyAddress(Request $request)
{
    try {
        $company_id = $request->company_id;
        $addresses = $request->input('address', []);
        $mobiles = $request->input('mobile', []);
        $latitudes = $request->input('latitude', []);
        $longitudes = $request->input('longitude', []);

        if (
            count($addresses) !== count($mobiles) ||
            count($addresses) !== count($latitudes) ||
            count($addresses) !== count($longitudes)
        ) {
            return response()->json(['status' => 'error', 'message' => 'Input data mismatch']);
        }

        foreach ($addresses as $index => $address) {
            $existingAddress = companyaddress::where([
                'company_id' => $company_id,
                'address' => $address,
                'mobile' => $mobiles[$index],
                'latitude' => $latitudes[$index],
                'longitude' => $longitudes[$index]
            ])->first();

            if ($existingAddress) {
                continue;
            }

            companyaddress::create([
                'company_id' => $company_id,
                'address' => $address,
                'mobile' => $mobiles[$index],
                'latitude' => $latitudes[$index],
                'longitude' => $longitudes[$index]
            ]);
        }

        return response()->json(['status' => 'success']);
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
}
