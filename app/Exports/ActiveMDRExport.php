<?php
namespace App\Exports;
use App\MdrInformation;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;

class ActiveMDRExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents {
    use Exportable;

    protected $param;
    protected $sl_no = 0;

    public function __construct(int $param = 0) {
        $this->param = $param;
    }

    public function query() {
        //dd('Komol');

        $query = MdrInformation::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                'regions'=>function($q){
                    return $q->select('*');
                },
                'depots'=>function($q){
                    return $q->select('*');
                },
                
            ])
          
        ->select(   'mdr_informations.id',
                    'mdr_informations.mdr_idcard',
                    'mdr_informations.applicant_name',
                    'mdr_informations.applicant_mobile',
                    'mdr_informations.applicant_education',
                    'mdr_informations.effectivedate',
                    'mdr_informations.basic_salary',
                    'depots.name as DepotName',
                    'regions.name as RegionName',
                    'distributors.distributorName as DistName',
                    'distributors.dbcode as DistCode',
                    'mdr_informations.status'
                         
            )
            ->where('mdr_informations.status', 'active')
            ->join('distributors', 'distributors.id', '=', 'mdr_informations.distributor_id')
            ->join('depots', 'depots.id', '=', 'mdr_informations.depot_id')
            ->join('regions', 'regions.id', '=', 'mdr_informations.region_id');

            
                
            //dd($query);   
            return $query;
            

    }

    public function headings(): array
    {

        return [
            'ID',
            'MDR ID No',
            'MDR Name',
            'Distributor Name',
            'DB Code',
            'Depot Name',
            'Region Name',
            'Rocket No',
            'Salary',
            'Education',
            'Effective Date',
            'Status'
           
        ];
    }

    /**
     * @var object $invoice
     */
    public function map($employee): array
    {
       // $this->sl_no = $this->sl_no + 1;
        return [
            $employee->id,
            $employee->mdr_idcard,
            $employee->applicant_name,
            $employee->DistName,
            $employee->DistCode,
            $employee->DepotName,
            $employee->RegionName,
            $employee->applicant_mobile,
            $employee->basic_salary,
            $employee->applicant_education,
            $employee->effectivedate,
            $employee->status
             
        ];

    }

    /**
     * Description: Some coustom hook into events, The events will be activated by adding the WithEvents concern
     * @return array //return an array of events
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                 

                //inserts 1 new rows, right before row 1:
                //$event->sheet->getDelegate()->insertNewRowBefore(1, 1);

                //Set top row height:
                //$event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);

                //merge two or more cells together, to become one cell
                //$event->sheet->getDelegate()->mergeCells('A1:T1');

                //Set value to merge cells
                //$today = date("j F, Y");
                //Set value to merge cells
                //$event->sheet->getDelegate()->setCellValue("A1", "Dhaka Ice Cream Industries Ltd.\n Employee Lists.\n As On " . $today);

                //$cellRange = 'A2:T2';
                //$event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);

                //Style to merge cells
                $styleArray = [
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'FFA0A0A0',
                        ],
                        'endColor' => [
                            'argb' => 'FFFFFFFF',
                        ],
                    ],
                ];

                //apply style to merge cells
                //$event->sheet->getDelegate()->getStyle('A1:T1')->applyFromArray($styleArray);

                $styleArray = [
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'DDDDDDDD'],
                        ],
                    ],
                ];
                //apply style to Header cells
                $event->sheet->getDelegate()->getStyle('A1:T1')->applyFromArray($styleArray);

            },
        ];
    }
}

?>