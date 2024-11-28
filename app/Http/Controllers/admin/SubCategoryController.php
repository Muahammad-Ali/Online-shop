<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class SubCategoryController extends Controller
{


    public function index(Request $request)
    {
        $SubCategories = SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories','categories.id','sub_categories.category_id');

        if (!empty($request->get('keyword'))) {


            $SubCategories = $SubCategories
                ->where('sub_categories.name', 'like', '%' . $request->get('keyword') . '%');
        }
        $SubCategories = $SubCategories->paginate(10);

        return view('Admin.Sub_Category.list', compact('SubCategories'));
    }
    public function create()
    {
        $categories = Category::orderBy('name','ASC')->get();

        $data['categories'] = $categories;
        return view('Admin.Sub_Category.create', $data);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                'name' => 'required',
                'slug' => 'required|unique:sub_categories',
                'category' => 'required',
                'status' => 'required',

        ]);

        if ($validator->passes())
        {
          $subCategory  = new SubCategory();
          $subCategory->name = $request->name;
          $subCategory->slug = $request->slug;
          $subCategory->status = $request->status;
          $subCategory->showHome = $request->showHome;
          $subCategory->category_id = $request->category;
          $subCategory->save();

          Session::flash('success', 'Sub-Category created successfully');

          return response ([
            'status' => true,
            'message' => 'Category created successfully'
      ]);
        }else{
          return response ([
                'status' => false,
                'errors' => $validator->errors()
          ]);
        }
    }


    public function edit($id)
{
    $subCategory = SubCategory::findOrFail($id); // Find the sub-category by ID
    $categories = Category::orderBy('name', 'ASC')->get(); // Get all categories for dropdown

    return view('Admin.Sub_Category.edit', compact('subCategory', 'categories'));
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
        'slug' => 'required|unique:sub_categories,slug,' . $id, // Allow same slug for the current sub-category
        'category' => 'required',
        'status' => 'required',
    ]);

    if ($validator->passes()) {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->name = $request->name;
        $subCategory->slug = $request->slug;
        $subCategory->status = $request->status;
        $subCategory->showHome = $request->showHome;
        $subCategory->category_id = $request->category;
        $subCategory->save();

        // Store success message in session
        Session::flash('success', 'Sub-Category updated successfully');

        // Redirect to the list page
        return redirect()->route('Sub-Categories.index');
    } else {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }
}


public function destroy($id)
{
    $subCategory = SubCategory::findOrFail($id);
    $subCategory->delete();

    return response([
        'status' => true,
        'message' => 'Sub-Category deleted successfully'
    ]);
}

}
