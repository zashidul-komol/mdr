<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\DepotUser;
use App\Models\DistributorUser;
use App\Models\DfReturn;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Section;
use App\Models\Item;
use App\Models\Employee;
use App\Models\PhysicalVisit;
use App\Models\Application;
use App\Models\ApplicationLog;
use App\Models\ApplicationDetail;
use App\Models\MerchanApplication;
use App\Models\MerchanApplicationLog;
use App\Models\MerchanApplicationDetail;
use App\Models\MdrInformation;
use App\Models\MerchandiserInformation;
use App\Models\RequisitionLog;
use App\Models\Role;
use App\Models\Settlement;
use App\Models\ReportingSequenceDetail;
use App\Models\ReportingSequence;
use App\Models\TadaReportingSequenceDetail;
use App\Models\TadaReportingSequence;
use App\Models\Product;
use App\Models\Measurement;
use App\Models\Size;
use App\Models\shop;
use App\Models\Stage;
use App\Traits\DocumentsUpload;
use App\Traits\HasStageExists;
use App\Traits\SettlementCreateCloseData;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Region;
use App\Models\Distributor;
use App\Models\Depot;
use App\Models\Month;
use App\Models\Attendance;
use App\Models\MdrAttendance;
use App\Models\MdrAttendanceLog;
use Auth;
use App\Mail\ReturnMail;
use App\Mail\ReqRaisedMail;
use App\Mail\ForwardMail;
use App\Mail\ApproveMail;
use App\Mail\HoldMail;
use App\Mail\CancelMail;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;
use Intervention\Image\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Exports\ActiveMDRExport;
use App\Exports\AprovedMDRExport;
use App\Exports\MerchandiserSalarySheetExport;
use App\Exports\MerchandiserTADABillExport;

use Illuminate\Http\Request;

class MerchandisersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
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
                'merchandiser_informations'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->where('employee_id',$EmployeeID)
            ->where('year', $year)
            ->where('month_id', $MonthID)
            ->get(); 

        //dd($MdrInformations->toArray());

        return view('merchandiserattendances.view', compact('MdrInformations', 'RegionName', 'TodayDate', 'MonthName', 'year'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $authUser  = auth()->user()->id;
        //dd($authUser);
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
        ->where('id',$authUser)
        ->get();
        $users[0] = $users[0];
        $authUser = Auth::user()->employee_id;
        //dd($authUser);
        $RegionIdQry = Employee::where('id', Auth::user()->employee_id)->pluck('region_id');
        //dd($RegionIdQry);
        $CurrentDate = Carbon::now();
        //$MaxID = Requisition::max('id');
        //$NowMaxNo = $MaxID + 1;
        $AuthDeptID = Auth::user()->department_id;
        $departments = Department::pluck('name','id');
        $designations = Designation::pluck('title','id');

        $region_name = Region::where('id',$RegionIdQry);

        $distributorsUser = Distributor::where('region_id', $RegionIdQry)
        ->where('status', 'active')
        ->pluck('distributorName','id');
        //dd($distributorsUser->toArray());
        //$DistributorRegion = $distributorsUser->distributorName .'-'.$region_name->name ; 
        $sections = Section::pluck('name','id');

        $distributor = Shop::select('outlet_name', 'id', 'address')
            ->where('is_distributor', '1')
            ->where('status', 'active')
            ->where('region_id', $RegionIdQry)
            ->selectRaw("CONCAT(outlet_name, ' -- ' ,address) as OutLateName")
            ->orderBy('outlet_name')
            ->pluck('OutLateName', 'id');
            //dd($distributor->toArray());

/*
            @foreach ($users as $user)
                @php
                    $full_name = $user->first_name. " " .$user->last_name;
                @endphp
                {{ $full_name }}
            @endforeach
*/
            //->selectRaw("CONCAT(shops.outlet_name,'::',shops.mobile,' (',distributors.outlet_name, ')') as outlet_name")

        //$products         = Product::orderBy('name', 'asc')->where('department_id', $AuthDeptID)->pluck('name','id');
        //dd($products);
        return view('merchandisers.create', compact('users', 'CurrentDate', 'departments', 'sections', 'designations', 'distributor', 'distributorsUser'));
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createMerchandiser()
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

        //dd($Monthly_Total_Holidays);merchandiser_informations



            $MdrInformations = MerchandiserInformation::with([
                'depots'=>function($q){
                    return $q->select('*');
                },
                                
            ])
            ->where('employee_id',$EmployeeID)
            ->where('basic_salary', '!=', Null)
            ->where('status', 'active')
            ->get(); 

        //dd($MdrInformations->toArray());

        return view('merchandiserattendances.merchandiserAttendance', compact('MdrInformations', 'RegionName', 'TodayDate', 'MonthName', 'year', 'Month_Days', 'Monthly_Total_Holidays', 'DepotName', 'month_start_date', 'month_end_date'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeMerchandiser(Request $request)
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
                                //->where('region_id', $Region_id)
                                ->where('year', $Year)
                                ->value('id');
                    //dd($Employee_ID);

                    $Attendance_ID = $Attendance;
                    $Attendance_check = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                //->where('region_id', $Region_id)
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
                        //$requisition_data['region_id'] = $Region_id;
                        $requisition_data['status'] = 'pending';

                        if($Attendance){

                            $requisitionData = Attendance::where('employee_id', $Employee_ID)
                                ->where('month_id', $Month_id)
                                ->where('depot_id', $Depot_id)
                                //->where('region_id', $Region_id)
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
                                        $MDRInformationQry = MerchandiserInformation::where('id', $data['id'][$i])
                                        ->first();
                                        $MDR_EffectiveDate = MerchandiserInformation::select('joiningdate')->where('id', $data['id'][$i])
                                        ->first();

                                        //dd($MDR_EffectiveDate);
                                        $Test_Entry = [];

                                        $Test_Entry['attendance_id']   = $Attendance_ID;
                                        $Test_Entry['merchan_id']   = $data['id'][$i];
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
                                        $MDRAttendance = MdrAttendance::where('merchan_id', $data['id'][$i])
                                        ->where('month_id', $Month_id)
                                        ->where('year', $data['year'])
                                        ->value('id');

                                        if($MDRAttendance){
                                            MdrAttendance::where(['merchan_id'=> $data['id'][$i]])
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
                                    $admin_email     = $ReportTo_Mail;

                                    Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));
                                    
                                    $message = "You have successfully Inserted";
                                    return redirect()->route('merchandiserattendances.createMerchandiser', [])
                                        ->with('flash_success', $message);

                                } else {
                                    $message = "Something wrong!! Please try again-1";
                                    return redirect()->route('merchandiserattendances.createMerchandiser', [])
                                        ->with('flash_danger', $message);
                                } 

                            } else {
                                $message = "Something wrong!! Please try again-2";
                                return redirect()->route('merchandiserattendances.createMerchandiser', [])
                                    ->with('flash_danger', $message);
                            } 

                    }else{
                        $message = "This entry is already checked, so you cant edit or modify it.";
                        return redirect()->route('merchandiserattendances.createMerchandiser', [])
                            ->with('flash_danger', $message);

                    }

        
                        
                }else {
                    $message = "Your reporting sequence has not been created yet, please contact with Software Administrator";
                    return redirect()->route('merchandiserattendances.createMerchandiser', [])
                        ->with('flash_danger', $message);
                } 

            }else {
                $message = "Something wrong!! Please try again-4";
                return redirect()->route('merchandiserattendances.createMerchandiser', [])
                    ->with('flash_danger', $message);
            } 
            

            

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //dd($request);
        $EffectiveDate = \Carbon\Carbon::parse($request->effectivedate)->format('Y-m-d');
        $DateOfBirth = \Carbon\Carbon::parse($request->date_of_birth)->format('Y-m-d');

        //dd($DateOfBirth);
        
        //{{ \Carbon\Carbon::parse($user->from_date)->format('d/m/Y')}}
        $request->validate([
            'name' => 'required',
            'education' => 'required',
            'applicant_image' => 'required',
            'applicant_cv' => 'required',
            'date_of_birth' => 'required',
            'nid' => 'required|unique:mdr_informations',
            'remarks' => 'required',
            'height_feet' => 'required',
            'height_inch' => 'required',
            'applicant_mobile' => 'required|min:11|max:12|string|unique:mdr_informations',
        ]);

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
        $data = $request->except('_token');
        //dd($data);
        
        //dd($usersInfo->toArray());
        //dd($data);
        if($request->file('applicant_image')){
            $file_cert = $request->file('applicant_image');
            $extension_cert =$file_cert->getClientOriginalExtension();
            $fileName = time().'.'.$extension_cert;
            $file_cert->move('storage/applicantImages/', $fileName);
            $data['applicant_image'] = $fileName;
        }else{
            $fileName = '';
        }
        
        if($request->file('applicant_cv')){
            $file = $request->file('applicant_cv');
            $extension =$file->getClientOriginalExtension();
            $applicantCV = time().'.'.$extension;
            $file->move('storage/applicantCV/', $applicantCV);
            $data['applicant_cv'] = $applicantCV;
        }else{
            $applicantCV = '';
        }

        if($request->file('certificate')){
            $file_cert = $request->file('certificate');
            $extension_cert =$file_cert->getClientOriginalExtension();
            $certificate = time().'.'.$extension_cert;
            $file_cert->move('storage/Certificate/', $certificate);
            $data['certificate'] = $certificate;
        }else{
            $certificate = '';  
        }
        //dd($file);
        if(ReportingSequence::where('user_id', $authUser)->exists()){
            $requisition_Request_data = $request->except('_method', '_token');
            $user_data  = Auth::user();
            $Region_ID = Employee::where('id', $user_data->employee_id)->first();
            $region = $Region_ID->region_id;
            $DepotID = $Region_ID->depot_id;
            //dd($DepotID);
            $reporting_sequence = ReportingSequenceDetail::where('user_id', auth()->user()->id)
            ->where('sequence', '=', 1)
            ->value('report_to');
            
            $application_data['user_id']    = $user_data->id;
            $application_data['employee_id']    = $user_data->employee_id;
            $application_data['report_to']  = $reporting_sequence;
            $application_data['depot_id']   = $DepotID;
            $application_data['sequence']   = 1;
            $application_data['date']   = Carbon::now();
            $application_data['application_status'] = 'pending';
            $application_data['status'] = 'pending';
            $application_data['created_at'] = Carbon::now();
            $application_data['updated_at'] = Carbon::now();
            


            //insert data in Applications table
            $applicationData = MerchanApplication::create($application_data);

            $MDRDate = \Carbon\Carbon::parse($request->effectivedate)->format('y-m-d');

            $date = explode("-", $MDRDate);
            $year = $date[0];
            $month = $date[1];
            $day = $date[2];

            $MDR_IDCARD  = $year.$month.$applicationData->id ;
            //dd($MDR_ID);

            //dd($applicationData);
            //insert data in ApplicationDetails table
            
            if ($applicationData) {

                $applicationDetail_data['merchan_application_id']   = $applicationData->id;
                $applicationDetail_data['employee_id']  = $user_data->employee_id;
                $applicationDetail_data['applicant_name']   = $data['name'];
                $applicationDetail_data['applicant_fathers_name']   = $data['applicant_fathers_name'];
                $applicationDetail_data['applicant_address']    = $data['applicant_address'];
                $applicationDetail_data['applicant_mobile'] = $data['applicant_mobile'];
                $applicationDetail_data['applicant_email']  = $data['email'];
                $applicationDetail_data['nid']  = $data['nid'];
                $applicationDetail_data['applicant_education']  = $data['education'];
                $applicationDetail_data['date_of_birth']    = $DateOfBirth;
                $applicationDetail_data['height_feet']  = $data['height_feet'];
                $applicationDetail_data['height_inch']  = $data['height_inch'];
                //$applicationDetail_data['appearance'] = $data['appearance'];
                //$applicationDetail_data['effectivedate']    = $EffectiveDate;
                $applicationDetail_data['applicant_image']  = $fileName;
                $applicationDetail_data['applicant_cv'] = $applicantCV;
                $applicationDetail_data['certificate']  = $certificate;
                $applicationDetail_data['remarks']  = $data['remarks'];
                $applicationDetail_data['created_at']   = Carbon::now();
                $applicationDetail_data['updated_at']   = Carbon::now(); 

                $applicationDetailsData = MerchanApplicationDetail::create($applicationDetail_data);

                $mdrInformation_data['merchandiser_idcard']  = $MDR_IDCARD;
                $mdrInformation_data['merchan_application_id']  = $applicationData->id;
                $mdrInformation_data['employee_id'] = $user_data->employee_id;
                $mdrInformation_data['depot_id']    = $DepotID;
                $mdrInformation_data['applicant_name']  = $data['name'];
                $mdrInformation_data['applicant_fathers_name']  = $data['applicant_fathers_name'];
                $mdrInformation_data['applicant_address']   = $data['applicant_address'];
                $mdrInformation_data['applicant_mobile']    = $data['applicant_mobile'];
                $mdrInformation_data['applicant_email'] = $data['email'];
                $mdrInformation_data['nid'] = $data['nid'];
                $mdrInformation_data['applicant_education'] = $data['education'];
                $mdrInformation_data['date_of_birth']   = $DateOfBirth;
                $mdrInformation_data['height_feet'] = $data['height_feet'];
                $mdrInformation_data['height_inch'] = $data['height_inch'];
                //$applicationDetail_data['appearance'] = $data['appearance'];
                //$mdrInformation_data['joiningdate']   = $EffectiveDate;
                $mdrInformation_data['applicant_image'] = $fileName;
                $mdrInformation_data['applicant_cv']    = $applicantCV;
                $mdrInformation_data['certificate'] = $certificate;
                $mdrInformation_data['remarks'] = $data['remarks'];
                $mdrInformation_data['status']  = 'pending';
                $mdrInformation_data['created_at']  = Carbon::now();
                $mdrInformation_data['updated_at']  = Carbon::now(); 

                $mdrInformationData = MerchandiserInformation::create($mdrInformation_data);

                
                $applicationLog_data['merchan_application_id']  = $applicationData->id;
                $applicationLog_data['user_id'] = $user_data->id;
                $applicationLog_data['employee_id'] = $user_data->employee_id;
                $applicationLog_data['remarks'] = $data['remarks'];
                $applicationLog_data['created_at']  = Carbon::now();
                $applicationLog_data['updated_at']  = Carbon::now(); 

                $applicationLogsData = MerchanApplicationLog::create($applicationLog_data);

                $ReportTo_Mail   = User::where('id', $reporting_sequence)
                    ->value('email');
                 
                //$admin_email     = ['zashidul@polarbd.com'];
                $admin_email     = $ReportTo_Mail;
                
                Mail::to($admin_email)->send(new ReqRaisedMail($usersInfo));
                
                
                $message = "You have successfully created the Application....";
                    return redirect()->route('merchandiserattendances.create')
                        ->with('flash_success', $message);

            } else {
                $message = "Something wrong!! Please try again";
                return redirect()->route('merchandiserattendances.create', [])
                    ->with('flash_danger', $message);
            }
        }else{
            $message = "Your reporting sequence was not created. Please contact with software administrator.....";
                return redirect()->route('merchandiserattendances.create', [])
                    ->with('flash_danger', $message);
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function AttendanceList()
    {
        //dd('Komol');
        $user_id = auth()->user()->id;
            //dd($user_id);
            $reportToRequisitions = Attendance::with([
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
            ->where('region_id', NULL)
            ->where('attendance_status','<>', 'return')
            ->orderBy('id', 'desc')
            ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
        ->get();
        */
        //dd($reportToRequisitions->toArray());
        return view('merchandiserattendances.attendanceList', compact('reportToRequisitions'));
        //return view('mdrattendances.index');
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
                Mail::to($admin_email)->send(new ReturnMail($users));
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
                Mail::to($admin_email)->send(new ReturnMail($users));

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
                        Mail::to($admin_email)->send(new ForwardMail($users));

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

                        Mail::to($admin_email)->send(new ApproveMailAttendance($users));

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
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //dd($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //dd($id);

        $application_id = $id;
        $ApplicationDetails = MdrInformation::with([
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
        ->where('application_id', $id)
        ->get();
        //$ApplicationDetails = $ApplicationDetails[0];
        $distributorsUser = Distributor::where('region_id', $ApplicationDetails[0]->region_id)
        ->where('status', 'active')
        ->pluck('distributorName','id');
        $DepotName = Depot::pluck('name','id');
        $RegionNameQry = Region::pluck('name','id');
        //dd($DepotName);
        $EmployeeName = Employee::where('region_id', $ApplicationDetails[0]->region_id)
        ->where('status', 'active')
        ->pluck('name','id');
        //dd($distributorsUser->toArray());
        //dd($ApplicationDetails->toArray());

        
        return view('merchandisers.edit', compact('ApplicationDetails', 'distributorsUser', 'EmployeeName', 'DepotName', 'RegionNameQry'));
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
                    Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));

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
                        Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));

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
                        Mail::to($admin_email)->send(new DepotTADABillMail($usersInfo));

                        $message = "You have successfully Forward the TA/DA Bill..";
                            return redirect()->route('merchandiserattendances.attendanceList')
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

                    Mail::to($admin_email)->send(new TaDaBillApproveMail($usersInfo));

                    $message = "You have successfully Verified/Audited the Attendance..";
                        return redirect()->route('merchandiserattendances.attendanceList')
                            ->with('flash_success', $message);
            //dd('Null');
        }
        
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
        public function attendanceview($id)
        {
            $MdrInformations = MdrAttendance::with([
                'merchandiser_informations',
                'depots',
                'months',
            ])
            ->where('attendance_id', $id)
            ->get();

            if ($MdrInformations->isEmpty()) {
                abort(404, 'Attendance not found');
            }

            $firstRow  = $MdrInformations->first();

            $DepotName = $firstRow->depots->name;
            $MonthName = $firstRow->months->name;
            $TodayDate = $firstRow->salary_date;
            $year      = $firstRow->year;

            return view(
                'merchandiserattendances.attendanceview',
                compact(
                    'MdrInformations',
                    'DepotName',
                    'TodayDate',
                    'MonthName',
                    'year'
                )
            );
        }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function attendanceViewCheck($id)
    {
        // Single record (for header + form)
        $MdrInformation = MdrAttendance::with([
            'merchandiser_informations',
            'depots',
            'months'
        ])
        ->where('attendance_id', $id)
        ->first();

        if (!$MdrInformation) {
            abort(404, 'Attendance record not found');
        }

        // Collection (for table rows)
        $MdrInformations = MdrAttendance::with([
            'merchandiser_informations'
        ])
        ->where('attendance_id', $id)
        ->get();

        $MonthName = optional($MdrInformation->months)->name;
        $TodayDate = $MdrInformation->salary_date;
        $year = $MdrInformation->year;

        $AttendanceLogs = MdrAttendanceLog::with('user')
            ->where('attendance_id', $id)
            ->orderBy('id', 'ASC')
            ->get();

        return view('merchandiserattendances.attendanceViewCheck', compact(
            'MdrInformation',
            'MdrInformations',
            'TodayDate',
            'MonthName',
            'year',
            'AttendanceLogs'
        ));
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
            ->where('region_id', NULL)
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
        return view('merchandiserattendances.attendanceAudited', compact('reportToRequisitions'));
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
            ->where('region_id', Null)
            //->where('attendance_status', 'processing')
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
        return view('merchandiserattendances.attendanceProcessing', compact('reportToRequisitions'));
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
            ->where('region_id', NULL)
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
        return view('merchandiserattendances.attendanceSubmitted', compact('reportToRequisitions'));
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
            ->where('month_id', $Month_ID)
            ->where('year', '2026')
            ->where('attendance_id', $id)
            ->join('merchandiser_informations', 'merchandiser_informations.id', '=', 'mdr_attendances.merchan_id')
            ->join('employees', 'employees.id', '=', 'merchandiser_informations.employee_id')
            ->join('depots', 'depots.id', '=', 'mdr_attendances.depot_id')
            //->join('distributors', 'distributors.id', '=', 'mdr_attendances.distributor_id')
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

                        
        $pdf = \domPDF::loadView('pdf.Merchandiser_TADABill', compact('AttendanceReport', 'Month_Name', 'AttendanceLogs', 'Depot_Name'));
        return $pdf->setPaper('a4', 'landscape')->download('TA-DA Bill'.'-'.$Month_Name.'.pdf');

         
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

            $AttendanceReport = MdrAttendance::with([
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
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('merchandiser_informations', 'merchandiser_informations.id', '=', 'mdr_attendances.merchan_id')
                ->join('employees', 'employees.id', '=', 'merchandiser_informations.employee_id')
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
               
            return (new MerchandiserSalarySheetExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceReport')))->download('merchandiser_monthly_salary_report.xlsx');

        }elseif($data['Attendance'] == '2'){
            //dd('4');
            $Month_ID = $data['month_id'];
            $Depot_ID = $data['depot_id'];
            $Year = $data['year'];

            $AttendanceReport = MdrAttendance::with([
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
                ->where('month_id', $Month_ID)
                ->where('year', '2026')
                ->join('merchandiser_informations', 'merchandiser_informations.id', '=', 'mdr_attendances.merchan_id')
                ->join('employees', 'employees.id', '=', 'merchandiser_informations.employee_id')
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
                
            return (new MerchandiserTADABillExport(compact('Month_ID', 'Depot_ID', 'Year', 'AttendanceReport')))->download('monthly_TADA_report.xlsx');    
            
        }
        
                 
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
        return view('merchandiserattendances.attendanceTopSheet', compact('regions', 'Months', 'TodayDate', 'Years', 'depots'));
                 
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
        return view('merchandiserattendances.attendance', compact('regions', 'Months', 'TodayDate', 'Years', 'depots'));
                 
    }
}

