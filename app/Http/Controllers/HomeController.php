<?php

namespace App\Http\Controllers;


use App\Location;
use App\Designation;
use App\Requisition;
use App\User;
use App\Application;
use App\Attendance;
use App\MdrInformation;
use App\Role;
use App\Product;
use App\CustomerComplain;
use App\CustomerComplainLog;
use App\CustomerComplainType;
use App\Traits\HasStageExists;
use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Employee;

class HomeController extends Controller {
	use HasStageExists;
	private $canApply = false;
	private $user = null;
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware(function ($request, $next) {
			if ($request->user()) {
				$this->user = $request->user();
				$this->canApply = (bool) Role::where('id', $this->user->role_id)->value('can_apply');
			}
			return $next($request);
		});
		//$this->middleware('auth');
	}
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$user_id = auth()->user()->id;
	      	//dd($user_id);
		$userDetails = User::where('id', auth()->user()->id)->get();
	    //dd($userDetails);
	    $OfficerName = auth()->user()->name;
	    //dd($OfficerName);
		$EmployeeID = Auth::user()->employee_id;
		//dd($EmployeeID);
		$Depot = Employee::where('id', $EmployeeID)->get();
		$DepotID  = $Depot[0]->depot_id;
		
		   	$countPendingReq = Application::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'pending')
	        ->where('application_status','<>', 'return')
	        ->Where('report_to',$user_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        $countApprovedReq = Application::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'approved')
	        //->Where('department_id',$userDetails[0]->department_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        $countInactiveMDR = MdrInformation::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'inactive')
	        //->Where('department_id',$userDetails[0]->department_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        $DepotAttendanceList = Attendance::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'pending')
	        //->Where('department_id',$userDetails[0]->department_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        $countActiveMDR = MdrInformation::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'active')
	        //->Where('department_id',$userDetails[0]->department_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        $countProcessingReq = Application::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('application_status', 'processing')
	        ->where('depot_id', $DepotID)
	        //->Where('department_id',$userDetails[0]->department_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        $countCancelReq = Application::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'cancelled')
	        ->Where('report_to',$user_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

	        //dd($countApprovedReq);

	        $countOfficerActiveMDR = MdrInformation::with([
	            'user'=>function($q){
	                return $q->select('*');
	            },
	        ])
	        ->where('status', 'active')
	        ->where('employee_id', $EmployeeID)
	        //->Where('department_id',$userDetails[0]->department_id)
	        //Carbon::parse($employees['birthdate'])->format('d-m-Y');
	        ->count(); 

		
        return view('dashboards.index', compact('countPendingReq', 'countApprovedReq', 'countProcessingReq', 'countCancelReq', 'countInactiveMDR', 'countActiveMDR', 'DepotAttendanceList', 'countOfficerActiveMDR', 'OfficerName'));
        
	}

	
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

	public function example(Request $request) {
		$request->validate([
			'name' => 'required|min:3|max:255',
		]);
		return collect($request->all());
	}

	// should be deleted after development
	public function pages($name = 'ui-elements_panels') {
		return view('pages.' . $name);
	}
	
		
}