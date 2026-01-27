<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Application;
use App\Models\ApplicationLog;
use App\Models\ApplicationDetail;
use App\Models\MdrInformation;
use App\Models\MerchandiserInformation;
use App\Models\Attendance;
use App\Models\MdrAttendance;
use App\Models\MdrAttendanceLog;
use App\Models\ReportingSequenceDetail;
use App\Models\ReportingSequence;
use App\Models\TadaReportingSequenceDetail;
use App\Models\TadaReportingSequence;
use App\Models\Region;
use App\Models\Depot;
use App\Models\Month;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Exports\BrandWiseDfExport;
use App\Exports\MdrTADABillExport;
use App\Exports\MDRSalaryTopSheetExport;
use Intervention\Image\Facades\Image;
use Intervention\Image\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Mail\ReturnMail;
use App\Mail\ReqRaisedMail;
use App\Mail\DepotTADABillMail;
use App\Mail\TaDaBillApproveMail;
use App\Mail\ForwardMail;
use App\Mail\ApproveMail;
use App\Mail\ApproveMailAttendance;
use App\Mail\HoldMail;
use App\Mail\CancelMail;
use Illuminate\Support\Facades\Mail;

class MDRAttendancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
        //dd($user_id);
        $EmployeeID = User::where('id', $user_id)->pluck('employee_id');
        $RegionID = Employee::where('id', $EmployeeID)->pluck('region_id');
        $RegionRow = Region::where('id', $RegionID)->first();
        $RegionName = $RegionRow['name'];
        //dd($name);
        $TodayDate  = \Carbon\Carbon::now()->format('d-m-Y');

        //$date = \Carbon\Carbon::now()->format('d-m-Y');
        $date = explode("-", $TodayDate);
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
        
        $MonthRow = Month::where('id', $month)->first();
        $MonthName = $MonthRow['name'];
        $MonthID   = $MonthRow['id'];


        //dd($Employee_id);



            $MdrInformations = MdrAttendance::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                'mdrInformations'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->where('employee_id',$EmployeeID)
            ->where('year', $year)
            ->where('month_id', $MonthID)
            ->orderBy('distributor_id', 'ASC')
            ->get(); 

        //dd($MdrInformations->toArray());

        return view('mdrattendances.view', compact('MdrInformations', 'RegionName', 'TodayDate', 'MonthName', 'year'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
        //dd($user_id);
        $EmployeeID = User::where('id', $user_id)->pluck('employee_id');
        $RegionID = Employee::where('id', $EmployeeID)->pluck('region_id');
        $RegionRow = Region::where('id', $RegionID)->first();
        $RegionName = $RegionRow['name'];
        $DepotID = Employee::where('id', $EmployeeID)->pluck('depot_id');
        $DepotRow = Depot::where('id', $DepotID)->first();
        $DepotName = $DepotRow['name'];
        //dd($DepotName);
        $TodayDate  = \Carbon\Carbon::now()->format('d-m-Y');

        //$date = \Carbon\Carbon::now()->format('d-m-Y');
        $date = explode("-", $TodayDate);
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
        
        $MonthRow               = Month::where('id', $month)->first();
        $MonthName              = $MonthRow['name'];
        $MonthID                = $MonthRow['id'];
        $Month_Days             = $MonthRow['monthly_days'];
        $Holi_Days              = $MonthRow['holidays'];
        $Govt_Holidays          = $MonthRow['govt_holidays'];
        $month_start_date       = $MonthRow['month_start_date'];
        $month_end_date         = $MonthRow['month_end_date'];
        $Monthly_Total_Holidays = $Govt_Holidays ; 

        //dd($Monthly_Total_Holidays);



            $MdrInformations = MdrInformation::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->where('employee_id',$EmployeeID)
            ->where('basic_salary', '!=', Null)
            ->where('status', 'active')
            ->orderBy('distributor_id', 'ASC')
            ->get(); 

        //dd($MdrInformations->toArray());

        return view('mdrattendances.edit3', compact('MdrInformations', 'RegionName', 'TodayDate', 'MonthName', 'year', 'Month_Days', 'Monthly_Total_Holidays', 'DepotName', 'month_start_date', 'month_end_date'));

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
        $authUser  = auth()->user()->id ;
        $request->validate([
            'DepotName' => 'required',
            'salary_date' => 'required',
            'month_name' => 'required',
            'year' => 'required',

        ]);

        $Month_id   = Month::where('name', $data['month_name'])->value('id');
        $Year = $data['year'];

        //dd($Year);
        
        $usersInfo = User::with([
            'designation'=>function($q){
                return $q->select('id', 'title');
            },
            'department'=>function($q){
                return $q->select('id', 'name');
            },
            'section'=>function($q){
                return $q->select('id', 'name');
            },
        ])
        ->where('id',$authUser)
        ->get();

        $userInfo = auth()->user('id');
        $User_ID  = $userInfo['id'];
        //dd($usersInfo);
        
        $Employee_Info  = User::where('id', $User_ID)->get();
        
        $Employee_ID  = $Employee_Info[0]->employee_id;
        $Employee_Details = Employee::where('id', $Employee_ID)->get();
        
        $Depot_id  = $Employee_Details[0]->depot_id;
        $Region_id  = $Employee_Details[0]->region_id;

        $data = $request->except('_token');
        $Test_EntryArray = [];
        
            if(!empty($data)){

               if(TadaReportingSequence::where('user_id', $authUser)->exists()){
                    $user_data  = Auth::user();
                    $request->validate([
                        'details.*.product_id' => 'required',
                        'details.*.requsition_quantity' => 'required',
                        
                    ]);
                    
                    $reporting_sequence = TadaReportingSequenceDetail::where('user_id', auth()->user()->id)
                    ->where('sequence', '=', 1)
                    ->value('report_to');

        
                    $Attendance = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                ->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->value('id');
                    //dd($Employee_ID);

                    $Attendance_ID = $Attendance;
                    $Attendance_check = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                ->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->where('attendance_status', 'checked')
                                ->where('status', 'approved')
                                ->value('id');
                    //dd($Attendance_check);
                    if(empty($Attendance_check)){
                        //dd('Komol');
                        $requisition_data['user_id']    = $user_data->id;
                        $requisition_data['report_to']  = $reporting_sequence;
                        $requisition_data['sequence']   = 1;
                        $requisition_data['attendance_status'] = 'pending';
                        $requisition_data['date']   = Carbon::now();
                        $requisition_data['employee_id']  = $Employee_ID;
                        $requisition_data['month_id'] = $Month_id;
                        $requisition_data['year'] = $Year;
                        $requisition_data['depot_id']  = $Depot_id;
                        $requisition_data['region_id'] = $Region_id;
                        $requisition_data['status'] = 'pending';

                        if($Attendance){

                            $requisitionData = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                ->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->update([
                                    'report_to' => $reporting_sequence ,
                                    'attendance_status' => 'pending',
                                    'status' => 'pending',
                                    'updated_at' => Carbon::now(),
                                ]);
                            $Attendance_ID = $Attendance;

                        }else{

                           $requisitionData = Attendance::create($requisition_data);
                           $Attendance_ID = $requisitionData->id;
                        }
                        

                        //insert data in requisitionDetails table
                        
                            if ($requisitionData) {
                            
                                for ($i=0; $i < count($data['id']); ++$i) 
                                {
                                    //dd($data['id']);
                                    if(($data['working_days'][$i]) != Null){
                                        //dd('komol');
                                        $MDRInformationQry = MdrInformation::where('id', $data['id'][$i])
                                        ->first();
                                        $MDR_EffectiveDate = MdrInformation::select('effectivedate')->where('id', $data['id'][$i])
                                        ->first();

                                        //dd($MDR_EffectiveDate);
                                        $Test_Entry = [];

                                        $Test_Entry['attendance_id']   = $Attendance_ID;
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
                                        $Test_Entry['meeting_days']   = $data['meeting_days'][$i];
                                        $Test_Entry['others_ta_bill']   = $data['others_ta_bill'][$i];
                                        $Test_Entry['eid_duty']   = $data['eid_duty'][$i];
                                        $Test_Entry['working_days']   = $data['working_days'][$i];
                                        $Test_Entry['payable_days']   = $data['payable_days'][$i];
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
                                        $Test_Entry['status']   = 'active';
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

                                    $ReportTo_Mail   = User::where('id', $reporting_sequence)
                                        ->value('email');
                                    //dd($ReportTo_Mail);
                                    //$email['email']    = $ReportTo_Mail->email;   
                                    //$ReqRaiseMail->email;
                                    //dd($email);
                                    $requisition_log['attendance_id']  = $Attendance_ID;
                                    $requisition_log['user_id'] = auth()->user()->id;
                                    $requisition_log['action_name'] = 'Prepared By  ';
                                    $requisition_log['created_at']  = Carbon::now();
                                    $requisition_log['updated_at']  = Carbon::now();
                                
                                    $RequisitionLogs  = MdrAttendanceLog::insert($requisition_log);
                                        
                                    //$ReqRaiseMail    = $ReportTo_Mail['email'];
                                    //$admin_email     = ['mamun@polarbd.com','samir.paul@polarbd.com'];
                                    $admin_email     = $ReportTo_Mail;
                                    //$customer_email    = $ReportTo_Mail['email'];
                                    //dd($customer_email);

                                    //Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));
                                    
                                    $message = "You have successfully Inserted";
                                    return redirect()->route('mdrattendances.create', [])
                                        ->with('flash_success', $message);

                                } else {
                                    $message = "Something wrong!! Please try again-1";
                                    return redirect()->route('mdrattendances.create', [])
                                        ->with('flash_danger', $message);
                                } 

                            } else {
                                $message = "Something wrong!! Please try again-2";
                                return redirect()->route('mdrattendances.create', [])
                                    ->with('flash_danger', $message);
                            } 

                    }else{
                        $message = "This entry is already checked, so you cant edit or modify it.";
                        return redirect()->route('mdrattendances.create', [])
                            ->with('flash_danger', $message);

                    }

        
                        
                }else {
                    $message = "Your reporting sequence has not been created yet, please contact with Software Administrator";
                    return redirect()->route('mdrattendances.create', [])
                        ->with('flash_danger', $message);
                } 

            }else {
                $message = "Something wrong!! Please try again-4";
                return redirect()->route('mdrattendances.create', [])
                    ->with('flash_danger', $message);
            } 
            

            

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function inactiveMDRcreate()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
        //dd($user_id);
        $EmployeeID = User::where('id', $user_id)->pluck('employee_id');
        $RegionID = Employee::where('id', $EmployeeID)->pluck('region_id');
        $RegionRow = Region::where('id', $RegionID)->first();
        $RegionName = $RegionRow['name'];
        $DepotID = Employee::where('id', $EmployeeID)->pluck('depot_id');
        $DepotRow = Depot::where('id', $DepotID)->first();
        $DepotName = $DepotRow['name'];
        //dd($DepotName);
        $TodayDate  = \Carbon\Carbon::now()->format('d-m-Y');

        $CurDateTime    = Carbon::today();
        //dd ($CurDateTime);
        list($CurDate, $CurTime)=explode(' ', $CurDateTime);
        list($CurYear, $CurMonth, $CurDay)=explode('-', $CurDate);

        //$date = \Carbon\Carbon::now()->format('d-m-Y');
        $date = explode("-", $TodayDate);
        $day = $date[0];
        $month = $date[1];
        $year = $date[2];
        
        $MonthRow               = Month::where('id', $month)->first();
        $MonthName              = $MonthRow['name'];
        $MonthID                = $MonthRow['id'];
        $Month_Days             = $MonthRow['monthly_days'];
        $Holi_Days              = $MonthRow['holidays'];
        $Govt_Holidays          = $MonthRow['govt_holidays'];
        $month_start_date       = $MonthRow['month_start_date'];
        $month_end_date         = $MonthRow['month_end_date'];
        $Monthly_Total_Holidays = $Govt_Holidays ; 

        //dd($Monthly_Total_Holidays);



            $MdrInformations = MdrInformation::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->whereMonth('inactiveDate', '=', $CurMonth)
            ->whereYear('inactiveDate', '=', $CurYear)
            ->where('employee_id',$EmployeeID)
            //->where('depot_id',$DepotID)
            ->where('status', 'inactive')
            ->orderBy('distributor_id', 'ASC')
            ->get(); 

        //dd($MdrInformations->toArray());

        return view('mdrattendances.InactiveMDR', compact('MdrInformations', 'RegionName', 'TodayDate', 'MonthName', 'year', 'Month_Days', 'Monthly_Total_Holidays', 'DepotName', 'month_start_date', 'month_end_date'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function InactiveMDR(Request $request)
    {
        $data = $request->all();
        //dd($data);
        $authUser  = auth()->user()->id ;
        $request->validate([
            'DepotName' => 'required',
            //'salary_date' => 'required',
            'month_name' => 'required',
            //'year' => 'required',

        ]);

        $Month_id   = Month::where('name', $data['month_name'])->value('id');
        $Year = $data['year'];
        
        $usersInfo = User::with([
            'designation'=>function($q){
                return $q->select('id', 'title');
            },
            'department'=>function($q){
                return $q->select('id', 'name');
            },
            'section'=>function($q){
                return $q->select('id', 'name');
            },
        ])
        ->where('id',$authUser)
        ->get();

        $userInfo = auth()->user('id');
        $User_ID  = $userInfo['id'];
        //dd($usersInfo);
        
        $Employee_Info  = User::where('id', $User_ID)->get();
        
        $Employee_ID  = $Employee_Info[0]->employee_id;
        $Employee_Details = Employee::where('id', $Employee_ID)->get();
        
        $Depot_id  = $Employee_Details[0]->depot_id;
        $Region_id  = $Employee_Details[0]->region_id;

        $data = $request->except('_token');
        $Test_EntryArray = [];
        
            if(!empty($data)){

               if(TadaReportingSequence::where('user_id', $authUser)->exists()){
                    $user_data  = Auth::user();
                    $request->validate([
                        'details.*.product_id' => 'required',
                        'details.*.requsition_quantity' => 'required',
                        
                    ]);
                    
                    $reporting_sequence = TadaReportingSequenceDetail::where('user_id', auth()->user()->id)
                    ->where('sequence', '=', 1)
                    ->value('report_to');

        
                    $Attendance = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                ->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->value('id');
                    //dd($Attendance);

                    $Attendance_ID = $Attendance;
                    $Attendance_check = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                ->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->where('attendance_status', 'checked')
                                ->where('status', 'approved')
                                ->value('id');
                    //dd($Attendance_check);
                    if(empty($Attendance_check)){
                        //dd('Komol');
                        $requisition_data['user_id']    = $user_data->id;
                        $requisition_data['report_to']  = $reporting_sequence;
                        $requisition_data['sequence']   = 1;
                        $requisition_data['attendance_status'] = 'pending';
                        $requisition_data['date']   = Carbon::now();
                        $requisition_data['employee_id']  = $Employee_ID;
                        $requisition_data['month_id'] = $Month_id;
                        $requisition_data['year'] = $Year;
                        $requisition_data['depot_id']  = $Depot_id;
                        $requisition_data['region_id'] = $Region_id;
                        $requisition_data['status'] = 'pending';

                        if($Attendance){

                            $requisitionData = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                ->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->update([
                                    'report_to' => $reporting_sequence ,
                                    'attendance_status' => 'pending',
                                    'status' => 'pending',
                                    'updated_at' => Carbon::now(),
                                ]);
                            $Attendance_ID = $Attendance;

                        }else{

                           $requisitionData = Attendance::create($requisition_data);
                           $Attendance_ID = $requisitionData->id;
                        }
                        

                        //insert data in requisitionDetails table
                        
                            if ($requisitionData) {
                            
                                for ($i=0; $i < count($data['id']); ++$i) 
                                {
                                    //dd($data['id']);
                                    if(($data['working_days'][$i]) != Null){
                                        //dd('komol');
                                        $MDRInformationQry = MdrInformation::where('id', $data['id'][$i])
                                        ->first();
                                        $MDR_EffectiveDate = MdrInformation::select('effectivedate')->where('id', $data['id'][$i])
                                        ->first();

                                        //dd($MDR_EffectiveDate);
                                        $Test_Entry = [];

                                        $Test_Entry['attendance_id']   = $Attendance_ID;
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
                                        $Test_Entry['meeting_days']   = $data['meeting_days'][$i];
                                        $Test_Entry['eid_duty']   = $data['eid_duty'][$i];
                                        $Test_Entry['working_days']   = $data['working_days'][$i];
                                        $Test_Entry['payable_days']   = $data['payable_days'][$i];
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
                                        $Test_Entry['status']   = 'inactive';
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

                                    $ReportTo_Mail   = User::where('id', $reporting_sequence)
                                        ->value('email');
                                    //dd($ReportTo_Mail);
                                    //$email['email']    = $ReportTo_Mail->email;   
                                    //$ReqRaiseMail->email;
                                    //dd($email);
                                    $requisition_log['attendance_id']  = $Attendance_ID;
                                    $requisition_log['user_id'] = auth()->user()->id;
                                    $requisition_log['action_name'] = 'Prepared By  ';
                                    $requisition_log['created_at']  = Carbon::now();
                                    $requisition_log['updated_at']  = Carbon::now();
                                
                                    $RequisitionLogs  = MdrAttendanceLog::insert($requisition_log);
                                        
                                    //$ReqRaiseMail    = $ReportTo_Mail['email'];
                                    //$admin_email     = ['mamun@polarbd.com','samir.paul@polarbd.com'];
                                    $admin_email     = $ReportTo_Mail;
                                    //$customer_email    = $ReportTo_Mail['email'];
                                    //dd($customer_email);

                                    //Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));
                                    
                                    $message = "You have successfully Inserted";
                                    return redirect()->route('mdrattendances.InactiveMDR', [])
                                        ->with('flash_success', $message);

                                } else {
                                    $message = "Something wrong!! Please try again-1";
                                    return redirect()->route('mdrattendances.InactiveMDR', [])
                                        ->with('flash_danger', $message);
                                } 

                            } else {
                                $message = "Something wrong!! Please try again-2";
                                return redirect()->route('mdrattendances.InactiveMDR', [])
                                    ->with('flash_danger', $message);
                            } 

                    }else{
                        $message = "This entry is already checked, so you can't edit or modify it.";
                        return redirect()->route('mdrattendances.InactiveMDR', [])
                            ->with('flash_danger', $message);

                    }

        
                        
                }else {
                    $message = "Your reporting sequence has not been created yet, please contact with Software Administrator";
                    return redirect()->route('mdrattendances.InactiveMDR', [])
                        ->with('flash_danger', $message);
                } 

            }else {
                $message = "Something wrong!! Please try again-4";
                return redirect()->route('mdrattendances.InactiveMDR', [])
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
        //dd($request);
        $regions = Region::pluck('name','id');
        $depots = Depot::pluck('name','id');
        $Months = Month::pluck('name','id');
        $TodayDate  = \Carbon\Carbon::now()->format('d-m-Y');
        //$Years = MdrAttendance::select('year')->distinct()->pluck('year');
        //$Years = MdrAttendance::distinct()->get(['year']);
        $Years = MdrAttendance::select('year')->distinct()->pluck('year');
        //dd($Years);
        return view('mdrattendances.attendance', compact('regions', 'Months', 'TodayDate', 'Years', 'depots'));
                 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function downloadTopSheet(Request $request) 
    {
        //dd($request);
        $regions = Region::pluck('name','id');
        $depots = Depot::pluck('name','id');
        $Months = Month::pluck('name','id');
        $TodayDate  = \Carbon\Carbon::now()->format('d-m-Y');
        //$Years = MdrAttendance::select('year')->distinct()->pluck('year');
        //$Years = MdrAttendance::distinct()->get(['year']);
        $Years = MdrAttendance::select('year')->distinct()->pluck('year');
        //dd($Years);
        return view('mdrattendances.attendanceTopSheet', compact('regions', 'Months', 'TodayDate', 'Years', 'depots'));
                 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function downloadAttendanceReport(Request $request) 
    {
        $data = $request->all();
        //dd($data);
        $Depot_IDs = $data['depot_id'];
        //dd($data['year']);
        $request->validate([
            'depot_id' => 'required',
            'salary_date' => 'required',
            'month_id' => 'required',
            'year' => 'required',
        ]);

        if($data['Attendance']== '1'){
            //dd('1');
            $Month_ID = $data['month_id'];
            $Depot_ID = $data['depot_id'];
            $Year     = $data['year'];

            $users = DB::table('users')
            ->join('mdr_attendances', 'users.id', '=', 'mdr_attendances.user_id')
            ->select('users.*', 'mdr_attendances.phone', 'mdr_attendances.salary')
            ->get();
            //dd($users);

            $AttendanceReport = DB::select(sum('salary'))
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
                ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
                ->orderBy('depots.name', 'asc');

                if($Depot_IDs){
                    //dd($Depot_IDs);
                    $AttendanceReport = $AttendanceReport->whereIn('mdr_attendances.depot_id', $Depot_IDs);
                }
                $AttendanceReport = $AttendanceReport->get();
                //dd($AttendanceReport->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];
               
            //return (new BrandWiseDfExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceReport')))->download('monthly_salary_report.xlsx');
                
            $pdf = \domPDF::loadView('pdf.MDRSalarySheet', compact('AttendanceReport', 'Month_Name'));
            return $pdf->setPaper('a4', 'landscape')->download('Salary Sheet'.'-'.$Month_Name.'-'.$Month_Name.'.pdf');

        }elseif($data['Attendance']== '2'){
            //dd('2');
            $Month_ID = $data['month_id'];
            $Depot_ID = $data['depot_id'];
            $Year     = $data['year'];

            $AttendanceReport = MdrAttendance::with([
                    'distributors'=>function($q){
                        return $q->select('*');
                    },
                    'mdrInformations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                    'depots'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
                ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
                ->orderBy('depots.name', 'asc');

                if($Depot_IDs){
                    //dd($Depot_IDs);
                    $AttendanceReport = $AttendanceReport->whereIn('mdr_attendances.depot_id', $Depot_IDs);
                }
                $AttendanceReport = $AttendanceReport->get();
                //dd($AttendanceReport->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];
               
            return (new BrandWiseDfExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceReport')))->download('monthly_salary_report.xlsx');
                
                        
        }elseif($data['Attendance']== '3'){
            //dd('3');
            $Month_ID = $data['month_id'];
            $Depot_ID = $data['depot_id'];
            $Year = $data['year'];

            $AttendanceReport = MdrAttendance::with([
                    'distributors'=>function($q){
                        return $q->select('*');
                    },
                    'mdrInformations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                    'depots'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
                ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
                ->join('distributors', 'distributors.id', '=', 'mdr_attendances.distributor_id')
                ->orderBy('depots.name', 'asc');

                if($Depot_IDs){
                    //dd($Depot_IDs);
                    $AttendanceReport = $AttendanceReport->whereIn('mdr_attendances.depot_id', $Depot_IDs);
                }
                $AttendanceReport = $AttendanceReport->get();
                //dd($AttendanceReport->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];

                            
            $pdf = \domPDF::loadView('pdf.MDR_TADABillTopSheet', compact('AttendanceReport', 'Month_Name', 'AttendanceLogs'));
            return $pdf->setPaper('a4', 'landscape')->download('TA-DA Bill'.'-'.$Month_Name.'.pdf');

        }elseif($data['Attendance'] == '4'){
            //dd('4');
            $Month_ID = $data['month_id'];
            $Depot_ID = $data['depot_id'];
            $Year = $data['year'];

            $AttendanceReport = MdrAttendance::with([
                    'distributors'=>function($q){
                        return $q->select('*');
                    },
                    'mdrInformations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                    'depots'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
                ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
                ->orderBy('depots.name', 'asc');

                if($Depot_IDs){
                    //dd($Depot_IDs);
                    $AttendanceReport = $AttendanceReport->whereIn('mdr_attendances.depot_id', $Depot_IDs);
                }
                $AttendanceReport = $AttendanceReport->get();
                //dd($AttendanceReport->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];
                
            return (new MdrTADABillExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceReport')))->download('monthly_TADA_report.xlsx');    
            
        }elseif($data['Attendance']== '5'){
            //dd('5');
            $Month_ID = $data['month_id'];
            $Depot_ID = $data['depot_id'];
            $Year = $data['year'];

            $AttendanceReport = MdrAttendance::with([
                    'distributors'=>function($q){
                        return $q->select('*');
                    },
                    'mdrInformations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->where('depot_id', $Depot_ID)
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->orderBy('depot_id', 'desc')
                ->get(); 
                //dd($AttendanceReport->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];
                //dd($MName);
                //$Region_Name_Qry = Region::select('name')->where('id', $Region_ID)->limit(1)
                   // ->first();
                //$Region_Name = $Region_Name_Qry['name'];
                $Depot_Name_Qry = Depot::select('name')->where('id', $Depot_ID)->limit(1)
                    ->first();
                $Depot_Name = $Depot_Name_Qry['name'];

                //return (new EmployeeExport())->download('employees.xlsx');
                
            $pdf = \domPDF::loadView('pdf.MDRattendanceReport', compact('AttendanceReport', 'Month_Name', 'Depot_Name'));
            return $pdf->setPaper('a4', 'landscape')->download('Summary Salary Sheet'.'-'.$Depot_Name.'-'.$Month_Name.'.pdf');    
        }
        
                 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function downloadAttendanceTopSheet(Request $request) 
    {
        $data = $request->all();
        //dd($data);
        //dd($data['status']);
        $request->validate([
            'salary_date' => 'required',
            'month_id' => 'required',
            'year' => 'required',
            'status' => 'required',
        ]);
        //dd('komol');

        if($data['Attendance']== '2'){
            //dd('2');
            $Month_ID   = $data['month_id'];
            $Year       = $data['year'];
            $Status     = $data['status'];

            $AttendanceTopSheet = MdrAttendance::with([
                    'distributors'=>function($q){
                        return $q->select('*');
                    },
                    'mdrInformations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                    'depots'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->where('mdr_attendances.month_id', $Month_ID)
                ->where('mdr_attendances.year', '2026')
                ->where('mdr_attendances.status', $Status)
                ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
                ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id');
                //->groupBy('mdr_attendances.employee_id');
                

                $AttendanceTopSheet = $AttendanceTopSheet->get();
                dd($AttendanceTopSheet->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];
               
            return (new MDRSalaryTopSheetExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceTopSheet', 'Status')))->download('Monthly Attendance top-sheet.xlsx');
                
                        
        }elseif($data['Attendance'] == '4'){
            dd('4');
            $Month_ID = $data['month_id'];
            $Year = $data['year'];

            $AttendanceTopSheet = MdrAttendance::with([
                    'distributors'=>function($q){
                        return $q->select('*');
                    },
                    'mdrInformations'=>function($q){
                        return $q->select('*');
                    },
                    'employee'=>function($q){
                        return $q->select('*');
                    },
                    'depots'=>function($q){
                        return $q->select('*');
                    },
                ])
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
                ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
                ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
                ->orderBy('depots.name', 'asc');

                if($Depot_IDs){
                    //dd($Depot_IDs);
                    $AttendanceTopSheet = $AttendanceTopSheet->whereIn('mdr_attendances.depot_id', $Depot_IDs);
                }
                $AttendanceTopSheet = $AttendanceTopSheet->get();
                //dd($AttendanceReport->toArray());

                $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                    ->first();
                $Month_Name = $Month_Name_Qry['name'];
                
            return (new MDRSalaryTopSheetExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceTopSheet')))->download('Monthly Attendance top-sheet.xlsx');    
            
        }
        
                 
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function UpdateAttendance(Request $request) {
        $data = $request->except('_token');
        $user_id = auth()->user()->id;
        $Attendance_id  = $request->attendance_id;
        //dd($Attendance_id);
        $users = User::with([
                'designation'=>function($q){
                    return $q->select('id', 'title');
                },
                'department'=>function($q){
                    return $q->select('id', 'name');
                },
                'section'=>function($q){
                    return $q->select('id', 'name');
                },
            ])
            ->where('id',$user_id)
            ->first();
        if($request->attendance_status == 'return'){
            //dd('return');
            //dd($users->toArray());
            $attendance_owner = Attendance::where('id', $Attendance_id)->first();
            //dd($attendance_owner);
            $reporting_sequence  = TadaReportingSequenceDetail::where('user_id', $attendance_owner->user_id)->where('report_to', auth()->user()->id)->first();
            //dd($reporting_sequence);
            $attendance_reportTo = TadaReportingSequenceDetail::where('user_id', $reporting_sequence->user_id)
            ->where('sequence', '<', $reporting_sequence->sequence)
            ->orderBy('sequence', 'desc')
            ->limit(1)
            ->first();
            //dd($attendance_reportTo);
            if($attendance_reportTo != ''){
                $attendanceUpdate = Attendance::where('id', $Attendance_id)->update([
                    'report_to' => $attendance_reportTo->report_to,
                    'attendance_status' => 'processing',
                    'status' => 'pending',
                    'updated_at' => Carbon::now(),
                ]);

                $attendance_log['attendance_id']  = $Attendance_id;
                $attendance_log['user_id'] = auth()->user()->id;
                $attendance_log['action_name'] = 'Returned By';
                $attendance_log['comments']    = $request->comments;
                $attendance_log['created_at']  = Carbon::now();
                $attendance_log['updated_at']  = Carbon::now();
                
                $AttendanceLogs  = MdrAttendanceLog::insert($attendance_log);
                //dd($attendance_log);
                $AttenOwner_Mail   = User::where('id', $reporting_sequence->user_id)
                    ->value('email');
                $AttenReportTo_Mail    = User::where('id', $reporting_sequence->report_to)
                    ->value('email');
                $admin_email     = $AttenOwner_Mail;
                //Mail::to($admin_email)->send(new ReturnMail($users));
                $message = "You have successfully Return the Requisition..";
                    return redirect()->route('requisitions.index')
                        ->with('flash_success', $message);
            }else{
                
                $attendanceUpdate = Attendance::where('id', $Attendance_id)->update([
                    'report_to' => $attendance_owner->user_id,
                    'attendance_status' => 'processing',
                    'status' => 'pending',
                    'updated_at' => Carbon::now(),
                ]);

                $attendance_log['attendance_id']  = $Attendance_id;
                $attendance_log['user_id'] = auth()->user()->id;
                $attendance_log['action_name'] = 'Returned By';
                $attendance_log['comments']    = $request->comments;
                $attendance_log['created_at']  = Carbon::now();
                $attendance_log['updated_at']  = Carbon::now();
                
                $AttendanceLogs  = MdrAttendanceLog::insert($attendance_log);
                //dd($attendance_log);
                $AttenOwner_Mail   = User::where('id', $reporting_sequence->user_id)
                    ->value('email');
                $admin_email     = $AttenOwner_Mail;
                //Mail::to($admin_email)->send(new ReturnMail($users));

                $message = "You have successfully Return the Requisition..";
                    return redirect()->route('requisitions.index')
                        ->with('flash_success', $message);
                
            }
            //dd($requisition_reportTo->sequence);
        }elseif($request->attendance_status == 'verify'){
            //dd('Verify');
            $attendance_owner = Attendance::where('id', $Attendance_id)->first();
            //$Req_Sequence   = $attendance_owner['sequence'];
            //dd($attendance_owner->sequence);
            $reporting_sequence  = TadaReportingSequenceDetail::where('user_id', $attendance_owner->user_id)->where('report_to', auth()->user()->id)->first();
            //dd($reporting_sequence);
            $attendance_reportTo = TadaReportingSequenceDetail::where('user_id', $attendance_owner->user_id)
            ->where('sequence', '>', $reporting_sequence->sequence)
            ->orderBy('sequence', 'asc')
            ->limit(1)
            ->first();
            //dd($requisition_reportTo);
            
            if($attendance_reportTo != ''){
                $attendanceUpdate = Attendance::where('id', $Attendance_id)->update([
                        'report_to' => $attendance_reportTo->report_to,
                        'attendance_status' => 'checked',
                        'status' => 'pending',
                        'updated_at' => Carbon::now(),
                    ]);

                        $attendance_log['Attendance_id']  = $Attendance_id;
                        $attendance_log['user_id'] = auth()->user()->id;
                        $attendance_log['action_name'] = 'Forwarded By';
                        $attendance_log['comments']    = $request->comments;
                        $attendance_log['created_at']  = Carbon::now();
                        $attendance_log['updated_at']  = Carbon::now();
                        
                        $AttendanceLogs  = MdrAttendanceLog::insert($attendance_log);
                        //dd($attendance_log);
                        $ReportTo_Mail   = User::where('id', $attendance_reportTo->report_to)
                        ->value('email');
                        $admin_email     = $ReportTo_Mail;
                        //Mail::to($admin_email)->send(new ForwardMail($users));

                        $message = "You have successfully Forward the Attendance..";
                            return redirect()->route('requisitions.index')
                                ->with('flash_success', $message);
                //dd('Not Null');
            }else{
                $attendanceUpdate = Attendance::where('id', $Attendance_id)->update([
                        'report_to' => auth()->user()->id,
                        'attendance_status' => 'audited',
                        'status' => 'approved',
                        'updated_at' => Carbon::now(),
                    ]);

                        $attendance_log['Attendance_id']  = $Attendance_id;
                        $attendance_log['user_id'] = auth()->user()->id;
                        $attendance_log['action_name'] = 'Approved By';
                        $attendance_log['comments']    = $request->comments;
                        $attendance_log['created_at']  = Carbon::now();
                        $attendance_log['updated_at']  = Carbon::now();
                        
                        $AttendanceLogs  = MdrAttendanceLog::insert($attendance_log);
                        //dd($attendance_log);

                        $attendance_owner = Attendance::where('id', $Attendance_id)->first();
                        //dd($requisition_owner);
                        $AttenOwner_Mail   = User::where('id', $attendance_owner->user_id)
                        ->value('email');
                        $AttenReportTo_Mail    = User::where('id', $attendance_owner->report_to)
                            ->value('email');
                        $admin_email     = $AttenOwner_Mail;

                        //Mail::to($admin_email)->send(new ApproveMailAttendance($users));

                        $message = "You have successfully Approved the Attendance..";
                            return redirect()->route('requisitions.index')
                                ->with('flash_success', $message);
                //dd('Null');
            }
        
        }else{
            $message = "Something wrong !! Please Contact with Software Administrator.....";
                return redirect()->route('requisitions.index', [])
                    ->with('flash_danger', $message);
        }
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
        //dd($request->requisition_status);
        //dd($request->toArray());
        //dd($id);

        //$DepotID = Depot::where('name', $DepotID)->first();
        $authUser  = auth()->user()->id ;
        $usersInfo = User::with([
                'designation'=>function($q){
                    return $q->select('id', 'title');
                },
                'department'=>function($q){
                    return $q->select('id', 'name');
                },
                'section'=>function($q){
                    return $q->select('id', 'name');
                },
            ])
            ->where('id',$authUser)
            ->get();

        $userInfo = auth()->user('id');
        $User_ID  = $userInfo['id'];

        //dd($usersInfo);

        $User_Dept_Id   = User::where('id', auth()->user()->id)
                                ->value('department_id');

        //dd($User_Dept_Id);

        $attendance_creator = Attendance::where('id', $id)->first();
        $Req_Sequence   = $attendance_creator['sequence'];
        //dd($attendance_creator->sequence);
        $reporting_sequence  = TadaReportingSequenceDetail::where('user_id', $attendance_creator->user_id)->where('report_to', auth()->user()->id)->first();
        //dd($reporting_sequence);
        $requisition_reportTo = TadaReportingSequenceDetail::where('user_id', $attendance_creator->user_id)
        ->where('sequence', '>', $reporting_sequence->sequence)
        ->orderBy('sequence', 'asc')
        ->limit(1)
        ->first();
        //dd($requisition_reportTo->toArray());
        
        if($requisition_reportTo != ''){

            if($User_Dept_Id == 3){
                $requisitionUpdate = Attendance::where('id', $id)->update([
                    'report_to' => $requisition_reportTo->report_to,
                    'attendance_status' => 'checked',
                    'status' => 'pending',
                    'updated_at' => Carbon::now(),
                ]);

                    $requisition_log['attendance_id']  = $id;
                    $requisition_log['user_id'] = auth()->user()->id;
                    $requisition_log['action_name'] = 'Checked By';
                    $requisition_log['created_at']  = Carbon::now();
                    $requisition_log['updated_at']  = Carbon::now();
                    
                    $RequisitionLogs  = MdrAttendanceLog::insert($requisition_log);
                    //dd($requisition_log);
                    $ReportTo_Mail   = User::where('id', $requisition_reportTo->report_to)
                    ->value('email');
                    $admin_email     = $ReportTo_Mail;
                    //Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));

                    $message = "You have successfully Forward the TA/DA Bill..";
                        return redirect()->route('mdrattendances.attendanceList')
                            ->with('flash_success', $message);
            //dd('Not Null');

            }else{

                $Attendance_check = Attendance::where('id', $id)
                                ->where('attendance_status', 'checked')
                                ->value('id');
                    if(empty($Attendance_check)){
                        $requisitionUpdate = Attendance::where('id', $id)->update([
                        'report_to' => $requisition_reportTo->report_to,
                        'attendance_status' => 'processing',
                        'status' => 'pending',
                        'updated_at' => Carbon::now(),
                    ]);

                        $requisition_log['attendance_id']  = $id;
                        $requisition_log['user_id'] = auth()->user()->id;
                        $requisition_log['action_name'] = 'Forwarded By';
                        $requisition_log['created_at']  = Carbon::now();
                        $requisition_log['updated_at']  = Carbon::now();
                        
                        $RequisitionLogs  = MdrAttendanceLog::insert($requisition_log);
                        //dd($requisition_log);
                        $ReportTo_Mail   = User::where('id', $requisition_reportTo->report_to)
                        ->value('email');
                        $admin_email     = $ReportTo_Mail;
                        //Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));

                        $message = "You have successfully Forward the TA/DA Bill..";
                            return redirect()->route('mdrattendances.attendanceList')
                                ->with('flash_success', $message);

                    }else{

                        $requisitionUpdate = Attendance::where('id', $id)->update([
                        'report_to' => $requisition_reportTo->report_to,
                        'attendance_status' => 'checked',
                        'status' => 'pending',
                        'updated_at' => Carbon::now(),
                    ]);

                        $requisition_log['attendance_id']  = $id;
                        $requisition_log['user_id'] = auth()->user()->id;
                        $requisition_log['action_name'] = 'Forwarded By';
                        $requisition_log['created_at']  = Carbon::now();
                        $requisition_log['updated_at']  = Carbon::now();
                        
                        $RequisitionLogs  = MdrAttendanceLog::insert($requisition_log);
                        //dd($requisition_log);
                        $ReportTo_Mail   = User::where('id', $requisition_reportTo->report_to)
                        ->value('email');
                        $admin_email     = $ReportTo_Mail;
                        //Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));

                        $message = "You have successfully Forward the TA/DA Bill..";
                            return redirect()->route('mdrattendances.attendanceList')
                                ->with('flash_success', $message);
                    }
                
            //dd('Not Null');
            }

            
        }else{
            $requisitionUpdate = Attendance::where('id', $id)->update([
                    'report_to' => auth()->user()->id,
                    'attendance_status' => 'audited',
                    'status' => 'approved',
                    'updated_at' => Carbon::now(),
                ]);

                    $requisition_log['attendance_id']  = $id;
                    $requisition_log['user_id'] = auth()->user()->id;
                    $requisition_log['action_name'] = 'Audited By';
                    $requisition_log['created_at']  = Carbon::now();
                    $requisition_log['updated_at']  = Carbon::now();
                    
                    $RequisitionLogs  = MdrAttendanceLog::insert($requisition_log);
                    //dd($requisition_log);

                    $attendance_creator = Attendance::where('id', $id)->first();
                    //dd($requisition_owner);
                    $ReqOwner_Mail   = User::where('id', $attendance_creator->user_id)
                    ->value('email');
                    $ReqReportTo_Mail    = User::where('id', $attendance_creator->report_to)
                        ->value('email');
                    $admin_email     = $ReqOwner_Mail;

                    //Mail::to($admin_email)->send(new TaDaBillApproveMail($usersInfo));

                    $message = "You have successfully Verified/Audited the Attendance..";
                        return redirect()->route('mdrattendances.attendanceList')
                            ->with('flash_success', $message);
            //dd('Null');
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
        //
    }

    public function AttendanceList()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
            //dd($user_id);
            $reportToRequisitions = Attendance::with([
                'regions'=>function($q){
                    return $q->select('id', 'name');
                },
                'depots'=>function($q){
                    return $q->select('id', 'name');
                },
                'months'=>function($q){
                    return $q->select('id', 'name');
                },
                'user'=>function($q){
                    return $q->select('*');
                },
            ])
            ->where('report_to',$user_id)
            ->where('status', 'pending')
            ->where('attendance_status','<>', 'return')
            ->where('region_id', '<>', NULL)
            ->orderBy('id', 'desc')
            //->orWhere('complain_status','Marketing')
            //Carbon::parse($employees['birthdate'])->format('d-m-Y');
            ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
        ->get();
        */
        //dd($reportToRequisitions->toArray());
        return view('mdrattendances.attendanceList', compact('reportToRequisitions'));
        //return view('mdrattendances.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendanceview($id)
    {
            // /dd($id);
            $MdrInformations = MdrAttendance::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                'mdrInformations'=>function($q){
                    return $q->select('*');
                },
                'depots'=>function($q){
                    return $q->select('*');
                },
                'regions'=>function($q){
                    return $q->select('*');
                },
                'months'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->where('attendance_id',$id)
            ->orderBy('distributor_id', 'ASC')
            ->get(); 

            //dd($MdrInformations->toArray());
            $RegionName = $MdrInformations[0]->regions->name;
            $DepotName = $MdrInformations[0]->depots->name;
            $MonthName = $MdrInformations[0]->months->name;
            $TodayDate = $MdrInformations[0]->salary_date;
            $year = $MdrInformations[0]->year;
            //dd($year);


        return view('mdrattendances.attendanceview', compact('MdrInformations', 'RegionName', 'DepotName', 'TodayDate', 'MonthName', 'year'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendanceViewCheck($id)
    {

        //dd($id);
            $MdrInformations = MdrAttendance::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                'mdrInformations'=>function($q){
                    return $q->select('*');
                },
                'depots'=>function($q){
                    return $q->select('*');
                },
                'regions'=>function($q){
                    return $q->select('*');
                },
                'months'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->where('attendance_id',$id)
            ->orderBy('distributor_id', 'ASC')
            ->get(); 

            //dd($MdrInformations->toArray());
            $RegionName = $MdrInformations[0]->regions->name;
            $MonthName = $MdrInformations[0]->months->name;
            $TodayDate = $MdrInformations[0]->salary_date;
            $year = $MdrInformations[0]->year;
            //dd($year);

            $AttendanceLogs = MdrAttendanceLog::with([
                'user'=>function($q){
                    return $q->select('*');
                },
                
            ])
            ->where('attendance_id', $id)
            ->orderBy('id', 'ASC')
            ->get();


        return view('mdrattendances.attendanceViewCheck', compact('MdrInformations', 'RegionName', 'TodayDate', 'MonthName', 'year', 'AttendanceLogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function AttendanceAudited()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
            //dd($user_id);
            $reportToRequisitions = Attendance::with([
                'regions'=>function($q){
                    return $q->select('id', 'name');
                },
                'depots'=>function($q){
                    return $q->select('id', 'name');
                },
                'months'=>function($q){
                    return $q->select('id', 'name');
                },
                'user'=>function($q){
                    return $q->select('*');
                },
            ])
            ->where('status', 'approved')
            ->where('attendance_status', 'audited')
            ->where('region_id', '<>', NULL)
            ->orderBy('id', 'desc')
            //->orWhere('complain_status','Marketing')
            //Carbon::parse($employees['birthdate'])->format('d-m-Y');
            ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
        ->get();
        */
        //dd($reportToRequisitions->toArray());
        return view('mdrattendances.attendanceAudited', compact('reportToRequisitions'));
        //return view('mdrattendances.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function AttendanceProcessing()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
            //dd($user_id);
            $reportToRequisitions = Attendance::with([
                'regions'=>function($q){
                    return $q->select('id', 'name');
                },
                'depots'=>function($q){
                    return $q->select('id', 'name');
                },
                'months'=>function($q){
                    return $q->select('id', 'name');
                },
                'user'=>function($q){
                    return $q->select('*');
                },
            ])
            ->where('status', 'pending')
            ->where('attendance_status', 'checked')
            ->orWhere('attendance_status', 'processing')
            ->where('region_id', '<>', NULL)
            ->orderBy('id', 'desc')
            //->orWhere('complain_status','Marketing')
            //Carbon::parse($employees['birthdate'])->format('d-m-Y');
            ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
        ->get();
        */
        //dd($reportToRequisitions->toArray());
        return view('mdrattendances.attendanceProcessing', compact('reportToRequisitions'));
        //return view('mdrattendances.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function AttendanceSubmitted()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
            //dd($user_id);
            $reportToRequisitions = Attendance::with([
                'regions'=>function($q){
                    return $q->select('id', 'name');
                },
                'depots'=>function($q){
                    return $q->select('id', 'name');
                },
                'months'=>function($q){
                    return $q->select('id', 'name');
                },
                'user'=>function($q){
                    return $q->select('*');
                },
            ])
            ->where('status', 'pending')
            ->where('attendance_status', 'pending')
            ->where('region_id', '<>', NULL)
            ->orderBy('id', 'desc')
            //->orWhere('complain_status','Marketing')
            //Carbon::parse($employees['birthdate'])->format('d-m-Y');
            ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
        ->get();
        */
        //dd($reportToRequisitions->toArray());
        return view('mdrattendances.attendanceSubmitted', compact('reportToRequisitions'));
        //return view('mdrattendances.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function approveAttendanceDownload($id) {
        
        $Qry = Attendance::where('id', $id)->limit(1)
                ->first();
                //dd($Qry->toArray());
        
        $Month_ID = $Qry['month_id'];
        $Depot_ID = $Qry['depot_id'];
        $Year = 2026;
        //dd($Month_ID);
        $AttendanceReport = MdrAttendance::with([
                'distributors'=>function($q){
                    return $q->select('*');
                },
                'mdrInformations'=>function($q){
                    return $q->select('*');
                },
                'employee'=>function($q){
                    return $q->select('*');
                },
                'depots'=>function($q){
                    return $q->select('*');
                },
            ])
            ->where('month_id', $Month_ID)
            ->where('year', '2026')
            ->where('attendance_id', $id)
            ->join('mdr_informations', 'mdr_informations.id', '=', 'mdr_attendances.mdr_id')
            ->join('employees', 'employees.id', '=', 'mdr_informations.employee_id')
            ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
            ->join('distributors', 'distributors.id', '=', 'mdr_attendances.distributor_id')
            ->orderBy('depots.name', 'asc')
            ->get();

            //if($Depot_IDs){
                //dd($Depot_IDs);
                //$AttendanceReport = $AttendanceReport->whereIn('mdr_attendances.depot_id', $Depot_IDs);
            //}
            //$AttendanceReport = $AttendanceReport->get();
            //dd($AttendanceReport->toArray());

            $Month_Name_Qry = Month::select('name')->where('id', $Month_ID)->limit(1)
                ->first();
            $Month_Name = $Month_Name_Qry['name'];

            $Depot_Name_Qry = Depot::select('name')->where('id', $Depot_ID)->limit(1)
                ->first();
            $Depot_Name = $Depot_Name_Qry['name'];

            $AttendanceLogs = MdrAttendanceLog::with([
                'user'=>function($q){
                    return $q->select('*');
                },
                'user.designation'=>function($q){
                    return $q->select('*');
                },
            
            ])
            ->where('attendance_id', $id)
            ->orderBy('id', 'asc')
            ->get();
            
            //dd($AttendanceLogs->toArray());

                        
        $pdf = \domPDF::loadView('pdf.MDR_TADABill', compact('AttendanceReport', 'Month_Name', 'AttendanceLogs', 'Depot_Name'));
        return $pdf->setPaper('a4', 'landscape')->download('TA-DA Bill'.'-'.$Month_Name.'.pdf');

         
    }

    
}
