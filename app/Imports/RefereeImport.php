<?php

namespace App\Imports;

use App\Referee;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class RefereeImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param  array  $row
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
