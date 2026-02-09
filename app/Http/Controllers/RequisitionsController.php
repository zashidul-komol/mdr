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
use App\Models\MdrInformation;
use App\Models\RequisitionLog;
use App\Models\Role;
use App\Models\Settlement;
use App\Models\ReportingSequenceDetail;
use App\Models\ReportingSequence;
use App\Models\Product;
use App\Models\Measurement;
use App\Models\Size;
use App\Models\shop;
use App\Models\Stage;
use App\Traits\DocumentsUpload;
use App\Traits\HasStageExists;
use App\Traits\SettlementCreateCloseData;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Region;
use App\Models\Distributor;
use App\Models\Depot;
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
use Illuminate\Support\Facades\Storage;

//use App\Http\Controllers\RequisitionsController;

class RequisitionsController extends Controller {
	use DocumentsUpload, HasStageExists, SettlementCreateCloseData;
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
			$user_id = auth()->user()->id;
	      	$reportToApplications = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	            'distributor'=>function($q){
	                return $q->select('*');
	            },
	            'region'=>function($q){
	                return $q->select('*');
	            },
	            'depot'=>function($q){
	                return $q->select('*');
	            },
	            

	        ])
	        ->where('report_to',$user_id)
	        ->where('status', 'pending')
	        ->where('application_status','<>', 'return')
	        ->get(); 

	    //dd($reportToApplications->toArray());

	           
        return view('requisitions.index', compact('reportToApplications'));
        //return view('requisitions.index');
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
        //$NowMaxNo	= $MaxID + 1;
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

        //$products 		= Product::orderBy('name', 'asc')->where('department_id', $AuthDeptID)->pluck('name','id');
        //dd($products);
        return view('requisitions.create', compact('users', 'CurrentDate', 'departments', 'sections', 'designations', 'distributor', 'distributorsUser'));
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
			'dressUp' => 'required',
			'physicalStrenth' => 'required',
			'pdfMerchendising' => 'required',
			'applicant_mobile' => 'required|min:12|max:12|string|unique:mdr_informations',
			'applicant_image' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // only validate if sent
			'applicant_cv'    => 'nullable|file|mimes:pdf,doc,docx|max:5120',
			'certificate'     => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
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
		$fileName = null;
		$applicantCV = null;
		$certificate = null;
		if ($request->hasFile('applicant_image')) {
			Storage::disk('public')->makeDirectory('applicantImages');
			$file = $request->file('applicant_image');

			$fileName = time().'_img.'.$file->getClientOriginalExtension();
			$file->storeAs('applicantImages', $fileName, 'public');

			$data['applicant_image'] = $fileName; // or 'applicantImages/'.$fileName
		}

		if ($request->hasFile('applicant_cv')) {
			Storage::disk('public')->makeDirectory('applicantCV');
			$file = $request->file('applicant_cv');

			$applicantCV = time().'_cv.'.$file->getClientOriginalExtension();
			$file->storeAs('applicantCV', $applicantCV, 'public');

			$data['applicant_cv'] = $applicantCV;
		}

		if ($request->hasFile('certificate')) {
			Storage::disk('public')->makeDirectory('Certificate');
			$file = $request->file('certificate');

			$certificate = time().'_cert.'.$file->getClientOriginalExtension();
			$file->storeAs('Certificate', $certificate, 'public');

			$data['certificate'] = $certificate;
		}
		
		if(ReportingSequence::where('user_id', $authUser)->exists()){
			$requisition_Request_data = $request->except('_method', '_token');
	        $user_data	= Auth::user();
	        $Region_ID = Employee::where('id', $user_data->employee_id)->first();
	        $region = $Region_ID->region_id;
	        $DepotID = $Region_ID->depot_id;
	        //dd($DepotID);
	        $reporting_sequence	= ReportingSequenceDetail::where('user_id', auth()->user()->id)
	        ->where('sequence', '=', 1)
	        ->value('report_to');
	        
	        $application_data['user_id']	= $user_data->id;
	        $application_data['employee_id']	= $user_data->employee_id;
	        $application_data['report_to']	= $reporting_sequence;
	        $application_data['depot_id']	= $DepotID;
	        $application_data['region_id']	= $region;
	        $application_data['distributor_id']	= $data['distributor_id'];
	        $application_data['sequence']	= 1;
	        $application_data['date']	= Carbon::now();
	        $application_data['application_status']	= 'pending';
	        $application_data['status']	= 'pending';
	        $application_data['created_at']	= Carbon::now();
	        $application_data['updated_at']	= Carbon::now();
	        


	        //insert data in Applications table
	        $applicationData = Application::create($application_data);

	        $MDRDate = \Carbon\Carbon::parse($request->effectivedate)->format('y-m-d');

			$date = explode("-", $MDRDate);
	        $year = $date[0];
	        $month = $date[1];
	        $day = $date[2];

	        $MDR_IDCARD  = 'MDR'.$year.$month.$applicationData->id ;
			//dd($MDR_ID);

	        //dd($applicationData);
	        //insert data in ApplicationDetails table
	        
	        if ($applicationData) {

	            $applicationDetail_data['application_id']	= $applicationData->id;
		        $applicationDetail_data['employee_id']	= $user_data->employee_id;
		        $applicationDetail_data['distributor_id']	= $data['distributor_id'];
		        $applicationDetail_data['applicant_name']	= $data['name'];
		        $applicationDetail_data['applicant_fathers_name']	= $data['applicant_fathers_name'];
		        $applicationDetail_data['applicant_address']	= $data['applicant_address'];
		        $applicationDetail_data['applicant_mobile']	= $data['applicant_mobile'];
		        $applicationDetail_data['applicant_email']	= $data['email'];
		        $applicationDetail_data['nid']	= $data['nid'];
		        $applicationDetail_data['applicant_education']	= $data['education'];
		        $applicationDetail_data['date_of_birth']	= $DateOfBirth;
		        $applicationDetail_data['height_feet']	= $data['height_feet'];
		        $applicationDetail_data['height_inch']	= $data['height_inch'];
		        $applicationDetail_data['dressUp']	= $data['dressUp'];
		        $applicationDetail_data['physicalStrenth']	= $data['physicalStrenth'];
		        $applicationDetail_data['pdfMerchendising']	= $data['pdfMerchendising'];
		        $applicationDetail_data['rating']	= $data['rating'];
		        //$applicationDetail_data['appearance']	= $data['appearance'];
		        $applicationDetail_data['effectivedate']	= $EffectiveDate;
		        $applicationDetail_data['applicant_image']	= $fileName;
		        $applicationDetail_data['applicant_cv']	= $applicantCV;
		        $applicationDetail_data['certificate']	= $certificate;
		        $applicationDetail_data['remarks']	= $data['remarks'];
		        $applicationDetail_data['created_at']	= Carbon::now();
		        $applicationDetail_data['updated_at']	= Carbon::now(); 

		        $applicationDetailsData = ApplicationDetail::create($applicationDetail_data);

		        $mdrInformation_data['mdr_idcard']	= $MDR_IDCARD;
		        $mdrInformation_data['application_id']	= $applicationData->id;
		        $mdrInformation_data['employee_id']	= $user_data->employee_id;
		        $mdrInformation_data['distributor_id']	= $data['distributor_id'];
		        $mdrInformation_data['region_id']	= $region;
		        $mdrInformation_data['depot_id']	= $DepotID;
		        $mdrInformation_data['applicant_name']	= $data['name'];
		        $mdrInformation_data['applicant_fathers_name']	= $data['applicant_fathers_name'];
		        $mdrInformation_data['applicant_address']	= $data['applicant_address'];
		        $mdrInformation_data['applicant_mobile']	= $data['applicant_mobile'];
		        $mdrInformation_data['applicant_email']	= $data['email'];
		        $mdrInformation_data['nid']	= $data['nid'];
		        $mdrInformation_data['applicant_education']	= $data['education'];
		        $mdrInformation_data['date_of_birth']	= $DateOfBirth;
		        $mdrInformation_data['height_feet']	= $data['height_feet'];
		        $mdrInformation_data['height_inch']	= $data['height_inch'];
		        $mdrInformation_data['dressUp']	= $data['dressUp'];
		        $mdrInformation_data['physicalStrenth']	= $data['physicalStrenth'];
		        $mdrInformation_data['pdfMerchendising']	= $data['pdfMerchendising'];
		        $mdrInformation_data['rating']	= $data['rating'];
		        //$applicationDetail_data['appearance']	= $data['appearance'];
		        $mdrInformation_data['effectivedate']	= $EffectiveDate;
		        $mdrInformation_data['applicant_image']	= $fileName;
		        $mdrInformation_data['applicant_cv']	= $applicantCV;
		        $mdrInformation_data['certificate']	= $certificate;
		        $mdrInformation_data['remarks']	= $data['remarks'];
		        $mdrInformation_data['status']	= 'pending';
		        $mdrInformation_data['created_at']	= Carbon::now();
		        $mdrInformation_data['updated_at']	= Carbon::now(); 

		        $mdrInformationData = MdrInformation::create($mdrInformation_data);

		        
		        $applicationLog_data['application_id']	= $applicationData->id;
		        $applicationLog_data['user_id']	= $user_data->id;
		        $applicationLog_data['employee_id']	= $user_data->employee_id;
		        $applicationLog_data['remarks']	= $data['remarks'];
		        $applicationLog_data['created_at']	= Carbon::now();
		        $applicationLog_data['updated_at']	= Carbon::now(); 

		        $applicationLogsData = ApplicationLog::create($applicationLog_data);

		        $ReportTo_Mail	 = User::where('id', $reporting_sequence)
		        	->value('email');
		         
		        //$admin_email     = ['zashidul@polarbd.com'];
	            $admin_email     = $ReportTo_Mail;
	            
	            Mail::to($admin_email)->send(new ReqRaisedMail($usersInfo));
	            //Mail::to($admin_email)->send(new ContactMail($data));
	            
	            $message = "You have successfully created the Application....";
		            return redirect()->route('requisitions.create')
		                ->with('flash_success', $message);

	        } else {
	            $message = "Something wrong!! Please try again";
	            return redirect()->route('requisitions.create', [])
	                ->with('flash_danger', $message);
	        }
		}else{
			$message = "Your reporting sequence was not created. Please contact with software administrator.....";
	            return redirect()->route('requisitions.create', [])
	                ->with('flash_danger', $message);
		}

    }

    /**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */

    /**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function directsave(Request $request) {
		
		/*
		 $request->validate([
            'applicant_name' => 'required',
            'applicant_fathers_name' => 'required',
            'applicant_address' => 'required',
			'education' => 'required',
			'applicant_image' => 'required',
			'applicant_cv' => 'required',
			//'remarks' => 'required',
			'email' => 'required|string|email|max:255',
			'mobile' => 'required|string|size:11|nullable',
        ]);
        */
		 //dd($request);

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
			$upload = $request->file('applicant_image');
			//dd($upload);
			$directory = '../public' . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'applicantImages' . DIRECTORY_SEPARATOR;
			$fileName = time() . '_avatar.' . $upload->getClientOriginalExtension();
			$imageUrl = $directory . $fileName;
			$imgUploaded = Image::make($upload);
			$imgUploaded->resize(150, 150)->save($imageUrl);
			/*
            $file = $request->file('applicant_image');
            $extension =$file->getClientOriginalExtension();
            $filename = time().'.'.$extension;
            $file->move('storage/applicantImages/', $filename);
            $data['applicant_image'] = $filename;
            $imgUploaded = Image::make($file);
            $imgUploaded->resize(150, 150)->save($filename);
            */
        }
        if($request->file('applicant_cv')){
            $file = $request->file('applicant_cv');
            $extension =$file->getClientOriginalExtension();
            $applicantCV = time().'.'.$extension;
            $file->move('storage/applicantCV/', $applicantCV);
            $data['applicant_cv'] = $applicantCV;
        }
        //dd($file);
		if(ReportingSequence::where('user_id', $authUser)->exists()){
			$requisition_Request_data = $request->except('_method', '_token');
	        $user_data	= Auth::user();
	        $reporting_sequence	= ReportingSequenceDetail::where('user_id', auth()->user()->id)
	        ->where('sequence', '=', 1)
	        ->value('report_to');
	        
	        $application_data['user_id']	= $user_data->id;
	        $application_data['employee_id']	= $user_data->employee_id;
	        $application_data['report_to']	= $reporting_sequence;
	        $application_data['sequence']	= 1;
	        $application_data['date']	= Carbon::now();
	        $application_data['application_status']	= 'pending';
	        $application_data['status']	= 'pending';
	        $application_data['created_at']	= Carbon::now();
	        $application_data['updated_at']	= Carbon::now();
	        


	        //insert data in Applications table
	        $applicationData = Application::create($application_data);
	        //dd($applicationData);
	        //insert data in ApplicationDetails table
	        
	        if ($applicationData) {

	            $applicationDetail_data['application_id']	= $applicationData->id;
		        $applicationDetail_data['employee_id']	= $user_data->employee_id;
		        $applicationDetail_data['applicant_name']	= $data['applicant_name'];
		        $applicationDetail_data['applicant_fathers_name']	= $data['applicant_fathers_name'];
		        $applicationDetail_data['applicant_address']	= $data['applicant_address'];
		        $applicationDetail_data['applicant_mobile']	= $data['applicant_mobile'];
		        $applicationDetail_data['applicant_email']	= $data['email'];
		        $applicationDetail_data['applicant_education']	= $data['education'];
		        $applicationDetail_data['applicant_image']	= $fileName;
		        $applicationDetail_data['applicant_cv']	= $applicantCV;
		        $applicationDetail_data['remarks']	= $data['remarks'];
		        $applicationDetail_data['created_at']	= Carbon::now();
		        $applicationDetail_data['updated_at']	= Carbon::now(); 

		        $applicationDetailsData = ApplicationDetail::create($applicationDetail_data);

		        
		        $applicationLog_data['application_id']	= $applicationData->id;
		        $applicationLog_data['user_id']	= $user_data->id;
		        $applicationLog_data['employee_id']	= $user_data->employee_id;
		        $applicationLog_data['remarks']	= $data['remarks'];
		        $applicationLog_data['created_at']	= Carbon::now();
		        $applicationLog_data['updated_at']	= Carbon::now(); 

		        $applicationLogsData = ApplicationLog::create($applicationLog_data);

		        $ReportTo_Mail	 = User::where('id', $reporting_sequence)
		        	->value('email');
		         
		        //$admin_email     = ['zashidul@polarbd.com'];
	            $admin_email     = $ReportTo_Mail;
	            
	            Mail::to($admin_email)->send(new ReqRaisedMail($usersInfo));
	            //Mail::to($admin_email)->send(new ContactMail($data));
	            
	            $message = "You have successfully created the Application....";
		            return redirect()->route('requisitions.create')
		                ->with('flash_success', $message);

	        } else {
	            $message = "Something wrong!! Please try again";
	            return redirect()->route('requisitions.create', [])
	                ->with('flash_danger', $message);
	        }
		}else{
			$message = "Your reporting sequence was not created. Please contact with software administrator.....";
	            return redirect()->route('requisitions.create', [])
	                ->with('flash_danger', $message);
		}

    }

    /**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */

	public function updaterequisition(Request $request) {
		//dd('Komol');
		$data = $request->except('_token');
		$user_id = auth()->user()->id;
		$Application_id  = $request->application_id;
		//dd($data);
		//dd($request);
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
	        //dd($users);
		if($request->application_status == 'return'){

			//dd($users->toArray());
			$application_owner = Application::where('id', $Application_id)->first();
			//dd($application_owner);
			$reporting_sequence  = ReportingSequenceDetail::where('user_id', $application_owner->user_id)->where('report_to', auth()->user()->id)->first();
			//dd($reporting_sequence);
			$application_reportTo = ReportingSequenceDetail::where('user_id', $reporting_sequence->user_id)
			->where('sequence', '<', $reporting_sequence->sequence)
			->orderBy('sequence', 'desc')
			->limit(1)
			->first();
			//dd($application_reportTo);
			if($application_reportTo != ''){
				$applicationUpdate = Application::where('id', $Application_id)->update([
					'report_to' => $application_reportTo->report_to,
					'application_status' => 'return',
					'status' => 'pending',
					'updated_at' => Carbon::now(),
				]);

				$application_log['application_id']	= $Application_id;
		        $application_log['user_id']	= auth()->user()->id;
		        $application_log['employee_id']	= $users->employee_id;
		        $application_log['remarks']	= $request->remarks;
		        $application_log['created_at']	= Carbon::now();
		        $application_log['updated_at']	= Carbon::now();
		        
				$Applications_log  = ApplicationLog::insert($application_log);
				//dd($requisition_log);
				$ReqOwner_Mail	 = User::where('id', $reporting_sequence->user_id)
		        	->value('email');
		        $ReqReportTo_Mail	 = User::where('id', $reporting_sequence->report_to)
		        	->value('email');
				$admin_email     = $ReqOwner_Mail;
				//$admin_email     = ['zashidul@polarbd.com'];
				Mail::to($admin_email)->send(new ReturnMail($users));
				$message = "You have successfully Return the Application..";
		            return redirect()->route('requisitions.index')
		                ->with('flash_success', $message);
			}else{
				
				$applicationUpdate = Application::where('id', $Application_id)->update([
					'report_to' => $application_owner->user_id,
					'application_status' => 'return',
					'status' => 'pending',
					'updated_at' => Carbon::now(),
				]);

				$application_log['application_id']	= $Application_id;
		        $application_log['user_id']	= auth()->user()->id;
		        $application_log['employee_id']	= $users->employee_id;
		        $application_log['remarks']	= $request->remarks;
		        $application_log['created_at']	= Carbon::now();
		        $application_log['updated_at']	= Carbon::now();
		        
				$ApplicationLogs  = ApplicationLog::insert($application_log);
				//dd($requisition_log);
				$ReqOwner_Mail	 = User::where('id', $reporting_sequence->user_id)
		        	->value('email');
		        $admin_email     = $ReqOwner_Mail;
		        //$admin_email     = ['zashidul@polarbd.com'];
				Mail::to($admin_email)->send(new ReturnMail($users));

				$message = "You have successfully Return the Application..";
		            return redirect()->route('requisitions.index')
		                ->with('flash_success', $message);
				
			}
			//dd($requisition_reportTo->sequence);
		}elseif($request->application_status == 'cancel'){

			$application_owner = Application::where('id', $Application_id)->first();
			//$Req_Sequence   = $application_owner['sequence'];
			//dd($application_owner->sequence);
			$reporting_sequence  = ReportingSequenceDetail::where('user_id', $application_owner->user_id)->where('report_to', auth()->user()->id)->first();

			$application_reportTo = ReportingSequenceDetail::where('user_id', $application_owner->user_id)
			->where('sequence', '>', $reporting_sequence->sequence)
			->orderBy('sequence', 'asc')
			->limit(1)
			->first();

			$applicationUpdate = Application::where('id', $Application_id)->update([
					'report_to' => auth()->user()->id,
					'application_status' => 'cancelled',
					'status' => 'cancelled',
					'updated_at' => Carbon::now(),
				]);

					$application_log['application_id']	= $Application_id;
			        $application_log['user_id']	= auth()->user()->id;
			        $application_log['employee_id']	= $users->employee_id;
			        $application_log['remarks']	= $request->remarks;
			        $application_log['created_at']	= Carbon::now();
			        $application_log['updated_at']	= Carbon::now();

					
					$ApplicationLogs  = ApplicationLog::insert($application_log);
					//dd($requisition_log);

					$application_owner = Application::where('id', $Application_id)->first();
					//dd($application_owner);
					$AppOwner_Mail	 = User::where('id', $application_owner->user_id)
		        	->value('email');
			        //$ReqReportTo_Mail	 = User::where('id', $application_owner->report_to)
			        //	->value('email');
					$admin_email     = $AppOwner_Mail;
					//$admin_email     = ['zashidul@polarbd.com'];
					Mail::to($admin_email)->send(new CancelMail($users));
			
					$message = "You have successfully Cancelled the Requisition..";
			            return redirect()->route('requisitions.index')
			                ->with('flash_success', $message);
		}elseif($request->application_status == 'approve'){

			$application_owner = Application::where('id', $Application_id)->first();
			$reporting_sequence  = ReportingSequenceDetail::where('user_id', $application_owner->user_id)->where('report_to', auth()->user()->id)->first();
			$application_reportTo = ReportingSequenceDetail::where('user_id', $application_owner->user_id)
			->where('sequence', '>', $reporting_sequence->sequence)
			->orderBy('sequence', 'asc')
			->limit(1)
			->first();
			//dd($requisition_reportTo);
			
			if($application_reportTo != ''){
				$applicationUpdate = Application::where('id', $Application_id)->update([
						'report_to' => $application_reportTo->report_to,
						'application_status' => 'processing',
						'status' => 'pending',
						'updated_at' => Carbon::now(),
					]);

				

						$application_log['application_id']	= $Application_id;
				        $application_log['user_id']	= auth()->user()->id;
				        $application_log['employee_id']	= $users->employee_id;
				        $application_log['remarks']	= $request->remarks;
				        $application_log['created_at']	= Carbon::now();
				        $application_log['updated_at']	= Carbon::now();

										        
						$ApplicationsLogs  = ApplicationLog::insert($application_log);
						//dd($requisition_log);
						$ReportTo_Mail	 = User::where('id', $application_reportTo->report_to)
		        		->value('email');
						$admin_email     = $ReportTo_Mail;
						//$admin_email     = ['zashidul@polarbd.com'];
						Mail::to($admin_email)->send(new ForwardMail($users));

						$message = "You have successfully Forward the Requisition..";
				            return redirect()->route('requisitions.index')
				                ->with('flash_success', $message);
				//dd('Not Null');
			}else{
				$applicationUpdate = Application::where('id', $Application_id)->update([
						'report_to' => auth()->user()->id,
						'application_status' => 'approved',
						'status' => 'approved',
						'updated_at' => Carbon::now(),
					]);

						$mdrInformationUpdate = MdrInformation::where('application_id', $Application_id)->update([
						'status' => 'active',
						'updated_at' => Carbon::now(),
						]);

						$application_log['application_id']	= $Application_id;
				        $application_log['user_id']	= auth()->user()->id;
				        $application_log['employee_id']	= $users->employee_id;
				        $application_log['remarks']	= $request->remarks;
				        $application_log['created_at']	= Carbon::now();
				        $application_log['updated_at']	= Carbon::now();
				        
						$ApplicationLogs  = ApplicationLog::insert($application_log);
						//dd($requisition_log);

						$application_owner = Application::where('id', $Application_id)->first();
						//dd($application_owner);
						$AppOwner_Mail	 = User::where('id', $application_owner->user_id)
			        	->value('email');
				        $ReqReportTo_Mail	 = User::where('id', $application_owner->report_to)
				        	->value('email');
						$admin_email     = $AppOwner_Mail;

						$usersInfo = Application::with([
				            'distributor'=>function($q){
				                return $q->select('*');
				            },
				            'application_details'=>function($q){
				                return $q->select('*');
				            },
				            
				            
				        ])
				        ->where('id',$Application_id)
				        ->get();
				        //dd($usersInfo->toArray());
						//$Subject = ::where('id', $Application_id)->first();
						//$admin_email     = ['zashidul@polarbd.com'];
						Mail::to($admin_email)->send(new ApproveMail($usersInfo));

						$message = "You have successfully Approved the Application..";
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
	 * Show the form for editing the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Display the specified resource.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		dd($id);
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

		
		return view('requisitions.edit', compact('ApplicationDetails', 'distributorsUser', 'EmployeeName', 'DepotName', 'RegionNameQry'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function updateOLD(Request $request, $id) {
		//dd($request);
		dd('Komol-MDR-1');
		$EffectiveDate = \Carbon\Carbon::parse($request->effectivedate)->format('Y-m-d');
		//dd($EffectiveDate);
		//{{ \Carbon\Carbon::parse($user->from_date)->format('d/m/Y')}}
		$request->validate([
            'applicant_image' => 'required',
			'applicant_cv' => 'required',
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
	        $user_data	= Auth::user();
	        $Region_ID = Employee::where('id', $user_data->employee_id)->first();
	        $region = $Region_ID->region_id;
	        //dd($region);
	        $reporting_sequence	= ReportingSequenceDetail::where('user_id', auth()->user()->id)
	        ->where('sequence', '=', 1)
	        ->value('report_to');
	        
	        $application_data['user_id']	= $user_data->id;
	        $application_data['employee_id']	= $user_data->employee_id;
	        $application_data['report_to']	= $reporting_sequence;
	        $application_data['region_id']	= $region;
	        $application_data['distributor_id']	= $data['distributor_id'];
	        $application_data['sequence']	= 1;
	        $application_data['date']	= Carbon::now();
	        $application_data['application_status']	= 'pending';
	        $application_data['status']	= 'pending';
	        $application_data['created_at']	= Carbon::now();
	        $application_data['updated_at']	= Carbon::now();
	        


	        //insert data in Applications table
	        $applicationData = Application::where('id', $id)->update($application_data);
	        //$applicationData = Application::create($application_data);
	        //dd($applicationData);
	        //insert data in ApplicationDetails table
	        
	        if ($applicationData) {

	            $applicationDetail_data['application_id']	= $id;
		        $applicationDetail_data['employee_id']	= $user_data->employee_id;
		        $applicationDetail_data['distributor_id']	= $data['distributor_id'];
		        $applicationDetail_data['applicant_name']	= $data['applicant_name'];
		        $applicationDetail_data['applicant_fathers_name']	= $data['applicant_fathers_name'];
		        $applicationDetail_data['applicant_address']	= $data['applicant_address'];
		        $applicationDetail_data['applicant_mobile']	= $data['applicant_mobile'];
		        $applicationDetail_data['applicant_email']	= $data['applicant_email'];
		        $applicationDetail_data['nid']	= $data['nid'];
		        $applicationDetail_data['applicant_education']	= $data['applicant_education'];
		        $applicationDetail_data['rating']	= $data['rating'];
		        //$applicationDetail_data['appearance']	= $data['appearance'];
		        $applicationDetail_data['effectivedate']	= $EffectiveDate;
		        $applicationDetail_data['applicant_image']	= $fileName;
		        $applicationDetail_data['applicant_cv']	= $applicantCV;
		        $applicationDetail_data['certificate']	= $certificate;
		        $applicationDetail_data['remarks']	= $data['remarks'];
		        $applicationDetail_data['created_at']	= Carbon::now();
		        $applicationDetail_data['updated_at']	= Carbon::now(); 

		        $applicationDetailsData = ApplicationDetail::where('application_id', $id)->update($applicationDetail_data);
		        //$applicationDetailsData = ApplicationDetail::create($applicationDetail_data);

		        
		        $applicationLog_data['application_id']	= $id;
		        $applicationLog_data['user_id']	= $user_data->id;
		        $applicationLog_data['employee_id']	= $user_data->employee_id;
		        $applicationLog_data['remarks']	= $data['remarks'];
		        $applicationLog_data['created_at']	= Carbon::now();
		        $applicationLog_data['updated_at']	= Carbon::now(); 

		        $applicationLogsData = ApplicationLog::where('application_id', $id)->update($applicationLog_data);
		        //$applicationLogsData = ApplicationLog::create($applicationLog_data);

		        $ReportTo_Mail	 = User::where('id', $reporting_sequence)
		        	->value('email');
		         
		        //$admin_email     = ['zashidul@polarbd.com'];
	            $admin_email     = $ReportTo_Mail;
	            
	            Mail::to($admin_email)->send(new ReqRaisedMail($usersInfo));
	            //Mail::to($admin_email)->send(new ContactMail($data));
	            
	            $message = "You have successfully Updated the Application....";
		            return redirect()->route('requisitions.returned')
		                ->with('flash_success', $message);

	        } else {
	            $message = "Something wrong!! Please try again";
	            return redirect()->route('requisitions.returned', [])
	                ->with('flash_danger', $message);
	        }
		}else{
			$message = "Your reporting sequence was not created. Please contact with software administrator.....";
	            return redirect()->route('requisitions.returned', [])
	                ->with('flash_danger', $message);
		}

    }

    public function update(Request $request, $id) {
		//dd($request);
		//dd('Komol-MDR-2');
		$data = $request->except('_method', '_token', 'applicant_name');
		//dd($data);
		$Mobile  = $request->only('applicant_mobile', 'distributor_id', 'employee_id');
		//dd($Mobile);
		$ApplicationID  = $request['application_id'];
		//dd($ApplicationID);
		$authUser  = auth()->user()->id;
		//$data['resign_letter'] = $fileName;

		$MDRInformation = MdrInformation::where('application_id', $ApplicationID)->update($data);
		$Application_Details = ApplicationDetail::where('application_id', $ApplicationID)->update($Mobile);
        if ($MDRInformation) {
            $message = "You have successfully updated";
            return redirect()->route('requisitions.activelist', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('requisitions.activelist', [])
                ->with('flash_warning', $message);
        }

    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function approved() {
			$user_id = auth()->user()->id;
			$userDetails = User::where('id', auth()->user()->id)->get();
	      	//dd($userDetails);
	      	$New_Section_ID = $userDetails[0]->section_id;
	      	//dd($New_Section_ID);
	      	
	    	$reportToApplications = Application::with([
	            'distributor'=>function($q){
	                return $q->select('*');
	            },
	            'region'=>function($q){
	                return $q->select('*');
	            },
	            'depot'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'approved')
	        ->orderBy('id', 'desc')
	        ->get();

	    //dd($reportToApplications->toArray());
        return view('requisitions.approved', compact('reportToApplications'));
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function mdrInactive() {
			$user_id = auth()->user()->id;
			$userDetails = User::where('id', auth()->user()->id)->get();
	      	//dd($userDetails);
	      	$New_Section_ID = $userDetails[0]->section_id;
	      	//dd($New_Section_ID);
	      	
	    	$reportToApplications = MdrInformation::with([
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
	        ->where('status', 'inactive')
	        ->orderBy('id', 'desc')
	        ->get();

	    //dd($reportToApplications->toArray());
        return view('requisitions.inactive', compact('reportToApplications'));
    }

/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function mdrActive() {
			$user_id = auth()->user()->id;
			$userDetails = User::where('id', auth()->user()->id)->get();
	      	//dd($userDetails);
	      	$New_Section_ID = $userDetails[0]->section_id;
	      	//dd($New_Section_ID);
	      	
	    	$reportToApplications = MdrInformation::with([
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
	        ->where('status', 'active')
	        ->orderBy('id', 'desc')
	        ->get();

	    //dd($reportToApplications->toArray());
        return view('requisitions.activelist', compact('reportToApplications'));
    }
    /**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function officerActiveMDR() {
			$user_id = auth()->user()->id;
			$userDetails = User::where('id', auth()->user()->id)->get();
	      	//dd($userDetails);
	      	$New_Section_ID = $userDetails[0]->section_id;
	      	//dd($New_Section_ID);
	      	
	    	$reportToApplications = MdrInformation::with([
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
	        ->where('status', 'active')
	        ->where('employee_id', $userDetails[0]->employee_id)
	        ->orderBy('id', 'desc')
	        ->get();

	    //dd($reportToApplications->toArray());
        return view('requisitions.officerActiveMDR', compact('reportToApplications'));
    }

/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function explore() {
			//$user_id = auth()->user()->id;
			$reportToApplications = RequisitionDetail::with([
	            'requisition'=>function($q){
	                return $q->select('*');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'product'=>function($q){
	                return $q->select('*');
	            },
	            'product.subcategory'=>function($q){
	                return $q->select('*');
	            },
	            	            
	        ])
	        
			//->where('requisitions.status', '=', 'approved')
			->where('requisitions.status', '=', 'approved')
			->join('requisitions', 'requisitions.id', '=', 'requisition_details.requisition_id')
			//->join('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
			//->join('products', 'products.id', '=', 'requisition_details.product_id')
			->orderBy('requisition_id', 'desc')
	        ->get();
	    
        /*
        $reportToApplications = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToApplications);
        //dd($reportToApplications->product->subcategory_id);
        return view('requisitions.explore', compact('reportToApplications'));
        //return view('requisitions.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function cancelled() {
			$user_id = auth()->user()->id;
	      	$userDetails = User::where('id', auth()->user()->id)->get();
	      	$reportToApplications = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'cancelled')
	        ->orderBy('id', 'desc')
	        ->get(); 
            
        
        /*
        $reportToApplications = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToApplications->toArray());
        return view('requisitions.cancelled', compact('reportToApplications'));
        //return view('requisitions.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function hold() {
			$user_id = auth()->user()->id;
	      	//dd($user_id);
	      	$reportToApplications = Application::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'hold')
	        ->where('report_to', $user_id)
	        ->orderBy('id', 'desc')
	        ->get(); 
            
        
        /*
        $reportToApplications = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToApplications->toArray());
        return view('requisitions.hold', compact('reportToApplications'));
        //return view('requisitions.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */

		public function submitted() {
			$user_id = auth()->user()->id;
	      	$userDetails = User::where('id', auth()->user()->id)->get();
	      	$reportToApplications = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	            'distributor'=>function($q){
	                return $q->select('*');
	            },
	            'region'=>function($q){
	                return $q->select('*');
	            },
	            

	        ])
	        ->where('status', 'pending')
	        ->where('application_status', 'pending')
	        ->where('user_id', $user_id)
	        ->orderBy('id', 'desc')
	        ->get(); 
            
        
        /*
        $reportToApplications = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToApplications->toArray());
        return view('requisitions.submitted', compact('reportToApplications'));
        //return view('requisitions.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */

	public function returned() {
			$user_id = auth()->user()->id;
	      	$userDetails = User::where('id', auth()->user()->id)->get();
	      	$reportToApplications = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('application_status', 'return')
	        ->orderBy('id', 'desc')
	        ->get(); 
        
        /*
        $reportToApplications = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToApplications->toArray());
        return view('requisitions.returned', compact('reportToApplications'));
        //return view('requisitions.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function processing() {
			$user_id = auth()->user()->id;
	      	$userDetails = User::where('id', auth()->user()->id)->get();
	      	$user_data	= Auth::user();
	        $Region_ID = Employee::where('id', $user_data->employee_id)->first();
	        $region = $Region_ID->region_id;
	      	$reportToApplications = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'mdrInformation'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	            'employee'=>function($q){
	                return $q->select('*');
	            },
	            'depot'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('application_status', 'processing')
	        //->where('region_id', $region)
	        ->orderBy('id', 'desc')
	        ->get(); 
            
        
        /*
        $reportToApplications = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToApplications->toArray());
        return view('requisitions.processing', compact('reportToApplications'));
        //return view('requisitions.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//dd($id);
		$application = Application::findOrFail($id);
		if ($application) {

			
			$application_details = ApplicationDetail::where('application_id', $id)->exists();
			$documents = ApplicationDetail::where('application_id', $id)->get();
			//dd($documents);
			if ($documents->count()) {
				foreach ($documents as $key => $document) {
					\Storage::delete('applicantCV /' . $documents[0]->applicant_cv );
					//$document->delete();
				}
				foreach ($documents as $key => $document) {
					\Storage::delete('applicantImages /' . $documents[0]->applicant_image );
					//$document->delete();
				}
				foreach ($documents as $key => $document) {
					\Storage::delete('Certificate /' . $documents[0]->certificate );
					//$document->delete();
				}
				
			}
			ApplicationDetail::where('application_id', $id)->delete();
			ApplicationLog::where('application_id', $id)->delete();
			$application->delete();
			$message = "Successfully deleted this Application.";
			return redirect()->route('requisitions.submitted')->with('flash_success', $message);
		} else {
			$message = "Something wrong!! Please try again";
			return redirect()->route('requisitions.submitted')->with('flash_danger', $message);
		}
	}

	public function approveRequisitionDownload($id) {
		//dd($id);
		$AppointmentLetter = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'distributor'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('id', $id)
	        ->orderBy('id', 'desc')
	        ->get(); 
	        //dd($AppointmentLetter->toArray());
	        //dd($AppointmentLetter[0]->distributor_id[0]);
	        $DistributorId = ApplicationDetail::select('distributor_id')->where('application_id', $id)->limit(1)
				->first();
	        //dd($DistributorId->distributor_id);
			$DistributorCode = shop::select('code')->where('id', $DistributorId->distributor_id)->limit(1)
				->first();
			//dd($DistributorCode->code);
		   
		$pdf = \domPDF::loadView('pdf.appointmentLetter', compact('id', 'AppointmentLetter', 'DistributorCode'));
        return $pdf->setPaper('a4', 'portrait')->download('appointmentLetter.pdf');

         
    }

    public function download() {
    	//dd('Komol');
        return (new ActiveMDRExport())->download('ActiveMDR.xlsx');
    }

    public function ApprovedMDRdownload() {
    	//dd('Komol');\
    	return (new AprovedMDRExport())->download('ApprovedMDR.xlsx');
    }

    public function mdragreementDownload($id) {
		//dd($id);
		$AppointmentLetter = Application::with([
	            'application_details'=>function($q){
	                return $q->select('*');
	            },
	            'distributor'=>function($q){
	                return $q->select('*');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('id', $id)
	        ->orderBy('id', 'desc')
	        ->get(); 
	        //dd($AppointmentLetter->toArray());
	        //dd($AppointmentLetter[0]->distributor_id[0]);
	        $DistributorId = ApplicationDetail::select('distributor_id')->where('application_id', $id)->limit(1)
				->first();
	        //dd($DistributorId->distributor_id);
			$DistributorCode = shop::select('code')->where('id', $DistributorId->distributor_id)->limit(1)
				->first();
			//dd($DistributorCode->code);

		//$pdf = \mPDF::loadView('pdf.deedpaper', compact('reqisition', 'item', 'settlement', 'orgins'))->download('deedpaper' . time() . '.pdf');
		   
		$pdf = \mPDF::loadView('pdf.mdragreement', compact('id', 'AppointmentLetter', 'DistributorCode'))->download('mdragreement.pdf');
        //return $pdf->setPaper('a4', 'portrait')->download('mdragreement.pdf');

         
    }

    public function resign_letter(Request $request, $id) {
    	//dd($id);
    	

		
		return view('requisitions.resign_letter', compact('id'));
	}

}
