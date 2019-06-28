<?php

namespace Laraspace\Jobs;

use Storage;
use File;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Laraspace\Models\Website;
use Laraspace\Services\FaviconAPI\Client\HttpClient;

class FaviconGenerate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var local image uplod name
     */
    protected $imageName;

    /**
     * @var logo s3 path
     */
    protected $s3Path;

    /**
    * The tournament id.
    *
    * @var client
    */
    protected $tournamentId;

    public function __construct($imageName, $s3Path, $tournamentId)
    {
        $this->imageName = $imageName;
        $this->s3Path = $s3Path;
        $this->tournamentId = $tournamentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $imageName = $this->imageName;
        $s3ImagePath = $this->s3Path . $imageName;
        $disk = Storage::disk('s3');

        $request = json_encode(file_get_contents(base_path('resources/favicon_requests/favicongeneration.php')));
        $request = str_replace('{image_url}', $s3ImagePath, $request);

        $client = new HttpClient();
        $response = $client->post('/favicon', [], json_decode($request, true));

        $response = json_decode($response, true);
        $favicons = $response['favicon_generation_result']['favicon']['files_urls'];

        foreach($favicons as $favicon) {
            $file_name = basename($favicon);
            $disk->put(
                $this->s3Path . '/' . $this->tournamentId . '/' . $file_name,
                $favicon,
                'public'
            );
        }
    }
}
