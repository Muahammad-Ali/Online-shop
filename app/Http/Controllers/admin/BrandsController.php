<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
    public function index(Request $request)
    {
        $brands = Brand::latest('id');

        if ($request->get('keywords')) {


        $brands = $brands->where('name','like','%'. $request->keyword .'%');

        }

        $brands = $brands->paginate(10);
        return view('Admin.brands.list', compact('brands'));

    }
    public function create()
    {
        return view("Admin.brands.create");
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
        ]);

        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status = $request->status;
            $brand->save();

            // Redirect to the index page with a success message
            return redirect()->route('brands.index')->with('success', 'Brand created successfully');
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }



    // Other methods in the controller...

public function edit($id)
{
    $brand = Brand::findOrFail($id);
    return view('Admin.brands.edit', compact('brand'));
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:brands,slug,' . $id,
    ]);

    if ($validator->passes()) {
        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->status = $request->status;
        $brand->save();

        // Redirect back to the list page with a success message
        return response()->json(['status' => true, 'message' => 'Brand created successfully']);
    } else {
        return response()->json(['status' => false, 'errors' => $validator->errors()]);
    }
}


public function destroy($id)
{
    $brand = Brand::findOrFail($id);
    $brand->delete();

    return response()->json([
        'status' => true,
        'message' => 'Brand deleted successfully',
    ]);
}



}
