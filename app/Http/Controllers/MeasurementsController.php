<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use Illuminate\Http\Request;

class MeasurementsController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $measurements = Measurement::get();
        return view('measurements.index', compact('measurements'));
    }

     public function create()
    {
       
       return view('measurements.create', compact('measurements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('_token');
        //dd($data);
        $request->validate([
            'name' => 'required|unique:measurements,name',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $measurements = Measurement::create($data);
        if ($measurements) {
            $message = "You have successfully created";
            return redirect()->route('measurements.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('measurements.index', [])
                ->with('flash_danger', $message);
        }
    }


    public function edit($id)
    {
        $measurements = Measurement::findOrFail($id);
       
        return view('measurements.edit', compact('measurements'));
    }


    public function update(Request $request, $id)
    {
        $data = $request->except('_method', '_token');
        $validated = $request->validate([
            'name' => 'required|unique:measurements,name,' . $id,
            'status' => 'required',
        ]);

        $measurements = Measurement::whereKey($id)->update($validated);
        if ($measurements) {
            $message = "You have successfully updated";
            return redirect()->route('measurements.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('measurements.index', [])
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
        $measurements = Measurement::destroy($id);
        if ($measurements) {
            $message = "You have successfully deleted";
            return redirect()->route('measurements.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('measurements.index', [])
                ->with('flash_danger', $message);
        }
    }
}
