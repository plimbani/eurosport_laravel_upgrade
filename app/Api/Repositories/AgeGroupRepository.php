<?php

namespace Laraspace\Api\Repositories;

use Laraspace\Models\AgeGroup;
use Laraspace\Models\TournamentCompetationTemplates;

class AgeGroupRepository
{
    public function getAll()
    {
        return AgeGroup::get();
    }

    public function create($data)
    {
        return AgeGroup::create($data);
    }

    public function edit($data)
    {
        return AgeGroup::where('id', $data['id'])->update($data);
    }

    public function delete($data)
    {
        return AgeGroup::find($data['id'])->delete();
    }
    public function createCompeationFormat($data){
      // here first we save the Age Group

      $ageGroupData['name'] = $data['ageCategory_name'];
      $ageGroupId = AgeGroup::create($ageGroupData)->id;
      
      // here we save the tournament_competation_template      
      $tournamentCompeationTemplate['age_group_id'] = $ageGroupId;
      $tournamentCompeationTemplate['tournament_id'] = $data['touranament_id'];
      
      $data = TournamentCompetationTemplates::create($data);  
    }
}
