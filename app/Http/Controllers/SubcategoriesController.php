<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use App\Models\Category;
use Illuminate\Http\Request;

class SubcategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $subcategories = Subcategory::with([
            'category'=>function($q){
                return $q->select('id', 'name');
            },
            ])
        ->where('status','active')
        ->get();
        //dd($subcategories->toArray());
       //$subcategories = Subcategory::get();
        return view('subcategories.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name','id');
        return view('subcategories.create', compact('categories'));
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
            'name' => 'required|unique:subcategories,name',
            'status' => 'required',
        ]);
        //$data['sequence'] = Department::max('sequence') + 1;
        $subcategories = Subcategory::create($data);
        if ($subcategories) {
            $message = "You have successfully created";
            return redirect()->route('subcategories.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('subcategories.index', [])
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
       $categories = Category::pluck('name','id');
       $subcategories = Subcategory::findOrFail($id);
        return view('subcategories.edit', compact('subcategories', 'categories'));
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
            'name' => 'required|unique:subcategories,name,' . $id,
            'status' => 'required',
        ]);

        $subcategories = Subcategory::where('id', $id)->update($data);
        if ($subcategories) {
            $message = "You have successfully updated";
            return redirect()->route('subcategories.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('subcategories.index', [])
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
        $subcategories = Subcategory::destroy($id);
        if ($subcategories) {
            $message = "You have successfully deleted";
            return redirect()->route('subcategories.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('subcategories.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function download() {
        return (new OrganizationExport())->download('organization.xlsx');
    }
}
