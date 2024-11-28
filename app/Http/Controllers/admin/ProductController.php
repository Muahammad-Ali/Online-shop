<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{

     public function index(Request $request)
     {
         $products = Product::latest('id')->with('product_images');

         if($request->get('keywords')!==""){
             $products = $products->where('title', 'like' , '%'.$request->keyword.'%' );
         }
          $products = $products  ->paginate();
        //  dd($products);
           $data['products'] = $products;

         return view("Admin.Products.list", $data);
     }

    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view("Admin.Products.create", $data);
    }


    public function store(Request $request)
    {
        //   dd($request->image_array);
        //  exit();
        $rules = [
            "title" => "required",
            "slug" => "required|unique:products",
            "price" => "required|numeric",
            "sku" => "required|unique:products",
            "track_qty" => "required|in:Yes,No",
            "category" => "required|numeric",
            "is_featured" => "required|in:Yes,No",
        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator =  Validator::make($request->all(), $rules);

        if ($validator->passes()) {
            $products = new Product;
            $products->title = $request->title;
            $products->slug = $request->slug;
            $products->description = $request->description;
            $products->price = $request->price;
            $products->compare_price = $request->compare_price;
            $products->sku = $request->sku;
            $products->barcode = $request->barcode;
            $products->track_qty = $request->track_qty;
            $products->qty = $request->qty;
            $products->status = $request->status;
            $products->category_id = $request->category;
            $products->sub_category_id = $request->sub_category;
            $products->brand_id = $request->brand;
            $products->is_featured = $request->is_featured;
            $products->save();

            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray);  //like jpg gif png etc

                    $productImage = new ProductImage();
                    $productImage->product_id = $products->id;
                    $productImage->image = 'NULL';
                    $productImage->save();


                    $imageName = $products->id . '-' . $productImage->id . '-' . time() . '.' . $ext;

                    $productImage->image =  $imageName;
                    $productImage->save();


                    // Generate products thumbnails

                    // large image
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/products/large/' . $imageName;
                    $manager = new ImageManager(new Driver());

                    $image = $manager->read($sourcePath);
                    $image->cover(300,300);
                    $image->save($destPath);

                    // small
                    $destPath = public_path() . '/uploads/products/small/' . $imageName;
                }
            }

            Session::flash('success', 'Product saved successfully');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'

            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),

            ]);
        }
    }




    public function edit($id)
{
    $product = Product::with('product_images')->findOrFail($id);
    $categories = Category::orderBy('name', 'ASC')->get();
    $brands = Brand::orderBy('name', 'ASC')->get();

    return view('Admin.Products.edit', compact('product', 'categories', 'brands'));
}
public function update(Request $request, $id)
{
    $rules = [
        "title" => "required",
        "slug" => "required|unique:products,slug,$id",
        "price" => "required|numeric",
        "sku" => "required|unique:products,sku,$id",
        "track_qty" => "required|in:Yes,No",
        "category" => "required|numeric",
        "is_featured" => "required|in:Yes,No",
    ];

    if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
        $rules['qty'] = 'required|numeric';
    }

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    // Fetch the product using the $id from route
    $product = Product::findOrFail($id);

    // Update the product with the form data
    $product->title = $request->title;
    $product->slug = $request->slug;
    $product->description = $request->description;
    $product->price = $request->price;
    $product->compare_price = $request->compare_price;
    $product->sku = $request->sku;
    $product->barcode = $request->barcode;
    $product->track_qty = $request->track_qty;
    $product->qty = $request->qty;
    $product->status = $request->status;
    $product->category_id = $request->category;
    $product->sub_category_id = $request->sub_category;
    $product->brand_id = $request->brand;
    $product->is_featured = $request->is_featured;

    // Save the updated product in the database
    $product->save();

    // Flash success message to session
    Session::flash('success', 'Product updated successfully');

    // Redirect back to the product list page
    return redirect()->route('products.index');
}



public function destroy($id)
{
    $product = Product::findOrFail($id);
    $product->delete();

    Session::flash('success', 'Product deleted successfully');
    return redirect()->route('products.index');
}

}
