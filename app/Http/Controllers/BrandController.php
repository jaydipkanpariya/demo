<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\brand;
use Illuminate\Support\Facades\Validator;
class BrandController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return brand::orderBy('created_at', 'asc')->get(); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), ['brand_name' => 'required']);

        if($validator->fails()) 
            return $validator->getMessageBag();
  
        $brand = new brand;
        $brand->brand_name = $request->input('brand_name'); //retrieving user inputs
        $brand->save(); //storing values as an object
        return $brand;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return brand::findorFail($id);
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
        $validator = Validator::make($request->all(), ['brand_name' => 'required']);

        if($validator->fails()) 
            return $validator->getMessageBag();
  
        $brand = brand::find($id);
        if(!$brand)
            return "Brand Not Found";
        $brand->brand_name = $request->input('brand_name'); //retrieving user inputs
        $brand->save(); //storing values as an object
        return $brand;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brand = brand::find($id);
        if(!$brand)
            return "Brand Not Found";//searching for object in database using ID
        if($brand->delete())//deletes the object
             return 'Brand deleted successfully'; //shows a message when the delete operation was successful.

    }
}
