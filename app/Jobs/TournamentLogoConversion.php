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

class TournamentLogoConversion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var local image uplod name
     */
    protected $imagename;
    /**
     * @var local image destination path
     */
    protected $destination;
    /**
     * @var logo s3 path
     */
    protected $tournamentLogoPath;
    
    public function __construct($imagename, $destination, $tournamentLogoPath)
    {
        $this->imagename = $imagename;
        $this->destination = $destination;
        $this->tournamentLogoPath = $tournamentLogoPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageManager $images)
    {
        $imagename = $this->imagename;
        $imageWithPath = $this->destination.$imagename;

        $disk = Storage::disk('s3');

        $logoSizes = array_except(Website::$logoSizes, array('main'));
        foreach ($logoSizes as $key => $value) {
            $disk->put(
                $this->tournamentLogoPath.$key.'/'.$imagename,
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
