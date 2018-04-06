<?php

namespace Laraspace\Console\Commands;

use Laraspace\Models\Page;
use Laraspace\Models\Photo;
use Laraspace\Models\Website;
use Laraspace\Models\Sponsor;
use Laraspace\Models\Document;
use Laraspace\Models\Organiser;
use Illuminate\Console\Command;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all tournament logos
        $tournamentLogos = Website::all()->pluck('tournament_logo')->toArray();
        
        // Get all social sharing graphic images
        $socialSharingGraphics = Website::all()->pluck('social_sharing_graphic')->toArray();
        
        // Get all hero images
        $pages = Page::all()->pluck('meta')->toArray();
        
        // Get all welcome images
        

        // Get all organisers images
        $organiserImages = Organiser::all()->pluck('logo')->toArray();

        // Get all sponsors images
        $sponsorImages = Sponsor::all()->pluck('logo')->toArray();

        // Get all photos
        $photos = Photo::all()->pluck('image')->toArray();

        // Get all documents
        $documnets = Document::all()->pluck('file_name')->toArray();
    }
}
