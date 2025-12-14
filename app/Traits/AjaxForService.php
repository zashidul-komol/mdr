<?php

namespace App\Traits;
use App\Models\ComplainType;
use App\Models\DamageApplication;
use App\Models\Depot;
use App\Models\DepotUser;
use App\Models\DfProblem;
use App\Models\DistributorUser;
use App\Models\RequisitionDetail;
use App\Models\ReportingSequenceDetail;
use App\Models\TadaReportingSequence;
use App\Models\TadaReportingSequenceDetail;
use App\Models\RequisitionLog;
use App\Models\Application;
use App\Models\ApplicationLog;
use App\Models\ApplicationDetail;
use App\Models\MerchanApplication;
use App\Models\MerchanApplicationLog;
use App\Models\MerchanApplicationDetail;
use App\Models\Item;
use App\Models\ProblemType;
use App\Models\Shop;
use App\Models\Stage;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Machine;
use App\Models\User;
use App\Models\Distributor;
use Carbon\Carbon;

use Illuminate\Http\Request;

trait AjaxForService {
	private function authDistributorLists() {
		return DistributorUser::where('user_id', auth()->id())->pluck('distributor_id');
	}
	public function getItemsForService() {
		$query = Item::join('sizes', 'sizes.id', '=', 'items.size_id')
			->join('depots', 'depots.id', '=', 'items.depot_id')
			->leftJoin('df_problems', function ($join) {
				$join->on('df_problems.df_code', '=', 'items.serial_no')
					->whereIn('df_problems.status', ['pending', 'processing']);
			})
			->join('shops', 'shops.id', '=', 'items.shop_id')
			->join('distributor_users', 'distributor_users.distributor_id', '=', 'shops.distributor_id')
			->where('distributor_users.user_id', auth()->id())
			->where('items.item_status', 'continue')
			->select(
				'items.id',
				'items.serial_no',
				'shops.outlet_name',
				'shops.proprietor_name',
				'shops.mobile',
				'df_problems.status as problem',
				'depots.name as depot',
				'sizes.name as size'
			)
			->orderBy('items.updated_at', 'desc');

		return datatables($query)->make(true);
	}

	public function getItemsForServiceHistory() {

		$dfProblem = DfProblem::select('df_code')->groupBy('df_code');

		$query = Item::join('sizes', 'sizes.id', '=', 'items.size_id')
			->join('depots', 'depots.id', '=', 'items.depot_id')
			->joinSub($dfProblem, 'problems', function ($join) {
				$join->on('problems.df_code', '=', 'items.serial_no');
			})
			->join('shops', 'shops.id', '=', 'items.shop_id')
			->join('distributor_users', 'distributor_users.distributor_id', '=', 'shops.distributor_id')
			->where('distributor_users.user_id', auth()->id())
			->select(
				'items.id',
				'items.serial_no',
				'shops.outlet_name',
				'shops.proprietor_name',
				'shops.mobile',
				'depots.name as depot',
				'sizes.name as size'
			)
			->orderBy('items.updated_at', 'desc');

		//return $query->toSql();

		return datatables($query)->make(true);
	}

	public function getReportingSequence(Request $request) 
	{
		$requisitor_id = $request->get('id');
		//dd($requisition_id);

		
		$reporting_sequence = ReportingSequenceDetail::where('user_id', $requisitor_id)
		->orderBy('sequence', 'asc')
        ->get();

        $Requisitor_name  = User::where('id', $reporting_sequence[0]->user_id)
        ->get();

        $reportingTo_name = [];	
		foreach($reporting_sequence as $reportingName){
			$reportingTo_name[] = \App\Models\User::where('id', $reportingName->report_to)
					->pluck('name');
				
		}
        
		
		   
		return view('ajax.reporting_sequence', compact('reporting_sequence', 'Requisitor_name', 'reportingTo_name'));
			
			
	}

	public function getTaDaReportingSequence(Request $request) 
	{
		//dd($request);
		$requisitor_id = $request->get('id');
		//dd($requisition_id);

		
		$reporting_sequence = TadaReportingSequenceDetail::where('user_id', $requisitor_id)
		->orderBy('sequence', 'asc')
        ->get();

        $Requisitor_name  = User::where('id', $reporting_sequence[0]->user_id)
        ->get();

        $reportingTo_name = [];	
		foreach($reporting_sequence as $reportingName){
			$reportingTo_name[] = \App\Models\User::where('id', $reportingName->report_to)
					->pluck('name');
				
		}
        
		
		   
		return view('ajax.tadareporting_sequence', compact('reporting_sequence', 'Requisitor_name', 'reportingTo_name'));
			
			
	}

	public function getMDRApplication(Request $request) 
	{
		//dd($request);
		//dd($ApplicationLogs->toArray());
		
		return view('ajax.mdr_application_form', compact('request'));
			
			
	}

	public function getItemDetailsBySeraial(Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = ApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();

        $ApplicationLogs = ApplicationLog::with([
            'application'=>function($q){
                return $q->select('*');
            },
            'application.application_details'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
        //dd($ApplicationLogs->toArray());
        $Height	 = $ApplicationLogs[0]->application->application_details[0]->height_feet.' '.'Feet,'.$ApplicationLogs[0]->application->application_details[0]->height_inch.' '.'Inch';

        $MarketScore = 'Dress Up :'.' '.$ApplicationLogs[0]->application->application_details[0]->dressUp.', '.'Physical Strength :'.' '.$ApplicationLogs[0]->application->application_details[0]->physicalStrenth.', '.'PDF Merchandising :'.' '.$ApplicationLogs[0]->application->application_details[0]->pdfMerchendising;
        //dd($MarketScore);
		//dd($ApplicationLogs[0]->application->application_details[0]->date_of_birth);
		//{{\Carbon\Carbon::parse($data->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months')}}
		$ApploicantAge  = \Carbon\Carbon::parse($ApplicationLogs[0]->application->application_details[0]->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months');
		$DistributorName = Distributor::where('id', $ApplicationLogs[0]->application->application_details[0]->distributor_id)->pluck('distributorName');
		return view('ajax.problem_entry_form', compact('ApplicationDetails', 'ApplicationLogs', 'application_id', 'DistributorName', 'ApploicantAge', 'MarketScore', 'Height'));
			
			
	}

	public function getItemDetailsBySeraialMerchandiser(Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = MerchanApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('merchan_application_id', $application_id)
        ->get();
        //dd($ApplicationDetails);

        $ApplicationLogs = MerchanApplicationLog::with([
            'merchan_applications'=>function($q){
                return $q->select('*');
            },
            'merchan_applications.merchan_application_details'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('merchan_application_id', $application_id)
        ->get();
        //dd($ApplicationLogs);
		$ApploicantAge  = \Carbon\Carbon::parse($ApplicationLogs[0]->merchan_applications->merchan_application_details[0]->date_of_birth)->diff(\Carbon\Carbon::now())->format('%y years, %m months');

		return view('ajax.merchandiser_entry_form', compact('ApplicationDetails', 'application_id', 'ApploicantAge', 'ApplicationLogs'));
			
			
	}

	public function getRequisitionApprove(Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = ApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
        //dd($ApplicationDetails);
        $ApplicationLogs = ApplicationLog::with([
            'application'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
		//dd($ApplicationLogs->toArray());
		
		return view('ajax.requisition_approve', compact('ApplicationDetails', 'ApplicationLogs', 'application_id'));
			
	}

	public function getRequisitionReturn(Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = ApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
        //dd($ApplicationDetails);
        $ApplicationLogs = ApplicationLog::with([
            'application'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
		//dd($ApplicationLogs->toArray());
		
		return view('ajax.requisition_return', compact('ApplicationDetails', 'ApplicationLogs', 'application_id'));
			
			
	}

	public function getRequisitionHold(Request $request) 
	{
		$requisition_id = $request->get('id');
		$RequisitionDetails = RequisitionDetail::with([
            'product'=>function($q){
                return $q->select('id', 'name', 'tags', 'subcategory_id');
            },
            'section'=>function($q){
                return $q->select('id', 'name');
            },
            'measurement'=>function($q){
                return $q->select('id', 'shortname');
            },
            
        ])
        ->where('requisition_id', $requisition_id)
        ->get();
		//dd($RequisitionDetails->toArray());
		$particulars = [];	
		foreach($RequisitionDetails as $detail){
			if ($detail->product->tags == 'employees') {
				$particular = \App\Models\Employee::where('id', $detail->employee_id)
					->pluck('name');
				} elseif($detail->product->tags == 'vehicles') {
					$particular = \App\Models\Vehicle::where('id', $detail->vehicle_id)
					->pluck('name');
				} elseif($detail->product->tags == 'machines'){
					$particular = \App\Models\Machine::where('id', $detail->machine_id)
					->pluck('name');
				} else {
					$particular = [];	
			}
			$particulars[] = $particular;
		}

		$subcategories = [];	
		foreach($RequisitionDetails as $subcategory){
			$subcategories[] = \App\Models\Subcategory::where('id', $subcategory->product->subcategory_id)
					->pluck('name');
				
		}
		$stocks = [];	
		foreach($RequisitionDetails as $stock){
			
			if($stock->stock == 1){
				$stocks[] = 'Stock';
			}else{
				$stocks[] = 'N/A';
			}
				
		}
		$RequisitionLogs = RequisitionLog::with([
            'user'=>function($q){
                return $q->select('*');
            },
            
        ])
        ->where('requisition_id', $requisition_id)
        ->get();
		//dd($RequisitionLogs->toArray());
		
		$reqQuantities = [];	
		foreach($RequisitionDetails as $ReqQuantity){
			$reqQuantities[] = $ReqQuantity->requsition_quantity . " " . $ReqQuantity->measurement->shortname;
				
		}
		$consumptions = [];	
		foreach($RequisitionDetails as $Total){
			$consumption = RequisitionDetail::select('requsition_quantity')
			->join('requisitions', 'requisition_details.requisition_id', '=', 'requisitions.id')
		    ->where('product_id', '=', $Total->product_id)
		    ->where('requisitions.status', 'approved')
		    ->sum('requisition_details.requsition_quantity');
		    
			$consumptions[] = $consumption;
		}
		//dd($consumptions);

		return view('ajax.requisition_hold', compact('RequisitionDetails', 'particulars', 'requisition_id', 'subcategories', 'stocks', 'RequisitionLogs', 'reqQuantities', 'consumptions'));
			
			
	}

	public function getRequisitionCancel (Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = ApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
        //dd($ApplicationDetails);
        $ApplicationLogs = ApplicationLog::with([
            'application'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
		//dd($ApplicationLogs->toArray());
		
		return view('ajax.requisition_cancel', compact('ApplicationDetails', 'ApplicationLogs', 'application_id'));
			
			
	}

	public function getRequisitionProcessing(Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = ApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
        //dd($ApplicationDetails);
        $ApplicationLogs = ApplicationLog::with([
            'application'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
		//dd($ApplicationLogs->toArray());
		return view('ajax.requisition_processing', compact('ApplicationDetails', 'ApplicationLogs', 'application_id'));
					
	}

	public function getRequisitionSubmitted(Request $request) 
	{
		$application_id = $request->get('id');
		//dd($application_id);
		$ApplicationDetails = ApplicationDetail::with([
            'employee'=>function($q){
                return $q->select('id', 'name');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
        //dd($ApplicationDetails);
        $ApplicationLogs = ApplicationLog::with([
            'application'=>function($q){
                return $q->select('*');
            },
                        
        ])
        ->where('application_id', $application_id)
        ->get();
		//dd($ApplicationDetails->toArray());
		
		return view('ajax.requisition_submitted', compact('ApplicationDetails' , 'ApplicationLogs', 'application_id'));
			
			
	}


	public function getProblemEntries($param) {
		$query = DfProblem::with([
			'region' => function ($q) {
				return $q->select('id', 'name');
			},
			'depot' => function ($q) {
				return $q->select('id', 'name');
			},
			'officer' => function ($q) {
				return $q->select('id', 'name');
			},
			'technician' => function ($q) {
				return $q->select('id', 'name');
			},
		])
			->join('distributor_users', 'distributor_users.distributor_id', '=', 'df_problems.distributor_id')
			->where('distributor_users.user_id', auth()->id())
			->select('df_problems.id', 'df_problems.token', 'df_problems.df_size', 'df_problems.depot_id', 'df_problems.outlet_name', 'df_problems.proprietor_name', 'df_problems.address', 'df_problems.mobile', 'df_problems.technician_id', 'df_problems.user_id', 'df_problems.status', 'df_problems.created_at', 'df_problems.region_id', 'df_problems.comments', 'df_problems.item_id')
			->selectRaw("IF(df_problems.df_code = 'personal','Personal',df_problems.df_code) as df_code")
			->selectRaw("CASE WHEN df_problems.support = 1 THEN 'Need' WHEN df_problems.support = 2 THEN NULL WHEN df_problems.support = 3 THEN 'Returned' ELSE 'Not Needed' END as supportdf")
			->latest();

		if ($param == 'new') {
			$query->where('df_problems.status', 'pending');
		} elseif ($param == 'damage-applied') {
			$query->whereIn('df_problems.status', ['applied_for_damage', 'damage_approved']);
		} elseif ($param == 'service_workshop') {
			$query->whereIn('pull', [1, 2]); //mipellim
		} elseif ($param == 'all') {
			$query->whereNotNull('df_problems.status');
		} else {
			$query->where('df_problems.status', $param);
		}
		//dd($query->get()->toArray());
		return datatables($query)->make(true);
	}

	public function getProblemDetails($id) {
		$dfproblems = DfProblem::with([
			'region' => function ($q) {
				return $q->select('id', 'name');
			},
			'depot' => function ($q) {
				return $q->select('id', 'name');
			},
			'distributor' => function ($q) {
				return $q->select('id', 'outlet_name');
			},
			'technician' => function ($q) {
				return $q->select('id', 'name', 'mobile');
			},
			'supportdf' => function ($q) {
				return $q->select('id', 'serial_no');
			},
		])
			->find($id);
		//dd($dfproblems->toArray());

		$complains = ComplainType::join('problem_types', 'problem_types.id', '=', 'complain_types.problem_type_id')
			->where('complain_types.df_problem_id', $id)
			->pluck('problem_types.name')
			->implode(',');

		$dfproblems->df_problem = $complains;
		//return $dfproblems;
		return view('ajax.problem_details', compact('dfproblems'));
	}

	/*==========================Damage Application Start===========================*/
	public function getApplicationDetails($id) {
		$applications = DamageApplication::with([
			'item' => function ($q) {
				return $q->select('id', 'serial_no');
			},
			'shop' => function ($s) {
				return $s->select('id', 'outlet_name');
			},
			'settlement' => function ($s) {
				return $s->select('item_id', 'inject_date', 'receive_amount', 'status');
			},
			'depot' => function ($s) {
				return $s->select('id', 'name');
			},
			'damage_type',
		])
			->find($id);
		return view('ajax.get_application_details', compact('applications'));
	}
	private function application_approve($request) {
		$data = $request->all();
		$itemUpdate = false;
		$maxStage = Stage::where('module', 'damage_application')->max('sequence');
		if ($data['stage'] < $maxStage) {
			$updateArr['stage'] = $data['stage'] + 1;
			$updateArr['status'] = 'processing';
		} else {
			$itemUpdate = true;
			$updateArr['status'] = 'completed';
		}
		$applicationObj = DamageApplication::find($data['id']);
		if (!$applicationObj) {
			return response()->json([
				'error' => true, 'success' => false,
				'message' => 'Opps! Application does not match. Please try again.',
			]);
		}
		$application = $applicationObj->update($updateArr);
		if ($application) {
			if ($itemUpdate) {
				//when damage application final approved then settlement will be closed
				$settlementDataObj = (object) ['shop_id' => $applicationObj->shop_id, 'current_df' => $applicationObj->item_id, 'withdrawal_date' => $applicationObj->created_at];
				$this->settlementClose($settlementDataObj, 'closed', 'y');
				DfProblem::where('id', $applicationObj->df_problem_id)->update(['status' => 'damage_approved']);
				//item status update
				Item::where('id', $applicationObj->item_id)->update(['freeze_status' => 'damage']);
			}

			return response()->json([
				'error' => false,
				'success' => true,
				'message' => 'Action has successfully done',
			]);
		} else {
			return response()->json([
				'error' => true, 'success' => false,
				'message' => 'Opps! Something error. Please try again.',
			]);
		}

	}

	private function application_hold($request) {
		$data = $request->all();
		$application = DamageApplication::where('id', $data['id'])
			->update([
				'status' => 'on_hold',
			]);
		if ($application) {
			return response()->json([
				'error' => false,
				'success' => true,
				'message' => 'Action has successfully done',
			]);
		} else {
			return response()->json([
				'error' => true, 'success' => false,
				'message' => 'Opps! Something error. Please try again.',
			]);
		}
	}

	public function saveApplicationStageAction(Request $request) {
		$action = $request->get('action');
		$methodName = 'application_' . $action;
		return $this->$methodName($request);
		// if ($action == 'hold') {
		//     $validator = \Validator::make($data, [
		//         'comments' => 'required|max:150',
		//     ]);

		//     if ($validator->fails()) {
		//         return response()->json([
		//             'error' => true,
		//             'success' => true,
		//             'message' => 'Comments can not be blank.',
		//         ]);
		//     }
		// }
	}
	/*==========================Damage Application end===========================*/
}
?>