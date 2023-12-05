<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\Organiser;
use App\Models\Page;
use App\Models\Photo;
use App\Models\Sponsor;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class removeDanglingImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:removeDanglingImages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To delete dangling images/documents.';

    /**
     * @var disk
     */
    protected $disk;

    /**
     * @var diskName
     */
    protected $diskName;

    /**
     * @var Tournament logo path
     */
    protected $websiteTournamentLogoPath;

    /**
     * @var Social sharing graphic path
     */
    protected $socialSharingGraphicPath;

    /**
     * @var Hero image path
     */
    protected $heroImagePath;

    /**
     * @var Welcome image path
     */
    protected $welcomeImagePath;

    /**
     * @var Organiser image path
     */
    protected $organiserImagePath;

    /**
     * @var Sponsor image path
     */
    protected $sponsorImagePath;

    /**
     * @var Photo path
     */
    protected $photoPath;

    /**
     * @var Document path
     */
    protected $documentPath;

    /**
     * @var Tournament logo conversions
     */
    protected $websiteTournamentLogoConversions;

    /**
     * @var Hero image conversions
     */
    protected $heroImageConversions;

    /**
     * @var Welcome image conversions
     */
    protected $welcomeImageConversions;

    /**
     * @var Organiser image conversions
     */
    protected $organiserImageConversions;

    /**
     * @var Sponsor image conversions
     */
    protected $sponsorImageConversions;

    /**
     * @var Photo conversions
     */
    protected $photoConversions;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->diskName = config('filesystems.disks.s3.driver');
        $this->disk = Storage::disk($this->diskName);
        $this->websiteTournamentLogoPath = config('wot.imagePath.website_tournament_logo');
        $this->socialSharingGraphicPath = config('wot.imagePath.social_sharing_graphic');
        $this->heroImagePath = config('wot.imagePath.hero_image');
        $this->welcomeImagePath = config('wot.imagePath.welcome_image');
        $this->organiserImagePath = config('wot.imagePath.organiser_logo');
        $this->sponsorImagePath = config('wot.imagePath.sponsor_logo');
        $this->photoPath = config('wot.imagePath.photo');
        $this->documentPath = config('wot.imagePath.document');
        $this->websiteTournamentLogoConversions = config('image-conversion.conversions.website_tournament_logo');
        $this->heroImageConversions = config('image-conversion.conversions.hero_image');
        $this->welcomeImageConversions = config('image-conversion.conversions.welcome_image');
        $this->organiserImageConversions = config('image-conversion.conversions.organiser_logo');
        $this->sponsorImageConversions = config('image-conversion.conversions.sponsor_logo');
        $this->photoConversions = config('image-conversion.conversions.photo');

    }

    /**
     * Destroy instance.
     *
     * @return void
     */
    public function __destruct()
    {
        unset($this->diskName);
        unset($this->disk);
        unset($this->websiteTournamentLogoPath);
        unset($this->socialSharingGraphicPath);
        unset($this->heroImagePath);
        unset($this->welcomeImagePath);
        unset($this->organiserImagePath);
        unset($this->sponsorImagePath);
        unset($this->photoPath);
        unset($this->documentPath);
        unset($this->websiteTournamentLogoConversions);
        unset($this->heroImageConversions);
        unset($this->welcomeImageConversions);
        unset($this->organiserImageConversions);
        unset($this->sponsorImageConversions);
        unset($this->photoConversions);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $allUnUsedFiles = [];
        // Process tournament logos
        $tournamentLogos = Website::all()->pluck('tournament_logo')->toArray();
        $unUsedTournamentLogos = $this->getUnusedImages($this->websiteTournamentLogoPath, $this->websiteTournamentLogoConversions, $tournamentLogos);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedTournamentLogos);

        // Process social sharing graphic
        $socialSharingGraphics = Website::all()->pluck('social_sharing_graphic')->toArray();
        $unUsedSocialSharingGraphics = $this->getUnusedImages($this->socialSharingGraphicPath, [], $socialSharingGraphics);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedSocialSharingGraphics);

        // Process hero images
        $heroImages = [];
        $pages = Page::where('page_name', 'home')->pluck('meta')->each(function ($meta, $key) use (&$heroImages) {
            if ($meta && isset($meta['hero_image']) && $meta['hero_image'] != null && $meta['hero_image'] != '') {
                $heroImages[] = $meta['hero_image'];
            }
        });
        $unUsedHeroImages = $this->getUnusedImages($this->heroImagePath, $this->heroImageConversions, $heroImages);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedHeroImages);

        // Process welcome images
        $welcomeImages = [];
        $pages = Page::where('page_name', 'home')->pluck('meta')->each(function ($meta, $key) use (&$welcomeImages) {
            if ($meta && isset($meta['welcome_image']) && $meta['welcome_image'] != null && $meta['welcome_image'] != '') {
                $welcomeImages[] = $meta['welcome_image'];
            }
        });
        $unUsedWelcomeImages = $this->getUnusedImages($this->welcomeImagePath, $this->welcomeImageConversions, $welcomeImages);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedWelcomeImages);

        // Process organiser images
        $organiserImages = Organiser::all()->pluck('logo')->toArray();
        $unUsedOrganisersImages = $this->getUnusedImages($this->organiserImagePath, $this->organiserImageConversions, $organiserImages);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedOrganisersImages);

        // Process sponsor images
        $sponsorImages = Sponsor::all()->pluck('logo')->toArray();
        $unUsedSponsorsImages = $this->getUnusedImages($this->sponsorImagePath, $this->sponsorImageConversions, $sponsorImages);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedSponsorsImages);

        // Process photos
        $photos = Photo::all()->pluck('image')->toArray();
        $unUsedPhotos = $this->getUnusedImages($this->photoPath, $this->photoConversions, $photos);
        $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedPhotos);

        // Process documents
        $allWebsites = Website::all()->pluck('id')->each(function ($websiteId) use (&$allUnUsedFiles) {
            $documents = Document::where('website_id', $websiteId)->pluck('file_name')->toArray();
            $unUsedDocuments = $this->getUnusedImages($this->documentPath.$websiteId.'/', [], $documents);
            $allUnUsedFiles = array_merge($allUnUsedFiles, $unUsedDocuments);
        });
        $this->disk->delete($allUnUsedFiles);
        $this->info('Script executed.');
    }

    /**
     * Get unused images from S3.
     *
     * @return array
     */
    public function getUnusedImages($s3FolderPath, $conversions, $dbFiles)
    {
        $unUsedFiles = [];
        $s3Files = $this->disk->files($s3FolderPath);
        foreach ($s3Files as $file) {
            $filePathinfo = pathinfo($file);
            if (! in_array($filePathinfo['basename'], $dbFiles)) {
                $unUsedFiles[] = $file;
                foreach ($conversions as $key => $value) {
                    $unUsedFiles[] = $s3FolderPath.$key.'/'.basename($file);
                }
            }
        }

        return $unUsedFiles;
    }
}
