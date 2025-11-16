<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Designation;
use App\Models\OfficeLocation;
use App\Models\Region;
use App\Models\Depot;
use App\Models\Section;
use App\Exports\EmployeeExport;
use App\Traits\PhpExcelFormater;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    use PhpExcelFormater;
    /**

     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::with([
            'designation' => function ($q) {
                return $q->select('id', 'title');
            },
            'department'=>function($q){
                return $q->select('id', 'name');
            },
            'office_location'=>function($q){
                return $q->select('id', 'name');
            },
            'section'=>function($q){
                return $q->select('id', 'name');
            },
            'user'=>function($q){
                return $q->select('*');
            },
        ])
        ->where('status','active')
        ->get();
        //dd($employees->toArray());
       //$employees = Employee::get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::pluck('name','id');
        $designations = Designation::pluck('title','id');
        $regions = Region::pluck('name','id');
        $sections = Section::pluck('name','id');
        $officelocations = OfficeLocation::pluck('name','id');
        return view('employees.create', compact('departments','designations','officelocations', 'sections', 'regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required|unique:employees,name',
            'department_id' => 'required',
            'section_id' => 'required',
            'designation_id' => 'required',
            'officelocation_id' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $employees = Employee::create($data);
        if ($employees) {
            $message = "You have successfully created";
            return redirect()->route('employees.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('employees.index', [])
                ->with('flash_danger', $message);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departments = Department::pluck('name','id');
        $designations = Designation::pluck('title','id');
        $sections = Section::pluck('name','id');
        $officelocations = OfficeLocation::pluck('name','id');
        $Regions = Region::pluck('name','id');
        $Depots = Depot::pluck('name','id');
        $employees = Employee::findOrFail($id);
        return view('employees.edit', compact('employees','departments','designations','sections','officelocations', 'Regions', 'Depots' ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_method', '_token');
        $request->validate([
            'name' => 'required|unique:employees,name,' . $id,
            'status' => 'required',
        ]);

        $employees = Employee::where('id', $id)->update($data);
        if ($employees) {
            $message = "You have successfully updated";
            return redirect()->route('employees.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('employees.index', [])
                ->with('flash_warning', $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employees = Employee::destroy($id);
        if ($employees) {
            $message = "You have successfully deleted";
            return redirect()->route('employees.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('employees.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function uploadEmployee(Request $request) {
        ini_set('max_execution_time', 60000);
        /*
         file path must be absolute and related to local drive
         */
        //dd('Komol');
        if ($request->isMethod('post')) {
            $file = $request->file('file');
            
            $request->validate([
                'file' => 'required|mimes:xlsx|max:1024',
            ]);
            //dd($request);
            $filePath = $file->getRealPath();
            $excelDataArray = $this->dumptoarray($filePath);
            //dd('Sarker');
            //dd($excelDataArray);
            $departments = Department::pluck('name','id');
            $sections = Section::pluck('name','id');
            $designations = Designation::pluck('title','id');
            $officelocations = OfficeLocation::pluck('name','id');
            $regions = Region::pluck('name','id');
            $dataArray = [];

            foreach ($excelDataArray as $key => $value) {
                //dd($value) ;     
                $data = [];
                $data['designation_id'] = $designations->search(trim($value['designation']));
                //$data['polar_id'] = $value['polar_id'];
                $data['name'] = $value['name'];
                $data['mobile'] = $value['mobile'];
                $data['email'] = $value['email'];
                $data['department_id'] = $departments->search(trim(html_entity_decode($value['deptartment']))) ?: 0;
                $data['section_id'] = $sections->search(trim(html_entity_decode($value['section']))) ?: 0;
                $data['officelocation_id'] = $officelocations->search(trim(html_entity_decode($value['office_location']))) ?: 0;
                $data['region_id'] = $regions->search(trim(html_entity_decode($value['region']))) ?: 0;
                $data['status'] = $value['status'];

                //dd($products->toArray());
                //dd($data->toArray());
                 
                $existEmployeeId = Employee::where('id', $value['id'])
                ->value('id');
                //if product exist then update
                if($existEmployeeId){
                    Employee::where('id',$existEmployeeId)->update($data);
                }else{
                    $dataArray[$key] = $data;
                    //$dataArray[$key]['updated_at'] = Carbon::now();
                    //$dataArray[$key]['created_at'] = Carbon::now();
                    
                    
                }
                
            }
            $employees = Employee::insert($dataArray);
            if ($employees) {
                $message = "Successfully Uploaded";
                return redirect()->route('employees.uploads')
                ->with('flash_success', $message);
            } else {
                $message = "Something wrong!! Please try again";
                return redirect()->route('employees.uploads')
                ->with('flash_danger', $message);
            }
            
        } else {
            //dd('Sarker');
            return view('employees.uploads');
        }
       
    }

    public function download() {
        return (new EmployeeExport())->download('employees.xlsx');
    }
}
