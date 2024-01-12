<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Excel;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements  FromCollection, Responsable, WithMapping, WithProperties, WithStyles ,ShouldAutoSize
{
    
    use Exportable;

    private $writerType = Excel::XLSX;

   // private $fileName = 'Pitchmatchschedule.xlsx';

    /**
    * @return \Illuminate\Support\Collection
    */

     public function __construct($lableArray, $dataArray, $otherParams, $output = 'xlsx', $download = 'yes', $columnFormat = '')
    {
        $this->lableArray = $lableArray;
        $this->dataArray = $dataArray;
        $this->otherParams = $otherParams;
        $this->output = $output;
        $this->download ='yes';
    }
    public function properties(): array
    {
        return [
            'Title' => Str::slug($this->otherParams['sheetTitle']),
        ];
    }

    public function collection()
    {
        return collect([]);
    }
    public function styles(Worksheet $sheet)
    {
        $row_no=1;
        $sheet->setTitle(Str::slug($this->otherParams['sheetTitle']));

         // Apply style to each cell in the heading row individually
            foreach ($this->lableArray as $columnIndex => $label) {
                    $cellCoordinate = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($columnIndex + 1) . '1';
                    $sheet->getStyle($cellCoordinate)->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1f844c'],
                    ],
                    'font' => [
                        'color' => ['rgb' => 'ffffff'],
                        'bold' => true,
                        'name' => 'Arial',
                        'size' => 10,
                    ],
                ]);

            }
        $sheet->fromArray([$this->lableArray], null, 'A'. $row_no, false, false);       
        $row_no = 2;
         foreach ($this->dataArray as $data) {
            //dd($data);
                $sheet->fromArray([$data], null, 'A' . $row_no, false, false);
                 $sheet->getColumnDimension('A')->setAutoSize(true);  
                $row_no++;
            } 
            // dd($sheet); 
            return $sheet;


            if ($this->columnFormat != '') {
                $sheet->setColumnFormat($this->columnFormat);
            }

           

            if ($this->otherParams['boldLastRow']) {
                $sheet->getStyle($row_no - 1)->getFont()->setBold(true);
            }
            return $sheet;

    }  
     public function map($invoice): array
    {
       
    }  

}
