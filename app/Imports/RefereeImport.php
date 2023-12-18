<?php

namespace App\Imports;

use App\Referee;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;


class RefereeImport implements ToModel ,WithValidation ,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    use Importable;

    public function model(array $row)
    {
        return new Referee([
            //
        ]);
    }
    public function rules(): array
    {
        return [
            '0' => [
                'required',
            ],
            '1' => [
                'required',
            ],
        ];
    }
}
