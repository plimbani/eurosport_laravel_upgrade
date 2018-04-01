<?php

namespace Laraspace\Jobs;

use Storage;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intervention\Image\ImageManager;
use Laraspace\Models\Website;

class ImageConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var local image uplod name
     */
    protected $imageName;
    /**
     * @var local image tempImagePath path
     */
    protected $tempImagePath;
    /**
     * @var logo s3 path
     */
    protected $s3Path;

    /**
     * @var tournament logo conversions array
     */
    protected $conversions;

    public function __construct($imageName, $tempImagePath, $s3Path, $conversions)
    {
        $this->imageName = $imageName;
        $this->tempImagePath = $tempImagePath;
        $this->s3Path = $s3Path;
        $this->conversions = $conversions;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageManager $imageManager)
    {
        $imageName = $this->imageName;
        $imageWithPath = $this->tempImagePath.$imageName;
        $disk = Storage::disk('s3');

        foreach ($this->conversions as $key => $value) {
            $image = null;
            if(isset($value['width']) && isset($value['height'])) {
                $image = $this->cropAndResize($imageWithPath, $value['width'], $value['height'], $imageManager);
            } else if(isset($value['width']) && !isset($value['height'])) {
                $image = $this->resizeImageProportionally($imageWithPath, $value['width'], null, $imageManager);
            } else if(!isset($value['width']) && isset($value['height'])) {
                $image = $this->resizeImageProportionally($imageWithPath, null, $value['height'], $imageManager);
            } else {
                continue;
            }

            $disk->put(
                $this->s3Path.$key.'/'.$imageName,
                $image,
                'public'
            );
        }

        File::delete($imageWithPath);
    }

    /**
     * Crop & resize images
     * @return [string]
     */
    public function cropAndResize($path, $width, $height, $imageManager)
    {
        return (string) $imageManager->make($path)->fit($width, $height)->encode();
    }

    /**
     * Resize image proportionally
     * @return [string]
     */
    public function resizeImageProportionally($path, $width, $height, $imageManager)
    {
        // Resize the image to a width or height and constrain aspect ratio (auto width or height)
        return (string) $imageManager->make($path)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->encode();
    }
}
