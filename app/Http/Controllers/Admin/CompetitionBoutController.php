<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionModel;
use Auth;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Config;

class CompetitionBoutController extends Controller
{
    public function index($encrypted_comp_id)
    {
        $decrypted_comp_id = decrypt_val($encrypted_comp_id);
        $competitionSql = "SELECT COMP_NAME FROM COMPETITION where COMP_ID=?";
        $competition = DB::connection('rksys_app')->select($competitionSql,[$decrypted_comp_id])[0];
        return View('admin.bout.index',compact('encrypted_comp_id'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition);
    }

    public function report()
    {
        $decrypted_comp_id = decrypt_val($encrypted_comp_id);
        $competitionSql = "SELECT * FROM COMPETITION where COMP_ID=?";
        $competition = DB::connection('rksys_app')->select($competitionSql,[$decrypted_comp_id])[0];

        return View('admin.bout.report',compact('encrypted_comp_id'))
        ->with('decrypted_comp_id',$decrypted_comp_id)
        ->with('competition',$competition);
    }

    public function create()
    {
        //
    }

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
