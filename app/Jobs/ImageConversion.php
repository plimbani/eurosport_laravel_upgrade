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
    public function handle(ImageManager $images)
    {
        $imageName = $this->imageName;
        $imageWithPath = $this->tempImagePath.$imageName;

        $disk = Storage::disk('s3');

        foreach ($this->conversions as $key => $value) {
            $disk->put(
                $this->s3Path.$key.'/'.$imageName,
                $this->formatMainImage($imageWithPath, $value['width'], $value['height'], $images),
                'public'
            );
        }
        
        File::delete($imageWithPath);
    }

    /**
     * [formatMainImage crop image in diffrent sizes]
     * @return [string]
     */
    public function formatMainImage($path, $width, $height, $images)
    {
        return (string) $images->make($path)->fit($width, $height)->encode();
    }
}
