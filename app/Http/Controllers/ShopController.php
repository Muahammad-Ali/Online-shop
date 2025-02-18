<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{

    public  function index(Request $request,$categorySlug = null, $subcategorySlug = null)
    {

        $categorySelected = '';
        $subCategorySelected = '';

        $brandsArray = [];

         if(!empty($request->get('brand'))){
              $brandsArray = explode(',',$request->get('brand'));
         }

      $categories =  Category::orderBy("name","ASC")
        ->with('sub_category')->where('status',1)->get();

       $brands =  Brand::orderBy('name','ASC')
       ->where('status',1)->get();

       $products = Product::where('status','1');
    //    Apply filters for shopping screen
           if(!empty($categorySlug)){
            $category = Category::where('slug',$categorySlug)->first();

            $products = $products->where('category_id',$category->id);
            $categorySelected = $category->id;

           }

           if(!empty($subcategorySlug)){
            $subCategory = SubCategory::where('slug',$subcategorySlug)->first();

            $products = $products->where('sub_category_id',$subCategory->id);
            $subCategorySelected = $category->id;
           }

           if(!empty($request->get('brand'))){
            $brandsArray = explode(',',$request->get('brand'));
            $products = $products->whereIn('brand_id',$brandsArray);
           }
           if ($request->get('price_max') != ''  && $request->get('price_min') != ''){
              if($request->get('price_max')){

              }
               $products = $products->whereBetween('price' ,[intval($request->get('price_min')),intval($request->get('price_max'))]);

           }
           $products = $products->orderBy('id','DESC');
           $products = $products->paginate(6);
           $data['categories'] = $categories;
           $data['brands'] = $brands;
           $data['products'] = $products;
           $data['categorySelected'] = $categorySelected;
           $data['subCategorySelected'] = $subCategorySelected;
           $data['brandsArray'] = $brandsArray;
           $data['priceMax'] = (intval($request->get('price_max')) == 0) ? 1000:$request->get('price_max');
           $data['priceMin'] = intval($request->get('price_min'));

           return view("frontend.shop",$data);
    }

    public function product($slug){
            //   echo $slug;
            $product = Product::where('slug',$slug)->with('product_images')->first();
            if($product == null){
                    abort(404);
            }
            $data['product'] = $product;

            return view('frontend.products',$data);
    }
}
