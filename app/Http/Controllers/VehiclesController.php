<?php

namespace App\Http\Controllers;

use App\Vehicle;
use App\Exports\VehiclesExport;
use Illuminate\Http\Request;
use App\Traits\PhpExcelFormater;
use Carbon\Carbon;


class VehiclesController extends Controller
{
    use PhpExcelFormater;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $vehicles = Vehicle::get();
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vehicles.create');
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
            'name' => 'required|unique:vehicles,name',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $vehicles = Vehicle::create($data);
        if ($vehicles) {
            $message = "You have successfully created";
            return redirect()->route('vehicles.create', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('vehicles.create', [])
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
       $vehicles = Vehicle::findOrFail($id);
        return view('vehicles.edit', compact('vehicles'));
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
            'name' => 'required|unique:vehicles,name,' . $id,
            'status' => 'required',
        ]);

        $vehicles = Vehicle::where('id', $id)->update($data);
        if ($vehicles) {
            $message = "You have successfully updated";
            return redirect()->route('vehicles.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('vehicles.index', [])
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
        $vehicles = Vehicle::destroy($id);
        if ($vehicles) {
            $message = "You have successfully deleted";
            return redirect()->route('vehicles.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('vehicles.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function uploadVehicle(Request $request) {
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
            $dataArray = [];

            foreach ($excelDataArray as $key => $value) {
                //dd($value) ;     
                $data = [];
                $data['name'] = $value['vehicle_number'];
                $data['type'] = $value['vehicle_type'];
                $data['model'] = $value['model'];
                $data['year'] = $value['year'];
                $data['regNo'] = $value['registration_no'];
                $data['capacity'] = $value['capacity'];
                $data['description'] = $value['description'];
                $data['status'] = $value['status'];

                //dd($products->toArray());
                //dd($data->toArray());
                 
                $existVehicleId = Vehicle::where('id', $value['id'])
                ->orWhere('name',$value['vehicle_number'])
                ->value('id');
                //if product exist then update
                if($existVehicleId){
                    Vehicle::where('id',$existVehicleId)->update($data);
                }else{
                    $dataArray[$key] = $data;
                    $dataArray[$key]['updated_at'] = Carbon::now();
                    $dataArray[$key]['created_at'] = Carbon::now();
                    
                    
                }
                
            }
            $vehicles = Vehicle::insert($dataArray);
            if ($vehicles) {
                $message = "Successfully Uploaded";
                return redirect()->route('vehicles.uploads')
                ->with('flash_success', $message);
            } else {
                $message = "Something wrong!! Please try again";
                return redirect()->route('vehicles.uploads')
                ->with('flash_danger', $message);
            }
            
        } else {
            //dd('Sarker');
            return view('vehicles.uploads');
        }
       
    }

    public function download() {
        return (new VehiclesExport())->download('Vehicles.xlsx');
    }
}
