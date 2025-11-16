<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Application;
use App\Models\ApplicationLog;
use App\Models\ApplicationDetail;
use App\Models\MdrInformation;
use App\Models\MdrAttendance;
use App\Models\Region;
use App\Models\Depot;
use App\Models\Month;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Exports\BrandWiseDfExport;
use App\Exports\MdrTADABillExport;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Intervention\Image\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HolidaysController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        dd('Komol-Index');
        

        return view('holidays.view', compact());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd('Komol-Create');
        //$regions = Region::pluck('name','id');
        $Months = Month::pluck('name', 'id');
        
        return view('holidays.create', compact('Months'));

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
        //dd($data);
        $request->validate([
            'DepotName' => 'required',
            'salary_date' => 'required',
            'month_name' => 'required',
            'year' => 'required',
            //'working_days' => 'required',
            //'status' => 'required',
        ]);

        //$Region_id  = Region::where('name', $data['region_name'])->value('id');
        $Depot_id  = Depot::where('name', $data['DepotName'])->value('id');
        $Month_id   = Month::where('name', $data['month_name'])->value('id');
        //dd($Depot_id);

        $userInfo = auth()->user('id');
        $User_ID  = $userInfo['id'];
        //dd($User_ID);

         $data = $request->except('_token');
         $Test_EntryArray = [];
         //dd($data);
            if(!empty($data)){
                
                for ($i=0; $i < count($data['id']); ++$i) 
                {
                    if(($data['working_days'][$i]) != Null){
                        $MDRInformationQry = MdrInformation::where('id', $data['id'][$i])
                        ->first();
                        $MDR_EffectiveDate = MdrInformation::select('effectivedate')->where('id', $data['id'][$i])
                        ->first();

                        //dd($MDR_EffectiveDate);
                        $Test_Entry = [];

                        $Test_Entry['mdr_id']   = $data['id'][$i];
                        $Test_Entry['employee_id']   = $MDRInformationQry['employee_id'];
                        $Test_Entry['user_id']   = $User_ID;
                        $Test_Entry['depot_id']   = $MDRInformationQry['depot_id'];
                        $Test_Entry['region_id']   = $MDRInformationQry['region_id'];
                        $Test_Entry['distributor_id']   = $MDRInformationQry['distributor_id'];
                        $Test_Entry['month_days']   = $data['month_days'][$i];
                        $Test_Entry['authorized_leave']   = $data['authorized_leave'][$i];
                        $Test_Entry['unauthorized_leave']   = $data['unauthorized_leave'][$i];
                        $Test_Entry['weekly_holiday']   = $data['weekly_holiday'][$i];
                        $Test_Entry['govt_holiday']   = $data['govt_holiday'][$i];
                        $Test_Entry['eid_duty']   = $data['eid_duty'][$i];
                        $Test_Entry['working_days']   = $data['working_days'][$i];
                        $Test_Entry['travelling_allowance']   = $data['travelling_allowance'][$i];
                        $Test_Entry['dearness_allowance']   = $data['dearness_allowance'][$i];
                        $Test_Entry['mobile_bill']   = $data['mobile_bill'][$i];
                        $Test_Entry['salary']   = $data['salary'][$i];
                        $Test_Entry['weekly_holiday_bill']   = $data['weekly_holiday_bill'][$i];
                        $Test_Entry['govt_holiday_bill']   = $data['govt_holiday_bill'][$i];
                        $Test_Entry['eid_duty_bill']   = $data['eid_duty_bill'][$i];
                        $Test_Entry['gross_salary']   = $data['gross_salary'][$i];
                        $Test_Entry['salary_date']   = $data['salary_date'];
                        $Test_Entry['year']   = $data['year'];
                        $Test_Entry['month_id']   = $Month_id;
                        //$Test_Entry['status']   = $data['status'][$i];
                        $Test_Entry['created_at']   = \Carbon\Carbon::now();
                        $Test_Entry['updated_at']   = \Carbon\Carbon::now();
                        

                        //dd($data['id']);
                        $MDRAttendance = MdrAttendance::where('mdr_id', $data['id'][$i])
                        ->where('month_id', $Month_id)
                        ->where('year', $data['year'])
                        ->value('id');

                        if($MDRAttendance){
                            MdrAttendance::where(['mdr_id'=> $data['id'][$i]])
                            ->where('month_id', $Month_id)
                            ->where('year', $data['year'])
                            ->update($Test_Entry);

                            //MdrInformation::where(['id'=> $data['id'][$i]])
                            //->update(['status' => $data['status'][$i],
                            //          'updated_at' => \Carbon\Carbon::now()
                            //      ]);
                            
                        }else{
                                                    
                            $Test_EntryArray[$i] = $Test_Entry;

                                                         
                        }

                    }

                } 
                $mdrAttendanceInsert = MdrAttendance::insert($Test_EntryArray);
                if ($mdrAttendanceInsert) {
                    $message = "You have successfully Inserted";
                    return redirect()->route('mdrattendances.create', [])
                        ->with('flash_success', $message);

                } else {
                    $message = "Something wrong!! Please try again";
                    return redirect()->route('mdrattendances.create', [])
                        ->with('flash_danger', $message);
                }          
            }else {
                $message = "Something wrong!! Please try again";
                return redirect()->route('mdrattendances.create', [])
                    ->with('flash_danger', $message);
            } 
            

            

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function download(Request $request) 
    {
        dd($request);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id)
    {
         dd('Komol');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        dd('Komol');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
