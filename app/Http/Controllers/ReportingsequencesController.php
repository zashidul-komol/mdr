<?php

namespace App\Http\Controllers;

use App\Models\ReportingSequence;
use App\Models\User;
use App\Models\Depot;
use App\Models\Department;
use App\ReportingSequenceDetail;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ReportingsequencesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reportingsequences = ReportingSequence::with([
            'user',
            'user.designation',
            'user.department',
            'user.section',
            'user.employee.region',
            'user.employee.depot',
        ])->get();

        return view('reportingsequences.index', compact('reportingsequences'));
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
        return view('reportingsequences.create', compact('users', 'brands', 'sizes', 'reports_to'));
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
            'user_id' => 'required|unique:reporting_sequences,user_id',
            'details.*.report_to' => 'required',
        ]);

        //insert data in ReportingSequence
        $reportingSequence = ReportingSequence::create($data);
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
        
        $reportingSequenceDetails = $reportingSequence->reportingsequence_details()->createMany($detials_data);
        if ($reportingSequenceDetails) {
            $message = "You have successfully created";
            return redirect()->route('reportingsequences.create')
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
        $reportingsequence = ReportingSequence::with([
            'user',
            'reportingsequence_details'
        ])->findOrFail($id);

        $users = User::pluck('name', 'id');

        return view(
            'reportingsequences.edit',
            compact('reportingsequence', 'users')
        );
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
        $request->validate([
            'details' => 'required|array|min:1',
            'details.*.report_to' => 'required|integer|exists:users,id',
        ]);

        $reportingSequence = ReportingSequence::findOrFail($id);

        $details = $request->input('details');

        // Check duplicate Reporting To users
        $reportToUsers = array_column($details, 'report_to');

        if (count($reportToUsers) !== count(array_unique($reportToUsers))) {
            return back()
                ->withErrors([
                    'details' => 'Duplicate Reporting To users are not allowed.'
                ])
                ->withInput();
        }

        // Prevent self-reporting
        if (in_array($reportingSequence->user_id, $reportToUsers)) {
            return back()
                ->withErrors([
                    'details' => 'A user cannot report to themselves.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($reportingSequence, $details) {

            // Delete old reporting sequence details
            $reportingSequence->reportingsequence_details()->delete();

            // Insert updated details
            $sequence = 1;

            foreach ($details as $detail) {

                $reportingSequence->reportingsequence_details()->create([
                    'user_id' => $reportingSequence->user_id,
                    'report_to' => $detail['report_to'],
                    'sequence' => $sequence,
                ]);

                $sequence++;
            }
        });

        return redirect()
            ->route('reportingsequences.index')
            ->with('flash_success', 'Reporting Sequence updated successfully.');
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
