<?php

namespace Laraspace\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Laraspace\Api\Repositories\TournamentRepository;

class DuplicateTournament implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $tournamentData;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tournamentData)
    {
        $this->tournamentData = $tournamentData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $tournamentRepoObj = app(TournamentRepository::class);
        return $tournamentRepoObj->queriesForDuplicateTournament($this->tournamentData);
    }
}
