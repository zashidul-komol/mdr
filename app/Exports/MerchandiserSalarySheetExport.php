<?php
namespace App\Exports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\MdrInformation;
use App\Models\MerchandiserInformation;
use App\Models\MdrAttendance;

class MerchandiserSalarySheetExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents {
    use Exportable;

    protected $data;

    public function __construct($data) {
        $this->data = $data;
    }
        //dd($this->data['Month_ID']);
    //dd('Komol');

    public function query() {
        //dd($this->data['Depot_ID']);
        $Depot_IDs = $this->data['Depot_ID'];
        //dd($Depot_IDs);
        $query = MdrAttendance::with([
                    'merchandiser_informations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                    'depots'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->select(   'merchandiser_informations.merchandiser_idcard as MerchanID',
                            'merchandiser_informations.merchan_application_id',
                            'merchandiser_informations.applicant_name',
                            'merchandiser_informations.applicant_mobile',
                            'merchandiser_informations.joiningdate',
                            'merchandiser_informations.inactiveDate',
                            'merchandiser_informations.status',
                            'depots.name as DepotName',
                            'employees.name as OfficerName',
                            'mdr_attendances.month_days',
                            'mdr_attendances.authorized_leave',
                            'mdr_attendances.unauthorized_leave',
                            'mdr_attendances.weekly_holiday',
                            'mdr_attendances.govt_holiday',
                            'mdr_attendances.eid_duty',
                            'mdr_attendances.working_days',
                            'mdr_attendances.payable_days',
                            'mdr_attendances.salary',
                            'mdr_attendances.travelling_allowance',
                            'mdr_attendances.dearness_allowance',
                            'mdr_attendances.meeting_days',
                            'mdr_attendances.mobile_bill',
                            'mdr_attendances.weekly_holiday_bill',
                            'mdr_attendances.govt_holiday_bill',
                            'mdr_attendances.others_ta_bill',
                            'mdr_attendances.eid_duty_bill',
                            'mdr_attendances.gross_salary'

                    )
                ->where('month_id', $this->data['Month_ID'])
                ->where('year', '2026')
                ->join('merchandiser_informations', 'merchandiser_informations.id', '=', 'mdr_attendances.merchan_id')
                ->join('employees', 'employees.id', '=', 'merchandiser_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
                ->orderBy('depots.name', 'asc');

                if($Depot_IDs){
                    //dd($Depot_IDs);
                    $query = $query->whereIn('mdr_attendances.depot_id', $Depot_IDs);
                }
                //$query = $query->get();
                
            //dd($query);   
            return $query;
            

    }

    public function headings(): array
    {

        return [
            'Merchandiser ID',
            'Officer Name',
            'Merchandiser Name',
            'Depot Name',
            'Rocket Number',
            'Joining Date',
            'Inactive Date',
            'Status',
            'Month Days',
            'Working Days',
            'Meeting Days',
            'Payable Days',
            'Salary',
            'TA',
            'DA',
            'Mobile Bill',
            'Leave',
            'Absent',
            'Weekly Holiday',
            'Govt. Holiday',
            'EID Duty',
            'Weekly Holiday Bill',
            'Govt. Holiday Bill',
            'Others TA',
            'EID Duty Bill',
            'Gross Salary'


           
        ];
    }

    /**
     * @var object $invoice
     */
    public function map($mdr_attendances): array
    {
        //$this->sl_no = $this->sl_no + 1;
        return [
            $mdr_attendances->MerchanID,
            $mdr_attendances->OfficerName,
            $mdr_attendances->applicant_name,
            $mdr_attendances->DepotName,
            $mdr_attendances->applicant_mobile,
            $mdr_attendances->joiningdate,
            $mdr_attendances->inactiveDate,
            $mdr_attendances->status,
            $mdr_attendances->month_days,
            $mdr_attendances->working_days,
            $mdr_attendances->meeting_days,
            $mdr_attendances->payable_days,
            $mdr_attendances->salary,
            $mdr_attendances->travelling_allowance,
            $mdr_attendances->dearness_allowance,
            $mdr_attendances->mobile_bill,
            $mdr_attendances->authorized_leave,
            $mdr_attendances->unauthorized_leave,
            $mdr_attendances->weekly_holiday,
            $mdr_attendances->govt_holiday,
            $mdr_attendances->eid_duty,
            $mdr_attendances->weekly_holiday_bill, 
            $mdr_attendances->govt_holiday_bill, 
            $mdr_attendances->others_ta_bill,
            $mdr_attendances->eid_duty_bill, 
            $mdr_attendances->gross_salary
             
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