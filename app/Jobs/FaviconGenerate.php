<?php

namespace Laraspace\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Laraspace\Services\FaviconAPI\Client\HttpClient;
use Storage;

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
        $s3ImagePath = getenv('S3_URL').'/assets/img/website_tournament_logo/'.$imageName;
        $disk = Storage::disk('s3');

        $request = $this->getRequest($s3ImagePath);
        $client = new HttpClient();
        $response = $client->post('/favicon', [], $request);

        $response = json_decode($response, true);
        $favicons = $response['favicon_generation_result']['favicon']['files_urls'];

        foreach ($favicons as $favicon) {
            $file_name = basename($favicon);
            $disk->put(
                $this->s3Path.'/'.$this->tournamentId.'/'.$file_name,
                file_get_contents($favicon),
                'public'
            );
        }
    }

    public function getRequest($imageUrl)
    {
        return [
            'favicon_generation' => [
                'api_key' => '29d7b88affd3d41dece2dab8ee4fa70b758be3f3',
                'master_picture' => [
                    'type' => 'url',
                    'url' => $imageUrl,
                ],
                'files_location' => [
                    'type' => 'path',
                    'path' => '/',
                ],
                'favicon_design' => [
                    'desktop_browser' => [
                    ],
                    'ios' => [
                        'picture_aspect' => 'background_and_margin',
                        'margin' => '4',
                        'background_color' => '#FFFFFF',
                        'startup_image' => [
                            'master_picture' => [
                                'type' => 'url',
                                'url' => $imageUrl,
                            ],
                            'background_color' => '#FFFFFF',
                        ],
                        'assets' => [
                            'ios6_and_prior_icons' => false,
                            'ios7_and_later_icons' => true,
                            'precomposed_icons' => false,
                            'declare_only_default_icon' => true,
                        ],
                    ],
                    'windows' => [
                        'picture_aspect' => 'white_silhouette',
                        'background_color' => '#654321',
                        'assets' => [
                            'windows_80_ie_10_tile' => true,
                            'windows_10_ie_11_edge_tiles' => [
                                'small' => false,
                                'medium' => true,
                                'big' => true,
                                'rectangle' => false,
                            ],
                        ],
                    ],
                    'firefox_app' => [
                        'picture_aspect' => 'circle',
                        'keep_picture_in_circle' => 'true',
                        'circle_inner_margin' => '5',
                        'background_color' => '#FFFFFF',
                        'manifest' => [
                            'app_name' => 'Euro-Sportring',
                            'app_description' => 'Euro-Sportring',
                            'developer_name' => '',
                            'developer_url' => '',
                        ],
                    ],
                    'android_chrome' => [
                        'picture_aspect' => 'shadow',
                        'manifest' => [
                            'name' => 'Euro-Sportring',
                            'display' => 'standalone',
                            'orientation' => 'portrait',
                            'start_url' => '/',
                            'existing_manifest' => '{"name": "Euro-Sportring"}',
                        ],
                        'assets' => [
                            'legacy_icon' => true,
                            'low_resolution_icons' => false,
                        ],
                        'theme_color' => '#FFFFFF',
                    ],
                    'safari_pinned_tab' => [
                        'picture_aspect' => 'black_and_white',
                        'threshold' => 60,
                        'theme_color' => '#FFFFFF',
                    ],
                    'coast' => [
                        'picture_aspect' => 'background_and_margin',
                        'background_color' => '#FFFFFF',
                        'margin' => '12%',
                    ],
                    'open_graph' => [
                        'picture_aspect' => 'background_and_margin',
                        'background_color' => '#FFFFFF',
                        'margin' => '12%',
                        'ratio' => '1.91:1',
                    ],
                    'yandex_browser' => [
                        'background_color' => 'background_color',
                        'manifest' => [
                            'show_title' => true,
                            'version' => '1.0',
                        ],
                    ],
                ],
                'settings' => [
                    'compression' => '3',
                    'scaling_algorithm' => 'Mitchell',
                    'error_on_image_too_small' => true,
                    'readme_file' => true,
                    'html_code_file' => false,
                    'use_path_as_is' => false,
                ],
                'versioning' => [
                    'param_name' => 'ver',
                    'param_value' => '15Zd8',
                ],
            ],
        ];
    }
}
