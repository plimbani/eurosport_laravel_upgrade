<?php

namespace App\Imports;

use App\Models\Team;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
//use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TeamsImport implements ToModel, WithHeadingRow
{
    use Importable;

    public function model(array $row)
    {

        if (! array_filter($row)) {
            return null;
        }
        /* return new Team([

             'club' => $row[0],
             'team' => $row[1],

         ]);*/
    }

    public function rules(): array
    {
        /*return [
            'club' => [
                'required',
            ],
            'team' => [
                'required',
            ],
        ];*/
    }
    /**
    * @param  Collection  $collection
    */

    /* public function sheets(): array
     {
         return [
             0 => new FirstSheetImport()
         ];
     }

     public function getCsvSettings(): array
     {
         return [
             'input_encoding' => 'ISO-8859-1'
         ];
     }*/

}
