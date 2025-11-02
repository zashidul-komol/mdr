<?php
namespace App\Http\Controllers;

use App\Brand;
use App\DepotUser;
use App\DistributorUser;
use App\DfReturn;
use App\Department;
use App\Designation;
use App\Section;
use App\Item;
use App\PhysicalVisit;
use App\Requisition;
use App\RequisitionDetail;
use App\RequisitionLog;
use App\Role;
use App\Settlement;
use App\ReportingSequenceDetail;
use App\ReportingSequence;
use App\Product;
use App\Size;
use App\Stage;
use App\Traits\DocumentsUpload;
use App\Traits\HasStageExists;
use App\Traits\SettlementCreateCloseData;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Auth;
use App\Mail\ReturnMail;
use App\Mail\ReqRaisedMail;
use App\Mail\ForwardMail;
use App\Mail\ApproveMail;
use App\Mail\HoldMail;
use App\Mail\CancelMail;
use Illuminate\Support\Facades\Mail;

class RequisitionsController extends Controller {
	use DocumentsUpload, HasStageExists, SettlementCreateCloseData;

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
			$user_id = auth()->user()->id;
	      	//dd($user_id);
	      	$reportToRequisitions = Requisition::with([
	            'department'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('report_to',$user_id)
	        ->where('status', 'pending')
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
        return view('requisitions.index', compact('reportToRequisitions'));
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
        //dd($users->toArray());
        $CurrentDate = Carbon::now();
        $MaxID = Requisition::max('id');
        $NowMaxNo	= $MaxID + 1;
        $AuthDeptID = Auth::user()->department_id;
        $departments = Department::pluck('name','id');
        $designations = Designation::pluck('title','id');
        $sections = Section::pluck('name','id');
		//dd($AuthDeptID);
        $products = Product::where('department_id', $AuthDeptID)->pluck('name','id');
        
		return view('requisitions.create', compact('users', 'CurrentDate', 'NowMaxNo', 'products', 'departments', 'sections', 'designations'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		
		$data = $request->except('_token');
		$authUser  = auth()->user()->id ;
		//dd($data);
		if(ReportingSequence::where('user_id', $authUser)->exists()){
			$requisition_Request_data = $request->except('details', '_method', '_token');
	        $requisitionDetailsRequest_Data = $request->only('details');
	        //dd($requisition_Request_data);
	        //$data['created_by'] = auth()->user()->id;
	        $user_data	= Auth::user();
	        //dd($user_data);
	        $request->validate([
	            'details.*.product_id' => 'required',
	            'details.*.requsition_quantity' => 'required',
	            
	        ]);
	        
	        $reporting_sequence	= ReportingSequenceDetail::where('user_id', auth()->user()->id)
	        ->where('sequence', '=', 1)
	        ->value('report_to');
	        
	        $requisition_data['user_id']	= $user_data->id;
	        $requisition_data['report_to']	= $reporting_sequence;
	        $requisition_data['sequence']	= 1;
	        $requisition_data['requisition_status']	= 'pending';
	        $requisition_data['date']	= Carbon::now();
	        $requisition_data['department_id']	= $user_data->department_id;
	        $requisition_data['section_id']	= $user_data->section_id;
	        $requisition_data['status']	= 'pending';


	        //insert data in requisitions table
	        $requisitionData = Requisition::create($requisition_data);
	        
	        //insert data in requisitionDetails table
	        
	        if ($requisitionData) {

	            $detials_data =[];
		        foreach($requisitionDetailsRequest_Data['details'] as $report){
		 
		             //$data['reporting_sequence_id'] = $reportingSequence->id;
		            $d_data['product_id'] 			= $report['product_id'];
		            //$d_data['stock'] 				= isset($report['stock'])?$report['stock']:null;
		            $d_data['requsition_quantity'] 	= $report['requsition_quantity'];
		            $d_data['unitprice'] 			= $report['unitprice'];
		            $d_data['totalprice'] 			= $report['totalprice'];
		            $d_data['remarks'] 				= $report['remarks'];
		            $d_data['department_id']		= $user_data->department_id;
		            $d_data['section_id'] 			= $user_data->section_id;
		            $d_data['present_stock'] 		= $report['PresentStock'];;
		            $d_data['approve_quantity'] 	= '0';
		            $d_data['created_at'] 	= Carbon::now();
		            $d_data['updated_at'] 	= Carbon::now();

		            $product_tags	= Product::where('id', $report['product_id'])
		        	->value('tags');

		        	if($product_tags == 'employees'){
		        		$d_data['machine_id'] = '0';
		        		$d_data['vehicle_id'] = '0';
		        		$d_data['employee_id'] = $report['particular_id'];
		        	}elseif($product_tags == 'vehicles'){
		        		$d_data['machine_id'] = '0';
		        		$d_data['vehicle_id'] = $report['particular_id'];
		        		$d_data['employee_id'] = '0';

		        	}elseif($product_tags == 'machines'){
		        		$d_data['machine_id'] = $report['particular_id'];
		        		$d_data['vehicle_id'] = '0';
		        		$d_data['employee_id'] = '0';

		        	}else{
		        		$d_data['machine_id'] = '0';
		        		$d_data['vehicle_id'] = '0';
		        		$d_data['employee_id'] = '0';
		        	}
		            
		            $detials_data[] = $d_data;
		            
		        }
		        
		        $requisitionDetails = $requisitionData->requisition_details()->createMany($detials_data);

		        $ReportTo_Mail	 = User::where('id', $reporting_sequence)
		        	->value('email');
		        //dd($ReportTo_Mail);
		        //$email['email']    = $ReportTo_Mail->email;	
		        //$ReqRaiseMail->email;
		        //dd($email);
			    $requisition_log['requisition_id']	= $requisitionData->id;
		        $requisition_log['user_id']	= auth()->user()->id;
		        $requisition_log['action_name']	= 'Prepared By  ';
		        $requisition_log['created_at']	= Carbon::now();
		        $requisition_log['updated_at']	= Carbon::now();
	        
				$RequisitionLogs  = RequisitionLog::insert($requisition_log);
		        	
		        //$ReqRaiseMail    = $ReportTo_Mail['email'];
	            //$admin_email     = ['mamun@polarbd.com','samir.paul@polarbd.com'];
	            $admin_email     = $ReportTo_Mail;
	            //$customer_email    = $ReportTo_Mail['email'];
	            //dd($customer_email);

	            Mail::to($admin_email)->send(new ReqRaisedMail($data));
	            //Mail::to($admin_email)->send(new ContactMail($data));
	            
	            $message = "You have successfully created the Requisition..";
		            return redirect()->route('requisitions.create')
		                ->with('flash_success', $message);

	        } else {
	            $message = "Something wrong!! Please try again";
	            return redirect()->route('requisitions.create', [])
	                ->with('flash_danger', $message);
	        }
		}else{
			$message = "Your reporting sequence was not created. Please contact the software administrator.....";
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
		$data = $request->except('_token');
		$user_id = auth()->user()->id;
		$Requisition_id  = $request->requisition_id;
		//dd($data);
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
		if($request->requisition_status == 'return'){

			//dd($users->toArray());
			$requisition_owner = Requisition::where('id', $Requisition_id)->first();
			//dd($requisition_owner);
			$reporting_sequence  = ReportingSequenceDetail::where('user_id', $requisition_owner->user_id)->where('report_to', auth()->user()->id)->first();
			//dd($reporting_sequence);
			$requisition_reportTo = ReportingSequenceDetail::where('user_id', $reporting_sequence->user_id)
			->where('sequence', '<', $reporting_sequence->sequence)
			->orderBy('sequence', 'desc')
			->limit(1)
			->first();
			//dd($requisition_reportTo);
			if($requisition_reportTo != ''){
				$requisitionUpdate = Requisition::where('id', $Requisition_id)->update([
					'report_to' => $requisition_reportTo->report_to,
					'requisition_status' => 'return',
					'status' => 'pending',
					'updated_at' => Carbon::now(),
				]);

				$requisition_log['requisition_id']	= $Requisition_id;
		        $requisition_log['user_id']	= auth()->user()->id;
		        $requisition_log['action_name']	= 'Returned By';
		        $requisition_log['comments']	= $request->comments;
		        $requisition_log['created_at']	= Carbon::now();
		        $requisition_log['updated_at']	= Carbon::now();
		        
				$RequisitionLogs  = RequisitionLog::insert($requisition_log);
				//dd($requisition_log);
				$ReqOwner_Mail	 = User::where('id', $reporting_sequence->user_id)
		        	->value('email');
		        $ReqReportTo_Mail	 = User::where('id', $reporting_sequence->report_to)
		        	->value('email');
				$admin_email     = $ReqOwner_Mail;
				Mail::to($admin_email)->send(new ReturnMail($users));
				$message = "You have successfully Return the Requisition..";
		            return redirect()->route('requisitions.index')
		                ->with('flash_success', $message);
			}else{
				
				$requisitionUpdate = Requisition::where('id', $Requisition_id)->update([
					'report_to' => $requisition_owner->user_id,
					'requisition_status' => 'return',
					'status' => 'pending',
					'updated_at' => Carbon::now(),
				]);

				$requisition_log['requisition_id']	= $Requisition_id;
		        $requisition_log['user_id']	= auth()->user()->id;
		        $requisition_log['action_name']	= 'Returned By';
		        $requisition_log['comments']	= $request->comments;
		        $requisition_log['created_at']	= Carbon::now();
		        $requisition_log['updated_at']	= Carbon::now();
		        
				$RequisitionLogs  = RequisitionLog::insert($requisition_log);
				//dd($requisition_log);
				$ReqOwner_Mail	 = User::where('id', $reporting_sequence->user_id)
		        	->value('email');
		        $admin_email     = $ReqOwner_Mail;
				Mail::to($admin_email)->send(new ReturnMail($users));

				$message = "You have successfully Return the Requisition..";
		            return redirect()->route('requisitions.index')
		                ->with('flash_success', $message);
				
			}
			//dd($requisition_reportTo->sequence);
		}elseif($request->requisition_status == 'hold'){
			$requisitionUpdate = Requisition::where('id', $Requisition_id)->update([
					'report_to' => auth()->user()->id,
					'requisition_status' => 'processing',
					'status' => 'hold',
					'updated_at' => Carbon::now(),
				]);

			$requisition_log['requisition_id']	= $Requisition_id;
	        $requisition_log['user_id']	= auth()->user()->id;
	        $requisition_log['action_name']	= 'Hold By';
	        $requisition_log['comments']	= $request->comments;
	        $requisition_log['created_at']	= Carbon::now();
	        $requisition_log['updated_at']	= Carbon::now();
	        
			$RequisitionLogs  = RequisitionLog::insert($requisition_log);
			//dd($requisition_log);

			$requisition_owner = Requisition::where('id', $Requisition_id)->first();
			//dd($requisition_owner);
			$ReqOwner_Mail	 = User::where('id', $requisition_owner->user_id)
        	->value('email');
	        $ReqReportTo_Mail	 = User::where('id', $requisition_owner->report_to)
	        	->value('email');
			$admin_email     = $ReqOwner_Mail;

			Mail::to($admin_email)->send(new HoldMail($users));

			$message = "You have successfully Hold the Requisition..";
	            return redirect()->route('requisitions.index')
	                ->with('flash_success', $message);
			//dd('Hold');
		}elseif($request->requisition_status == 'cancel'){

			$requisition_owner = Requisition::where('id', $Requisition_id)->first();
			//$Req_Sequence   = $requisition_owner['sequence'];
			//dd($requisition_owner->sequence);
			$reporting_sequence  = ReportingSequenceDetail::where('user_id', $requisition_owner->user_id)->where('report_to', auth()->user()->id)->first();

			$requisition_reportTo = ReportingSequenceDetail::where('user_id', $requisition_owner->user_id)
			->where('sequence', '>', $reporting_sequence->sequence)
			->orderBy('sequence', 'asc')
			->limit(1)
			->first();

			$requisitionUpdate = Requisition::where('id', $Requisition_id)->update([
					'report_to' => auth()->user()->id,
					'requisition_status' => 'cancelled',
					'status' => 'cancelled',
					'updated_at' => Carbon::now(),
				]);

					$requisition_log['requisition_id']	= $Requisition_id;
			        $requisition_log['user_id']	= auth()->user()->id;
			        $requisition_log['action_name']	= 'Cancelled By';
			        $requisition_log['comments']	= $request->comments;
			        $requisition_log['created_at']	= Carbon::now();
			        $requisition_log['updated_at']	= Carbon::now();
			        
					$RequisitionLogs  = RequisitionLog::insert($requisition_log);
					//dd($requisition_log);

					$requisition_owner = Requisition::where('id', $Requisition_id)->first();
					//dd($requisition_owner);
					$ReqOwner_Mail	 = User::where('id', $requisition_owner->user_id)
		        	->value('email');
			        //$ReqReportTo_Mail	 = User::where('id', $requisition_owner->report_to)
			        //	->value('email');
					$admin_email     = $ReqOwner_Mail;

					Mail::to($admin_email)->send(new CancelMail($users));
			
					$message = "You have successfully Cancelled the Requisition..";
			            return redirect()->route('requisitions.index')
			                ->with('flash_success', $message);
		}elseif($request->requisition_status == 'approve'){

			$requisition_owner = Requisition::where('id', $Requisition_id)->first();
			//$Req_Sequence   = $requisition_owner['sequence'];
			//dd($requisition_owner->sequence);
			$reporting_sequence  = ReportingSequenceDetail::where('user_id', $requisition_owner->user_id)->where('report_to', auth()->user()->id)->first();
			//dd($reporting_sequence);
			$requisition_reportTo = ReportingSequenceDetail::where('user_id', $requisition_owner->user_id)
			->where('sequence', '>', $reporting_sequence->sequence)
			->orderBy('sequence', 'asc')
			->limit(1)
			->first();
			//dd($requisition_reportTo);
			
			if($requisition_reportTo != ''){
				$requisitionUpdate = Requisition::where('id', $Requisition_id)->update([
						'report_to' => $requisition_reportTo->report_to,
						'requisition_status' => 'processing',
						'status' => 'pending',
						'updated_at' => Carbon::now(),
					]);

						$requisition_log['requisition_id']	= $Requisition_id;
				        $requisition_log['user_id']	= auth()->user()->id;
				        $requisition_log['action_name']	= 'Forwarded By';
				        $requisition_log['comments']	= $request->comments;
				        $requisition_log['created_at']	= Carbon::now();
				        $requisition_log['updated_at']	= Carbon::now();
				        
						$RequisitionLogs  = RequisitionLog::insert($requisition_log);
						//dd($requisition_log);
						$ReportTo_Mail	 = User::where('id', $requisition_reportTo->report_to)
		        		->value('email');
						$admin_email     = $ReportTo_Mail;
						Mail::to($admin_email)->send(new ForwardMail($users));

						$message = "You have successfully Forward the Requisition..";
				            return redirect()->route('requisitions.index')
				                ->with('flash_success', $message);
				//dd('Not Null');
			}else{
				$requisitionUpdate = Requisition::where('id', $Requisition_id)->update([
						'report_to' => auth()->user()->id,
						'requisition_status' => 'approved',
						'status' => 'approved',
						'updated_at' => Carbon::now(),
					]);

						$requisition_log['requisition_id']	= $Requisition_id;
				        $requisition_log['user_id']	= auth()->user()->id;
				        $requisition_log['action_name']	= 'Approved By';
				        $requisition_log['comments']	= $request->comments;
				        $requisition_log['created_at']	= Carbon::now();
				        $requisition_log['updated_at']	= Carbon::now();
				        
						$RequisitionLogs  = RequisitionLog::insert($requisition_log);
						//dd($requisition_log);

						$requisition_owner = Requisition::where('id', $Requisition_id)->first();
						//dd($requisition_owner);
						$ReqOwner_Mail	 = User::where('id', $requisition_owner->user_id)
			        	->value('email');
				        $ReqReportTo_Mail	 = User::where('id', $requisition_owner->report_to)
				        	->value('email');
						$admin_email     = $ReqOwner_Mail;

						Mail::to($admin_email)->send(new ApproveMail($users));

						$message = "You have successfully Approved the Requisition..";
				            return redirect()->route('requisitions.index')
				                ->with('flash_success', $message);
				//dd('Null');
			}
		}else{
			$message = "Something wrong !! Please Contact Software Administrator.....";
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
		$requisitions = Requisition::with([
			'shop' => function ($q) {
				return $q->with(['distributor' => function ($q) {
					return $q->select('id', 'outlet_name');
				}])
					->select('id', 'distributor_id', 'outlet_name', 'mobile');
			},
			'physical_visits' => function ($q) {
				return $q->where('stage', false)->select('requisition_id', 'status')->first();
			},
		])
			->findOrFail($id);

		if ($requisitions->type == 'replace') {
			$itemIds = \App\Settlement::where('shop_id', $requisitions->shop_id)
				->where('status', '<>', 'closed')
				->pluck('item_id');
			$currentdfs = Item::whereIn('id', $itemIds)->pluck('serial_no', 'id');
		} else {
			$currentdfs = '';
		}

		$documents = Document::where('shop_id', $requisitions->shop_id)
			->where(function ($query) {
				$query->where('module', 'requisition')
					->orWhereNull('module');
			})
			->where(function ($query) use ($requisitions) {
				$query->where('data_id', $requisitions->id)
					->orWhereNull('data_id');
			})
			->pluck('file_name', 'field_name');
		$hasExecutive = false;
		$isExecutiveGroup = Role::where('id', auth()->user()->role_id)->value('can_apply');

		if (!$isExecutiveGroup) {
			$hasExecutive = true;
			if ($requisitions->created_by != auth()->user()->id) {
				$message = "Something wrong!! Please try again";
				return redirect()->route('requisitions.index')->with('flash_danger', $message);
			}
		} else {
			if ($requisitions->user_id != auth()->user()->id) {
				$message = "Something wrong!! Please try again";
				return redirect()->route('requisitions.index')->with('flash_danger', $message);
			}
		}

		if ($requisitions->status == 'draft') {

			$dfreturns = \App\DfReturn::select('df_returns.id', 'items.serial_no', 'shops.outlet_name', 'items.size_id')
				->join('items', 'items.id', '=', 'df_returns.current_df')
				->join('shops', 'shops.id', '=', 'df_returns.shop_id')
				->where('to_shop', $requisitions->shop_id)
				->where('df_returns.status', '<>', 'cancelled')
				->where('df_returns.is_requisition_created', false)
				->get();
			if ($dfreturns->isNotEmpty()) {
				$sizeIds = $dfreturns->pluck('size_id', 'size_id');
				$sizes = Size::whereIn('id', $sizeIds->values())->orderBy('name', 'asc')->select('id', 'name', 'rent_amount')->get();
			} else {
				//$sizes = Size::orderBy('name', 'asc')->select('id', 'name', 'rent_amount')->get();
				
				if($requisitions->df_type == 'low_cooling_df'){
				    $sizes = \App\Size::join('items', 'items.size_id', '=', 'sizes.id')
				    ->where('items.depot_id',$requisitions->depot_id)
				    ->where('items.freeze_status','low_cooling')
				    ->whereNull('items.item_status')
				    ->where('availability', 'yes')->orderBy('name', 'asc')->select('sizes.id', 'name', 'rent_amount')->get();
				}else{
				    $sizes = \App\Size::where('availability', 'yes')->orderBy('name', 'asc')->select('sizes.id', 'name', 'rent_amount')->get();
				}
				
			}

			return view('requisitions.edit', compact('requisitions', 'sizes', 'hasExecutive', 'documents', 'currentdfs', 'dfreturns'));
		} else {
			if (!$hasExecutive) {
				return view('requisitions.upload_files', compact('requisitions', 'documents'));
			} else {
				$message = "Something wrong!! Please try again";
				return redirect()->route('requisitions.index')->with('flash_danger', $message);
			}
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$data = $request->except([
			'_method',
			'_token',
		]);
		//dd($data);
		if (array_key_exists('user_id', $data)) {
			$validateArr = [
				'user_id' => 'required',
				'size_id' => 'required',
				'payment_modes' => 'required',
				'distance_from_dist' => 'required | max:50',
			];
		} else {
			$validateArr = [
				'size_id' => 'required',
				'payment_modes' => 'required',
				'distance_from_dist' => 'required | max:50',
			];
		}

		if (isset($data['type']) && $data['type'] == 'replace') {
			$validateArr['current_df'] = 'required';
			$validateArr['comment'] = 'required';
		}

		$physicallyVisitData = [];
		if (!$request->has('upload_file')) {

			/* if ($request->has('df_return_id')) {
				$validateArr['df_return_id'] = 'required';
			} else {
				$data['df_return_id'] = null;
			} */

			if (!empty($data['physically_visit'])) {
				$physicallyVisitData['status'] = $data['physically_visit'];
				unset($data['physically_visit']);
			} else {
				$physicallyVisitData['status'] = 0;
			}
			if (!empty($data['user_id'])) {
				$physicallyVisitData['user_id'] = $data['user_id'];
			}

			if (isset($data['other_company'])) {
				$validateArr['other_company_df'] = 'required';
				if (isset($data['other_company_df'])) {
					$data['other_company_df'] = json_encode($data['other_company_df']);
				}

				unset($data['other_company']);
			} else {
				$data['other_company_df'] = NULL;
			}

			if (empty($data['exclusive_outlet'])) {
				$data['exclusive_outlet'] = 0;
			}
			if ($data['payment_modes'] == 'full_paid' && !empty($data['size_id'])) {
				$sizeRentAmount = Size::select('rent_amount')->find($data['size_id']);
				$data['receive_amount'] = $sizeRentAmount->rent_amount ?: 0;
			}
			if ($data['payment_modes'] == 'concession') {
				$validateArr['receive_amount'] = 'required | numeric | not_in:0';
			}

			if ($data['payment_modes'] == 'without_rent') {
				$data['receive_amount'] = null;
				$data['payment_methods'] = null;
			}
		} else {
			unset($data['size_id'], $data['distance_from_dist'], $data['upload_file']);
		}

		// put shop's depot_id in every requisition
		$currentRequisitionData = Requisition::with([
			'depot' => function ($q) {
				return $q->select('id', 'has_incharge');
			},
		])
			->select('id','df_type', 'depot_id', 'shop_id', 'reference_id', 'current_df', 'distributor_id')
			->find($id);

		if (array_key_exists('send', $data)) {
			$data['status'] = 'processing';
			$data['created_at'] = \Carbon\Carbon::now(); //application date set
			if ($currentRequisitionData->depot->has_incharge) {
				if ($this->checkFirstStageSupervisor($currentRequisitionData->distributor_id)) {
					$data['stage'] = 1;
				} else {
					$data['stage'] = 2;
				}
			} else {
				$data['stage'] = 2;
			}
			unset($data['send']);
			if ($data['payment_modes'] != 'without_rent') {
				$validateArr['payment_methods'] = 'required';
			}
			
			//check stock availability
			if (empty($data['df_return_id'])) {
			    $dfType = $currentRequisitionData->df_type ? : 'new_df';
			    $totalItem = $this->checkAvailableStock($data['size_id'], null, $currentRequisitionData->depot_id,config('myconfig.df_type_status')[$dfType]);
				if ($totalItem < 1) {
					$message = "Stock is not available";
					return redirect()->back()->with('flash_danger', $message);
				}
			}
		}

		//requisition files upload
		$fieldsArr = [];
		$oldFieldsArr = [];
		foreach (config('myconfig.requisition_file') as $value) {
			$validateArr[$value] = 'mimes:jpeg,bmp,png,gif,svg,pdf|max:1024';
			$receipt = $request->file($value);
			if ($receipt) {
				if ($value == 'money_receipt') {
					$data['payment_confirm'] = true;
				}
				$fieldsArr[$value] = $receipt;
				unset($data[$value]);
			}

			if (array_key_exists('old_' . $value, $data)) {
				$oldFieldsArr['old_' . $value] = $data['old_' . $value];
				unset($data['old_' . $value]);
			}
		}

		//shop files upload
		$fieldsArr2 = [];
		$oldFieldsArr2 = [];
		foreach (config('myconfig.shop_file') as $value) {
			$validationArr[$value] = 'mimes:jpeg,bmp,png,gif,svg,pdf|max:1024';
			$receipt = $request->file($value);
			if ($receipt) {
				$fieldsArr2[$value] = $receipt;
				unset($data[$value]);
			}
			if (array_key_exists('old_' . $value, $data)) {
				$oldFieldsArr2['old_' . $value] = $data['old_' . $value];
				unset($data['old_' . $value]);
			}
		}

		if ($data['payment_modes'] == 'without_rent' || $data['payment_methods'] == 'bkash') {
			$data['payment_confirm'] = false;
		}
		$request->validate($validateArr);
		try {
		    \DB::beginTransaction();
		    
		    if (isset($data['current_df']) && $request->has('send')) {
		        if ($data['current_df'] != $currentRequisitionData->current_df) {
		            //new current df will be lock
		            $lockArrData = (object) ['shop_id' => $currentRequisitionData['shop_id'], 'current_df' => $data['current_df']];
		            $responseError = $this->settlementLockUnlock($lockArrData);
		            if ($responseError) {
		                //return $responseError;
		                throw new \Exception();
		            }
		            //inserted current df will be unlock
		            $this->settlementLockUnlock($currentRequisitionData, true);
		        } else {
		            $lockArrData = (object) ['shop_id' => $currentRequisitionData['shop_id'], 'current_df' => $data['current_df']];
		            $responseError = $this->settlementLockUnlock($lockArrData);
		            if ($responseError) {
		                //return $responseError;
		                throw new \Exception();
		            }
		        }
		        $data['created_at'] = date("Y-m-d h:i:s");
		    }
		    $requisition = Requisition::where('id', $id)->update($data);
		    if ($requisition) {
		        //if return df tagged in shop
		        if (!empty($data['df_return_id']) && $request->has('send')) {
		            DfReturn::where('id', $data['df_return_id'])
		            ->update(['is_requisition_created' => true]);
		        }
		        //update physical vistit
		        if (!empty($physicallyVisitData)) {
		            PhysicalVisit::where('requisition_id', $id)->where('stage', 0)->update($physicallyVisitData);
		        }
		        //requisition file uploads
		        if (count($fieldsArr)) {
		            if ($data['payment_modes'] == 'without_rent' || $data['payment_methods'] == 'bkash') {
		                unset($fieldsArr['money_receipt']);
		                unset($oldFieldsArr['old_money_receipt']);
		            }
		            $this->storeDucuments($fieldsArr, $currentRequisitionData, 'requisition', $oldFieldsArr);
		        }
		        //shop file uploads
		        $shopId['id'] = $currentRequisitionData->shop_id;
		        if (count($fieldsArr2)) {
		            $this->storeDucuments($fieldsArr2, (object) $shopId, null, $oldFieldsArr2);
		        }
		        \DB::commit();
		        $message = "You have successfully created";
		        return redirect()->route('requisitions.index', [$request->has('send') ? 'new' : 'draft'])->with('flash_success', $message);
		    } 
		}
		
		//catch exception
		catch(\Exception $e) {
		    \DB::rollBack();
		    $message = "Something wrong!! Please try again";
		    return redirect()->back()->with('flash_danger', $message);
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
	      	//dd($user_id);
	      	$reportToRequisitions = Requisition::with([
	            'department'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'approved')
	        ->Where('department_id',$userDetails[0]->department_id)
	        ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToRequisitions->toArray());
        return view('requisitions.approved', compact('reportToRequisitions'));
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
	      	$reportToRequisitions = Requisition::with([
	            'department'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'cancelled')
	        ->Where('department_id',$userDetails[0]->department_id)
	        ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToRequisitions->toArray());
        return view('requisitions.cancelled', compact('reportToRequisitions'));
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
	      	$reportToRequisitions = Requisition::with([
	            'department'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'hold')
	        ->where('report_to', $user_id)
	        ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToRequisitions->toArray());
        return view('requisitions.hold', compact('reportToRequisitions'));
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
	      	//dd($user_id);
	      	$reportToRequisitions = Requisition::with([
	            'department'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('requisition_status', 'return')
	        ->Where('department_id',$userDetails[0]->department_id)
	        ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToRequisitions->toArray());
        return view('requisitions.returned', compact('reportToRequisitions'));
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
	      	$reportToRequisitions = Requisition::with([
	            'department'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'section'=>function($q){
	                return $q->select('id', 'name');
	            },
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('requisition_status', 'processing')
	        ->Where('department_id',$userDetails[0]->department_id)
	        ->get(); 
            
        
        /*
        $reportToRequisitions = Requisition::where('report_to', $user_id)
        ->where('status', '=', 'pending')
	    ->get();
	    */
        //dd($reportToRequisitions->toArray());
        return view('requisitions.processing', compact('reportToRequisitions'));
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
		$requisition = Requisition::findOrFail($id);
		if ($requisition->status == 'draft') {
			$documents = Document::where('data_id', $id)->get();
			$physically_visit = PhysicalVisit::where('requisition_id', $id)->exists();
			if ($documents->count()) {
				foreach ($documents as $key => $document) {
					\Storage::delete('images/' . $requisition->shop_id . '/' . $document->file_name);
					$document->delete();
				}
			}
			if ($physically_visit) {
				PhysicalVisit::where('requisition_id', $id)->delete();
			}
			$requisition->delete();
			$message = "Successfully deleted this item.";
			return redirect()->route('requisitions.index')->with('flash_success', $message);
		} else {
			$message = "Something wrong!! Please try again";
			return redirect()->route('requisitions.index')->with('flash_danger', $message);
		}
	}

	public function approveRequisitionDownload($id) {
		//dd($id);
		//$id = $request->get('id');
		$requisitionDate 	= Requisition::where('id',$id)->get();
		//dd($requisitionDate);
		$RequisitionDetails = RequisitionDetail::with([
            'product'=>function($q){
                return $q->select('id', 'name', 'tags', 'subcategory_id');
            },
            'section'=>function($q){
                return $q->select('id', 'name');
            },
            'department'=>function($q){
                return $q->select('id', 'name');
            },
            
        ])
        ->where('requisition_id', $id)
        ->get();
		//dd($RequisitionDetails->toArray());
		$particulars = [];	
		foreach($RequisitionDetails as $detail){
			if ($detail->product->tags == 'employees') {
				$particular = \App\Employee::where('id', $detail->employee_id)
					->pluck('name');
				} elseif($detail->product->tags == 'vehicles') {
					$particular = \App\Vehicle::where('id', $detail->vehicle_id)
					->pluck('name');
				} elseif($detail->product->tags == 'machines'){
					$particular = \App\Machine::where('id', $detail->machine_id)
					->pluck('name');
				} else {
					$particular = [];	
			}
			$particulars[] = $particular;
		}

		$subcategories = [];	
		foreach($RequisitionDetails as $subcategory){
			$subcategories[] = \App\Subcategory::where('id', $subcategory->product->subcategory_id)
					->pluck('name');
				
		}
		$stocks = [];	
		foreach($RequisitionDetails as $stock){
			
			if($stock->stock == 1){
				$stocks[] = 'Stock';
			}else{
				$stocks[] = '';
			}
				
		}
		$RequisitionLogs = RequisitionLog::with([
            'user'=>function($q){
                return $q->select('*');
            },
            
        ])
        ->where('requisition_id', $id)
        ->get();
		//dd($subcategories);
		
		   
		$pdf = \domPDF::loadView('pdf.approveRequisitionDownload', compact('RequisitionDetails', 'particulars', 'id', 'subcategories', 'stocks', 'RequisitionLogs', 'requisitionDate'));
        return $pdf->setPaper('a4', 'landscape')->download('approveRequisition.pdf');

         
    }

}
