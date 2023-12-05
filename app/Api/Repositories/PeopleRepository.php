<?php

namespace App\Api\Repositories;

use App\Models\Person;

class PeopleRepository
{
    public function create($data)
    {
        return Person::create($data);
    }

    public function delete($data)
    {
        return Person::find($data['id'])->delete();
    }

    public function edit($data, $peopleId)
    {
        return Person::where('id', $peopleId)->update($data);
    }
}
