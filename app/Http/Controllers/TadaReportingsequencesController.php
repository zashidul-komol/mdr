<?php

namespace App\Http\Controllers;

use App\Models\ReportingSequence;
use App\Models\TadaReportingSequence;
use App\Models\User;
use App\Models\Department;
use App\Models\ReportingSequenceDetail;
use App\Models\TadaReportingSequenceDetail;
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
        
        $reportingSequenceDetails = $reportingSequence
            ->tada_reportingsequence_details()
            ->createMany($detials_data);

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
        $tada_reportingsequence = TadaReportingSequence::with([
            'user',
            'tada_reportingsequence_details'
        ])->findOrFail($id);

        $users = User::where('id', '!=', $tada_reportingsequence->user_id)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return view(
            'tada_reportingsequences.edit',
            compact('tada_reportingsequence', 'users')
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

        $tada_reportingsequence = TadaReportingSequence::findOrFail($id);

        $reportToUsers = collect($request->input('details'))
            ->pluck('report_to')
            ->map(fn ($userId) => (int) $userId);

        // Prevent duplicate Reporting To users
        if ($reportToUsers->count() !== $reportToUsers->unique()->count()) {
            return back()
                ->withErrors([
                    'details' => 'Duplicate Reporting To users are not allowed.'
                ])
                ->withInput();
        }

        // Prevent self-reporting
        if ($reportToUsers->contains((int) $tada_reportingsequence->user_id)) {
            return back()
                ->withErrors([
                    'details' => 'A user cannot report to themselves.'
                ])
                ->withInput();
        }

        DB::transaction(function () use ($request, $tada_reportingsequence) {

            // Delete existing reporting sequence details
            $tada_reportingsequence->tada_reportingsequence_details()->delete();

            $sequence = 1;

            // Insert updated reporting sequence details
            foreach ($request->input('details') as $detail) {

                $tada_reportingsequence->tada_reportingsequence_details()->create([
                    'user_id'   => $tada_reportingsequence->user_id,
                    'report_to' => $detail['report_to'],
                    'sequence'  => $sequence,
                ]);

                $sequence++;
            }
        });

        return redirect()
            ->route('tada_reportingsequences.index')
            ->with('flash_success', 'TA/DA Reporting Sequence updated successfully.');
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
