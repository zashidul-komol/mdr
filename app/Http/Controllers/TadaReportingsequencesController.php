<?php

namespace App\Http\Controllers;

use App\ReportingSequence;
use App\TadaReportingSequence;
use App\User;
use App\Department;
use App\ReportingSequenceDetail;
use App\TadaReportingSequenceDetail;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class TadaReportingsequencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tada_reportingsequences = TadaReportingSequence::with([
            'user'=>function($q){
                return $q->select('*');
            },
            'user.designation'=>function($q){
                return $q->select('id', 'title');
            },
            'user.department'=>function($q){
                return $q->select('id', 'name');
            },
            'user.section'=>function($q){
                return $q->select('id', 'name');
            },
        ])
        ->get();
        //dd($tada_reportingsequences->toArray());
        //$tada_reportingsequences = TadaReportingSequence::get();
        return view('tada_reportingsequences.index', compact('tada_reportingsequences'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
       if ($request->isMethod('post')) {
            return $this->stockStore($request);
        } else {
            $brands = Department::select(DB::raw("CONCAT(name,'-',short_name) AS name"), 'id')->pluck('name', 'id');
            if (!count($brands)) {
                $brands = '{}';
            }
            $sizes = Department::select(DB::raw("CONCAT(name,'-',short_name) AS name"), 'id')->pluck('name', 'id');
            if (!count($sizes)) {
                $sizes = '{}';
            }
            $reportsTo = User::select(DB::raw("CONCAT(name,'(',name,')') AS name"), 'id')->pluck('name', 'id');
            if (!count($sizes)) {
                $sizes = '{}';
            }
           }

       $users = User::pluck('name','id');
       $reports_to = User::get();
        return view('tada_reportingsequences.create', compact('users', 'brands', 'sizes', 'reports_to'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request); 
        $data = $request->except('details');
        $reportingSequenceDetailsData = $request->only('details');
        //dd($reportingSequenceDetailsData);
        
        $request->validate([
            'user_id' => 'required|unique:tada_reporting_sequences,user_id',
            'details.*.report_to' => 'required',
        ]);

        //insert data in ReportingSequence
        $reportingSequence = TadaReportingSequence::create($data);
        //insert data in ReportingSequenceDetails
        $detials_data =[];
        $sequence = 1;
        foreach($reportingSequenceDetailsData['details'] as $report){
 
            //$data['reporting_sequence_id'] = $reportingSequence->id;
            $d_data['report_to'] = $report['report_to'];
            $d_data['user_id'] = $data['user_id'];
            $d_data['sequence'] = $sequence;
            $detials_data[] = $d_data;
            $sequence++;
        }
        
        $reportingSequenceDetails = $reportingSequence->TadaReportingSequenceDetail()->createMany($detials_data);
        if ($reportingSequenceDetails) {
            $message = "You have successfully created";
            return redirect()->route('tada_reportingsequences.create')
                ->with('flash_success', $message);
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
        //
        $where = array('idcablelists' => $id);
        $data['cablelists_info'] = Cablelist::where($where)->first();
        $cablenames = Cablename::with('cablelist')->select(['cablelistscol', 'idcablelists'])->get();

        return view('cablelist.edit', $data)->with('cablenames', $cablenames);
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
        //https://w3path.com/laravel-6-tutorial-build-your-first-crud-app-with-laravel/
        $request->validate([
          'typeofmachine_id.*' => 'required',
          'cablename_id.*' => 'required',
          'lengh.*' => 'required',
        ]);
        $update = [ 'typeofmachine_id'=> $request->typeofmachine_id, 
        'cablename_id' => $request->cablename_id, 'lengh'=> $request->lengh];

      Cablelist::where('idcablelists',$id)->update($update);
        return Redirect::to('cablelists')
       ->with('success','Great! Cablelist updated successfully');
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
        Cablelist::where('idcablelists',$id)->delete();

        return Redirect::to('cablelists')->with('success','Cablelist deleted successfully');
    }

    public function download() {
        return (new OrganizationExport())->download('organization.xlsx');
    }
}
