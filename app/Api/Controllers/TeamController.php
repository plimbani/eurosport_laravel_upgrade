<?php

namespace Laraspace\Api\Controllers;

use Brotzka\DotenvEditor\DotenvEditor;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


// Need to Define Only Contracts
use Laraspace\Api\Contracts\TeamContract;

/**
 * Teams Resource Description.
 *
 * @Resource("Teams")
 *
 * @Author Knayak@aecordigital.com
 */
class TeamController extends BaseController
{
    public function __construct(TeamContract $teamObj)
    {
        $this->teamObj = $teamObj;
        $this->data = [];
    }

    /**
     * Show all Team Details.
     *
     * Get a JSON representation of all the Teams.
     *
     * @Get("/teams")
     * @Versions({"v1"})
     * @Response(200, body={"id": 10, "club_id": "foo"})
     */
    public function getTeams(Request $request)
    {
        // dd($request->all());
        return $this->teamObj->getTeams($request);
    }

    public function getClubs($id,Request $request)
    {
        return $this->teamObj->getClubs($id);
    }

    public function getClubTeams(Request $request)
    {
        return $this->teamObj->getClubTeams($request->all());
    }

    public function getAllTournamentTeams(Request $request)
    {
      return $this->teamObj->getAllTournamentTeams($request->all());
    }


    /**
     * Create  Torunament.
     *
     * Create New Tournament
     *
     * @Post("/team/create")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */

    public function createTeam(Request $request)
    {
        $teamData = $request->all();
        // dd($teamData);
        $file = $request->file('fileUpload');
        // $this->data['teamSize'] =  $teamData['teamSize'];
        $this->data['tournamentId'] = $teamData['tournamentId'];
       // $rows = \Excel::load($file->getRealPath(), null, 'ISO-8859-1')->get();
        //print_r($rows);
        //exit;
        \Excel::load($file->getRealPath(), function($reader) {
            // dd($reader->getTotalRowsOfFile() - 1);
            $this->data['totalSize']  = $reader->getTotalRowsOfFile() - 1;

            // $reader->limit($this->data['teamSize']);
            $reader->each(function($sheet) {
            // Loop through all rows
                // $sheet->each(function($row) {
                    // dd($sheet);
              $sheet->tournamentData = $this->data;
              $this->teamObj->create($sheet);

                // });
            });
        }, 'ISO-8859-1');
        // if($this->data['totalSize'] > $this->data['teamSize'] ){
        //     return ['bigFileSize' =>  true];
        // }else{
            return ['bigFileSize' =>  false];
        // }
    }
    public function assignTeam(Request $request) {
        // dd($request->all());
         $this->teamObj->assignTeams($request->all());
    }
    public function getAllTeamsGroup(Request $request) {
        $this->teamObj->getAllTeamsGroup($request->all());
    }

    // public function importTeamlist(){


    // }
    /**
     * Edit  Teams.
     *
     * @Post("/team/edit/{$id}")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function edit(Request $request)
    {
        return $this->teamObj->edit($request);
    }

    /**
     * Delete  Teams.
     *
     * @Post("/team/delete")
     *
     * @Versions({"v1"})
     * @Request("name=test", contentType="application/x-www-form-urlencoded")
     */
    public function delete(Request $request)
    {
        return $this->teamObj->delete($request);
    }
}
