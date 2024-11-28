<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::latest();

        if (!empty($request->get('keyword'))) {
            $categories = $categories
                ->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
        $categories = $categories->paginate(10);

        return view('Admin.Categories.list', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);
        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();
            //   save image
            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id . '.' . $ext;
                $sPath = public_path() . '/temp/' . $tempImage->name;
                $dPath = public_path() . '/uploads/category' . $newImageName;
                File::copy($sPath, $dPath);

                //    generate Images Thumbnails
                $dPath = public_path() . '/uploads/category/thumb' . $newImageName;
                $manager = new ImageManager(new Driver());

                $image = $manager->read($sPath);
                $image->cover(450, 600);
                $image->save($dPath);


                $category->image = $newImageName;
                $category->save();
            }
            Session::flash('success', 'Category saved successfully');




            return response()->json([
                'status' => true,
                'message' => "Category saved successfully",
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id)
    {

        $category = Category::findOrFail($id);
        return view('Admin.Categories.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,' . $id,
        ]);

        if ($validator->passes()) {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();


            if ($request->has('image_id')) {
                $tempImage = TempImage::find($request->image_id);
                if ($tempImage) {
                    $newImageName = $category->id . '.' . pathinfo($tempImage->name, PATHINFO_EXTENSION);
                    $sPath = public_path('/temp/') . $tempImage->name;
                    $dPath = public_path('/uploads/category/') . $newImageName;

                    // Copy and resize image
                    File::copy($sPath, $dPath);

                    $dPath =  public_path('/uploads/category/thumb/') . $newImageName;

                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($sPath);
                    $image->cover(450, 600);
                    $image->save($dPath);


                    $category->image = $newImageName;
                    $category->save();
                }
            }

            $category->save();
            return response()->json(['status' => true, 'message' => 'Category updated successfully']);
        }

        return response()->json(['status' => false, 'errors' => $validator->errors()]);
    }


    public function destroy($id)
     {
        $category = Category::findOrFail($id);

        // Delete associated image files
        if ($category->image) {
            $imagePath = public_path('/uploads/category/') . $category->image;
            $thumbPath = public_path('/uploads/category/thumb/') . $category->image;
            if (File::exists($imagePath)) File::delete($imagePath);
            if (File::exists($thumbPath)) File::delete($thumbPath);
        }

        $category->delete();
        return response()->json(['status' => true, 'message' => 'Category deleted successfully']);
    }
}
