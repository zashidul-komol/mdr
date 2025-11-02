<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $categories = Category::get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
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
            'name' => 'required|unique:categories,name',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $categories = Category::create($data);
        if ($categories) {
            $message = "You have successfully created";
            return redirect()->route('categories.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('categories.index', [])
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
       $categories = Category::findOrFail($id);
        return view('categories.edit', compact('categories'));
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
            'name' => 'required|unique:categories,name,' . $id,
            'status' => 'required',
        ]);

        $categories = Category::where('id', $id)->update($data);
        if ($categories) {
            $message = "You have successfully updated";
            return redirect()->route('categories.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('categories.index', [])
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
        $categories = Category::destroy($id);
        if ($categories) {
            $message = "You have successfully deleted";
            return redirect()->route('categories.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('categories.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function download() {
        return (new OrganizationExport())->download('organization.xlsx');
    }
}
