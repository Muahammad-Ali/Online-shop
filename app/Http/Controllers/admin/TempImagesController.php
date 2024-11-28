<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        // $image = $request->image;

        // if(!empty($image))
        // {
        //     $ext = $image->getClientOriginalExtension();
        //     $newName = time(). '.'.$ext;

        //     $tempImage = new TempImage();
        //     $tempImage->name = $newName;
        //     $tempImage->save();

        //     $image->move(public_path().'/temp',$newName);
        $image = $request->image;

        if(!empty($image)){
            $ext = $image->getClientOriginalExtension();
            $newName = time().'.'.$ext;
            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move( public_path().'/temp',$newName);



            // Generate thumbnail
            $manager = new ImageManager(new Driver());
            $sourcePath = public_path().'/temp/'.$newName;
            $destPath = public_path().'/uploads/category/thumb/'.$newName;
            $image = $manager->read($sourcePath);
            $image->cover(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => 'success',
                  'image_id' => $tempImage->id,
                  'ImagePath' =>asset('/uploads/category/thumb/'.$newName),
                'message' => 'Image uploaded successfully',
                'image' => $newName], 200);
        }
    }
}