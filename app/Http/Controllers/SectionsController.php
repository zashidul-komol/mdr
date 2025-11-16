<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Department;
use Illuminate\Http\Request;

class SectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $sections = Section::with([
            'department'=>function($q){
                return $q->select('id', 'name');
            },
            ])
        ->where('status','active')
        ->orderby('department_id', 'ASC')
        ->get();
        //dd($sections->toArray());

       //$sections = Section::get();
        return view('sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::pluck('name','id');
        return view('sections.create', compact('departments'));
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
            'name' => 'required|unique:sections,name',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $sections = Section::create($data);
        if ($sections) {
            $message = "You have successfully created";
            return redirect()->route('sections.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('sections.index', [])
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
       
       $sections = Section::findOrFail($id);
       $departments = Department::pluck('name','id');

        return view('sections.edit', compact('sections', 'departments'));
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
        //dd ('komol');
        $data = $request->except('_method', '_token');
        $request->validate([
            'name' => 'required|unique:sections,name,' . $id,
            'status' => 'required',
        ]);

        $sections = Section::where('id', $id)->update($data);
        if ($sections) {
            $message = "You have successfully updated";
            return redirect()->route('sections.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('sections.index', [])
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
        $sections = Section::destroy($id);
        if ($sections) {
            $message = "You have successfully deleted";
            return redirect()->route('sections.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('sections.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function download() {
        return (new OrganizationExport())->download('organization.xlsx');
    }
}
