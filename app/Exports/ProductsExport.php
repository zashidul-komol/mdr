<?php
namespace App\Exports;
use App\Product;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use \Maatwebsite\Excel\Sheet;

class ProductsExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithEvents {
    use Exportable;

    protected $param;
    protected $sl_no = 0;

    public function __construct(int $param = 0) {
        $this->param = $param;
    }
    //dd('komol');
    public function query() {

        $query = Product::with([
                'department' => function ($q) {
                    return $q->select('id', 'name');
                },
                'category' => function ($q) {
                    return $q->select('id', 'name');
                },
                'subcategory' => function ($q) {
                    return $q->select('id', 'name');
                },
                'section' => function ($q) {
                    return $q->select('id', 'name');
                },
            ])
           
            ->select(   'products.id',
                        'categories.name as CategoryName',
                        'subcategories.name as Subcategory',
                        'products.name',
                        'products.code',
                        'products.tags',
                        'products.specification',
                        'departments.name as DeptName',
                        'sections.name as SectionName',
                        'products.status'
                                             
            )
            ->join('departments', 'departments.id', '=', 'products.department_id')
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->join('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->join('sections', 'sections.id', '=', 'products.section_id');
                
               
            return $query;
            dd($query);

    }

    public function headings(): array
    {

        return [
            'SL No',
            'Category',
            'Sub Category',
            'Product Name',
            'Code',
            'Product Tags',
            'Specification',
            'Deptartment',
            'Section',
            'Status'
           
        ];
    }

    /**
     * @var object $invoice
     */
    public function map($product): array
    {
        $this->sl_no = $this->sl_no + 1;
        return [
            $this->sl_no,
            $product->CategoryName,
            $product->Subcategory,
            $product->name,
            $product->code,
            $product->tags,
            $product->specification,
            $product->DeptName,
            $product->SectionName,
            $product->status
             
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