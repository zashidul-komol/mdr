<?php

namespace App\Http\Controllers;

use App\Machine;
use Illuminate\Http\Request;

class MachinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $machines = Machine::get();
        return view('machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('machines.create');
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
        $request->validate([
            'name' => 'required|unique:machines,name',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $machines = Machine::create($data);
        if ($machines) {
            $message = "You have successfully created";
            return redirect()->route('machines.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('machines.index', [])
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
       $machines = Machine::findOrFail($id);
        return view('machines.edit', compact('machines'));
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
        $request->validate([
            'name' => 'required|unique:machines,name,' . $id,
            'status' => 'required',
        ]);

        $machines = Machine::where('id', $id)->update($data);
        if ($machines) {
            $message = "You have successfully updated";
            return redirect()->route('machines.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('machines.index', [])
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
        $machines = Machine::destroy($id);
        if ($machines) {
            $message = "You have successfully deleted";
            return redirect()->route('machines.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('machines.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function download() {
        return (new OrganizationExport())->download('organization.xlsx');
    }
}
