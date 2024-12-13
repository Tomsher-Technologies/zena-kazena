<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageResizeAndDownload
{
    /**
     * Download and resize product image.
     *
     * @param string $product_type
     * @param \Illuminate\Http\UploadedFile $imageUrl
     * @param string $sku
     * @param bool $mainImage
     * @param int $count
     * @param bool $update
     * @return string|null
     */
    public function downloadAndResizeImage($product_type, $imageUrl, $sku, $mainImage = false, $count = 1, $update = false)
    {
        $data_url = '';

        try {
            $ext = $imageUrl->getClientOriginalExtension();
            
            if($product_type == 'main_product'){
                $path = 'products/' . Carbon::now()->year . '/' . Carbon::now()->format('m') . '/' . $sku . '/main/';
            } else {
                $path = 'products/' . Carbon::now()->year . '/' . Carbon::now()->format('m') . '/' . $sku . '/';
            }
            
            // Generate the main image filename
            if ($mainImage) {
                $filename = $path . $sku . '.' . $ext;
            } else {
                $n = $sku . '_gallery_' .  $count;
                $filename = $path . $n . '.' . $ext;
            }

            // Download the image from the given URL
            $imageContents = file_get_contents($imageUrl);

            // Save the original image in the storage folder
            Storage::disk('public')->put($filename, $imageContents);
            $data_url = Storage::url($filename);
            // Create an Intervention Image instance for the downloaded image
            // $image = Image::make($imageContents);
            // dd($image);

            // // Resize and save additional copies of the image with different sizes
            // $sizes = config('app.img_sizes'); // Define the sizes in your config/app.php file

            // foreach ($sizes as $size) {
            //     $resizedImage = $image->resize($size, null, function ($constraint) {
            //         $constraint->aspectRatio();
            //     });

            //     // Generate resized image filename
            //     if ($mainImage) {
            //         $filename2 = $path . $sku . "_{$size}px" . '.' . $ext;
            //     } else {
            //         $filename2 = $path . $sku . '_gallery_' . $count . "_{$size}px." . $ext;
            //     }

            //     // Save the resized image in the storage folder
            //     Storage::disk('public')->put($filename2, $resizedImage->encode('jpg'));
            // }
        } catch (\Exception $e) {
            // Handle the error appropriately
            // Optionally log the error or rethrow it
            return null;
        }

        return $data_url;
    }
}
