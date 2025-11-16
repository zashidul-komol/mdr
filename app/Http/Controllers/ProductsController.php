<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Department;
use App\Models\Section;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Machine;
use App\Models\Employee;
use App\Exports\ProductsExport;
use App\Traits\PhpExcelFormater;
use Carbon\Carbon;
use Auth;


class ProductsController extends Controller
{
    use PhpExcelFormater;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AuthDeptID = Auth::user()->department_id;
        $AuthSectionID = Auth::user()->section_id;
        $user_id = auth()->user()->id;
        //dd($user_id);
        $products = Product::with([
            'department'=>function($q){
                return $q->select('id', 'name');
            },
            'section'=>function($q){
                return $q->select('id', 'name');
            },
            'category'=>function($q){
                return $q->select('id', 'name');
            },
            'subcategory'=>function($q){
                return $q->select('id', 'name');
            },
            
        ])
        ->where('status','active')
        ->where('section_id', $AuthSectionID)
        //->orWhere('id', 1)
        ->get();
        //dd($products->toArray());
        //$products = Product::get();


        //$products->load(['vehicles']);
        //dd($products);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::pluck('name','id');
        $sections = Section::pluck('name','id');
        $categories = Category::pluck('name','id');
        $subcategories = Subcategory::pluck('name','id');
        return view('products.create', compact('departments', 'sections', 'categories', 'subcategories'));
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
        $data = $request->except('_method', '_token');
        $request->validate([
            'category_id' => 'required',
            'subcategory_id' => 'required',
            'department_id' => 'required',
            'section_id' => 'required',
            'tags' => 'required',
            'name' => 'required|unique:products,name',
            'code' => 'required|unique:products,code',
            'status' => 'required',
        ]);

        //$data['seqcomplainTypesuence'] = Department::max('sequence') + 1;
        $products = Product::create($data);
        if ($products) {
            $message = "You have successfully created";
            return redirect()->route('products.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('products.index', [])
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
        $departments = Department::pluck('name','id');
        $sections = Section::pluck('name','id');
        $categories = Category::pluck('name','id');
        $subcategories = Subcategory::pluck('name','id');
        $products = Product::findOrFail($id);
        return view('products.edit', compact('products', 'departments', 'sections', 'categories', 'subcategories'));
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
            'status' => 'required',
        ]);

        $products = Product::where('id', $id)->update($data);
        if ($products) {
            $message = "You have successfully updated";
            return redirect()->route('products.index', [])
                ->with('flash_success', $message);

        } else {
            $message = "Nothing changed!! Please try again";
            return redirect()->route('products.index', [])
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
        $products = Product::destroy($id);
        if ($products) {
            $message = "You have successfully deleted";
            return redirect()->route('products.index', [])
                ->with('flash_success', $message);
        } else {
            $message = "Something wrong!! Please try again";
            return redirect()->route('products.index', [])
                ->with('flash_danger', $message);
        }
    }

    public function getProductTag($param)
    {
        //dd($param);
        $product = Product::findOrFail($param);
        if($product->tags == 'vehicles'){
            $productsItems = Vehicle::where('status', 'active')->pluck('name', 'id');
            return response()->json([
                'particulars' => $productsItems,
                'particular_type' => 'vehicles',
            ]);
        }elseif ($product->tags == 'employees') {
            $productsItems = Employee::where('status', 'active')->pluck('name', 'id');
            return response()->json([
                'particulars' => $productsItems,
                'particular_type' => 'employees',
            ]);
        }elseif ($product->tags == 'machines') {
            $productsItems = Machine::where('status', 'active')->pluck('name', 'id');
            return response()->json([
                'particulars' => $productsItems,
                'particular_type' => 'machines',
            ]);
        }else{
            return response()->json([]);
        }
        
    }

    public function getRequisitionEdit($param)
    {
        dd($param);
        $product = Product::findOrFail($param);
        if($product->tags == 'vehicles'){
            $productsItems = Vehicle::where('status', 'active')->pluck('name', 'id');
            return response()->json([
                'particulars' => $productsItems,
                'particular_type' => 'vehicles',
            ]);
        }elseif ($product->tags == 'employees') {
            $productsItems = Employee::where('status', 'active')->pluck('name', 'id');
            return response()->json([
                'particulars' => $productsItems,
                'particular_type' => 'employees',
            ]);
        }elseif ($product->tags == 'machines') {
            $productsItems = Machine::where('status', 'active')->pluck('name', 'id');
            return response()->json([
                'particulars' => $productsItems,
                'particular_type' => 'machines',
            ]);
        }else{
            return response()->json([]);
        }
        
    }

    public function uploadProduct(Request $request) {
        ini_set('max_execution_time', 60000);
        /*
         file path must be absolute and related to local drive
         */
        //dd('Komol');
        if ($request->isMethod('post')) {
            $file = $request->file('file');
            
            $request->validate([
                'file' => 'required|mimes:xlsx|max:1024',
            ]);
            //dd($request);
            $filePath = $file->getRealPath();
            $excelDataArray = $this->dumptoarray($filePath);
            //dd('Sarker');
            //dd($excelDataArray);
            $departments = Department::pluck('name','id');
            $sections = Section::pluck('name','id');
            $categories = Category::pluck('name','id');
            $subcategories = Subcategory::pluck('name','id');
            $dataArray = [];

            foreach ($excelDataArray as $key => $value) {
                //dd($value) ;     
                $data = [];
                $data['category_id'] = $categories->search(trim($value['category']));
                $data['subcategory_id'] = $subcategories->search(trim($value['sub_category']));
                $data['name'] = $value['product_name'];
                $data['code'] = $value['code'];
                $data['tags'] = $value['product_tags'];
                $data['specification'] = $value['specification'];
                $data['department_id'] = $departments->search(trim(html_entity_decode($value['deptartment']))) ?: 0;
                $data['section_id'] = $sections->search(trim(html_entity_decode($value['section']))) ?: 0;
                $data['status'] = $value['status'];

                //dd($products->toArray());
                //dd($data->toArray());
                 
                $existProductId = Product::where('code', $value['code'])
                ->Where('name',$value['product_name'])
                ->value('id');
                //if product exist then update
                if($existProductId){
                    Product::where('id',$existProductId)->update($data);
                }else{
                    $dataArray[$key] = $data;
                    $dataArray[$key]['updated_at'] = Carbon::now();
                    $dataArray[$key]['created_at'] = Carbon::now();
                    
                    
                }
                
            }
            $products = Product::insert($dataArray);
            if ($products) {
                $message = "Successfully Uploaded";
                return redirect()->route('products.uploads')
                ->with('flash_success', $message);
            } else {
                $message = "Something wrong!! Please try again";
                return redirect()->route('products.uploads')
                ->with('flash_danger', $message);
            }
            
        } else {
            //dd('Sarker');
            return view('products.uploads');
        }
       
    }

    public function download() {
        return (new ProductsExport())->download('products.xlsx');
    }


}
