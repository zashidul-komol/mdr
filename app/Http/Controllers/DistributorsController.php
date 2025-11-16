<?php

namespace App\Http\Controllers;
use App\Models\Distributor;
use App\Models\Employee;
use App\Models\Region;
use App\Models\Depot;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use Auth;

class DistributorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $DistributorList = Distributor::with([
            'region'=>function($q){
                return $q->select();
            },
            'depot'=>function($q){
                return $q->select('id', 'name');
            },
            ])
        ->where('status','active')
        ->orderby('region_id', 'ASC')
        ->get();


        //dd($DistributorList->toArray());
        
        $distributors = Distributor::get();
        //dd($distributors->toArray());
        $regions = Region::where('id', $distributors[0]->region_id)->get();
        //dd($regions->toArray());
        return view('distributors.index', compact('distributors', 'regions', 'DistributorList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $authUser  = auth()->user()->id;
        $RegionIdQry = Employee::where('id', Auth::user()->employee_id)->pluck('region_id');
        $regions = Region::pluck('name','id');
        $depots = Depot::pluck('name','id');
        //dd($authUser);
        return view('distributors.create', compact('regions', 'depots'));
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
            'distributorName' => 'required',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $distributors = Distributor::create($data);
        //dd($distributors);
        if ($distributors) {
            $message = "You have successfully created";
            return redirect()->route('distributors.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('distributors.index', [])
                ->with('flash_danger', $message);
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
        $regions = Region::pluck('name','id');
        $depots = Depot::pluck('name','id');
        $distributors = Distributor::findOrFail($id);
        return view('distributors.edit', compact('distributors', 'regions', 'depots'));
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
        $data = $request->except('_method', '_token');
        //dd($id);
        $request->validate([
            'distributorName' => 'required',
            'status' => 'required',
        ]);

        $distributors = Distributor::where('id', $id)->update($data);
        if ($distributors) {
            $message = "You have successfully updated";
            return redirect()->route('distributors.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('distributors.index', [])
                ->with('flash_warning', $message);
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
}
