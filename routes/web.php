    <?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\admin\AdminLogController;
use App\Http\Controllers\Admin\AdminLogController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ProductSubCategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class,'index'])->name('shop.home');
Route::get('/product/{slug}',[ShopController::class,'product'])->name('front.product');

// Route::get('/admin/login',[AdminLogController::class,'index'])->name('admin.login');
Route::get('/admin/login', [AdminLogController::class, 'index'])->name('admin.login');



Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {

        Route::get('/login', [AdminLogController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLogController::class, 'authenticate'])->name('admin.authenticate');
    });


    Route::group(['middleware' => 'admin.auth'], function () {

        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        //  Category routes

        Route::get('/categories', [CategoryController::class, 'index'])->name('Categories.index');

        Route::get('/Categories/create', [CategoryController::class, 'create'])->name('Categories.create');
        Route::post('/Categories/create', [CategoryController::class, 'store'])->name('Categories.store');

        //    temp-images-create
        Route::post('/upload-temp-image', [TempImagesController::class, 'create'])->name('temp-images-create');
        // Routes for editing and deleting categories
        Route::get('/Categories/{id}/edit', [CategoryController::class, 'edit'])->name('Categories.edit');
        Route::post('/Categories/{id}/update', [CategoryController::class, 'update'])->name('Categories.update');

        Route::delete('/Categories/{id}', [CategoryController::class, 'destroy'])->name('Categories.destroy');

        Route::get('/Sub-Categories/create', [SubCategoryController::class, 'create'])->name('Sub-Categories.create');
        Route::post('/Sub-Categories/create', [SubCategoryController::class, 'store'])->name('Sub-Categories.store');

        Route::get('/Sub-categories', [SubCategoryController::class, 'index'])->name('Sub-Categories.index');
        // Sub-Category Routes
        Route::get('/Sub-Categories/{id}/edit', [SubCategoryController::class, 'edit'])->name('Sub-Categories.edit');
        Route::post('/Sub-Categories/{id}/update', [SubCategoryController::class, 'update'])->name('Sub-Categories.update');
        Route::delete('/Sub-Categories/{id}', [SubCategoryController::class, 'destroy'])->name('Sub-Categories.destroy');

        ##Brands ROutes
        Route::get('/brands', [BrandsController::class, 'index'])->name('brands.index');
        Route::get('/brands/create', [BrandsController::class, 'create'])->name('brands.create');
        Route::post('/brands/store', [BrandsController::class, 'store'])->name('brands.store');
        Route::get('/brands/{id}/edit', [BrandsController::class, 'edit'])->name('brands.edit');
        Route::put('/brands/{id}/update', [BrandsController::class, 'update'])->name('brands.update');
        Route::delete('/brands/{id}', [BrandsController::class, 'destroy'])->name('brands.destroy');



        // Products-Routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('/product-subcategories', [ProductSubCategoryController::class, 'index'])->name('product-subcategories.index');
        Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');




        Route::get('/getSlug', function (Request $request) {
            $slug = '';
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                'slug' => $slug,
                'status' => true,
            ]);
        })->name('getSlug');
    });
});
