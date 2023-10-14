<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DefaultCategoryMaster;
use Illuminate\Support\Facades\DB;

class DefaultCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('admin.default_category.index');
    }

    public function report()
    { 
        $default_category_masters = DB::table("default_category_masters")    
        ->select(DB::raw('DISTINCT category_group '))
        ->get();
        return View('admin.default_category.report',compact('default_category_masters'));
    }

    public function categories($category)
    { 
        $categories = DB::table("default_category_masters")    
        ->where('category_group', '=', $category)
        ->orderBy('id')
        ->get();
        return View('admin.default_category.categories',compact('categories'));
    }

    public function category($category_id)
    { 
        $category = DB::table("default_category_masters")    
        ->where('id', '=', $category_id)
        ->first();
        return View('admin.default_category.category',compact('category'));
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
        //
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
        //
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
