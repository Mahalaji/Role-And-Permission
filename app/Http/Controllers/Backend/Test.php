<?php
    
    namespace App\Http\Controllers\Backend;
    
    use App\Http\Controllers\Controller;
    use App\Models\Tests;
    use Illuminate\Http\Request;
    
    class Test extends Controller
    {
        public function index()
        {
            $data = Tests::all();
            return view('Backend.pages.index', compact('data'));
        }
    
        public function create()
        {
            return view('Backend.pages.create');
        }
    
        public function store(Request $request)
        {
            Tests::create($request->all());
            return redirect()->route('pages.index')->with('success', 'Test created successfully.');
        }
    
        public function edit($id)
        {
            $item = Tests::findOrFail($id);
            return view('Backend.pages.edit', compact('item'));
        }
    
        public function update(Request $request, $id)
        {
            $item = Tests::findOrFail($id);
            $item->update($request->all());
            return redirect()->route('pages.index')->with('success', 'Test updated successfully.');
        }
    
        public function destroy($id)
        {
            Tests::destroy($id);
            return redirect()->route('pages.index')->with('success', 'Test deleted successfully.');
        }
    }