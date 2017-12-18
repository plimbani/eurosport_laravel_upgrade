<?php

namespace Laraspace\Console\Commands;

use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Laraspace\Models\Tournament;
use Laraspace\Api\Repositories\TournamentRepository;

class generateTournamentSlugForExistingData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:generateTournamentSlugForExistingData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setting up tournaments slug.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TournamentRepository $tournamentRepoObj)
    {
        $this->tournamentRepoObj = $tournamentRepoObj;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tournaments = Tournament::all()->toArray();
        foreach ($tournaments as $tournament) {
            $slug = $this->tournamentRepoObj->generateSlug($tournament['name'].Carbon::createFromFormat('d/m/Y', $tournament['start_date'])->year,'');
            DB::table('tournaments')
                ->where('id', $tournament['id'])
                ->update([
                    'slug' => $slug
                ]);
        }
        $this->info('Script executed.');
    }
}
