<?php

namespace App\Http\Controllers\Admin\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competition;
use Auth;
use Carbon\Carbon; 

class CompetitionController extends Controller
{
    public function index()
    {
        return Competition::all();
    }

    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'unique'    => ':attribute is already used'
        ];
        $request->validate([
            'name' => 'required|unique:competitions',
            'short_description' => 'required',

            'fees' => 'required|numeric|gt:0',
            'kata_fees' => 'required|numeric|gt:0',
            'kumite_fees' => 'required|numeric|gt:0',

            'team_kata_fees' => 'required|numeric|gt:0',
            'team_kumite_fees' => 'required|numeric|gt:0',

            'coach_fees' => 'required|numeric|gt:0',

            'level_id' => 'required|exists:competition_level_masters,id',

            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'coach_end_date' => 'required|date|before:start_date',
            'student_reg_end_date' => 'required|date|before:coach_end_date',

            'type_id' => 'required|exists:competition_type_masters,id',
        ], $customMessages);

        $dataObj = new Competition();
        $dataObj->name = $request->name;
        
        $dataObj->short_description = $request->short_description;
        $dataObj->additional_details = $request->additional_details;
        
        $dataObj->fees = $request->fees;
        $dataObj->kata_fees = $request->kata_fees;
        $dataObj->kumite_fees = $request->kumite_fees;
        $dataObj->team_kata_fees = $request->team_kata_fees;
        $dataObj->team_kumite_fees = $request->team_kumite_fees;
        $dataObj->coach_fees = $request->coach_fees;

        $dataObj->level_id  = $request->level_id;

        $dataObj->start_date = $request->start_date;
        $dataObj->end_date = $request->end_date;
        $dataObj->student_reg_end_date = $request->student_reg_end_date;
        $dataObj->coach_end_date = $request->coach_end_date;

        $dataObj->type_id  = $request->type_id;
        
        $dataObj->user_id = Auth::user()->id;
        $dataObj->last_modified = Carbon::now();
        $dataObj->last_modified_user_id = Auth::user()->id;
        $dataObj->save();

        return response([
            'data' => $dataObj,
            'message' => 'Competition inserted successfully',
            'success' => 'true'
        ], 200);
    }

    public function show($id)
    {
        return Competition::find($id);
    }

    public function update(Request $request, $id)
    {
        $dataObj = Competition::find($id);
        if ($dataObj) {

            $customMessages = [
                'required' => 'The :attribute field is required.',
                'unique'    => ':attribute is already used'
            ];
            $request->validate([
                'name' => 'required|unique:competitions,name,'.$dataObj->id,
                'short_description' => 'required',
    
                'fees' => 'required|numeric|gt:0',
                'kata_fees' => 'required|numeric|gt:0',
                'kumite_fees' => 'required|numeric|gt:0',
    
                'team_kata_fees' => 'required|numeric|gt:0',
                'team_kumite_fees' => 'required|numeric|gt:0',
    
                'coach_fees' => 'required|numeric|gt:0',
    
                'level_id' => 'required|exists:competition_level_masters,id',
    
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'coach_end_date' => 'required|date|before:start_date',
                'student_reg_end_date' => 'required|date|before:coach_end_date',
    
                'type_id' => 'required|exists:competition_type_masters,id',
            ], $customMessages);

            $dataObj->name = $request->name;
            
            $dataObj->short_description = $request->short_description;
            $dataObj->additional_details = $request->additional_details;
            
            $dataObj->fees = $request->fees;
            $dataObj->kata_fees = $request->kata_fees;
            $dataObj->kumite_fees = $request->kumite_fees;
            $dataObj->team_kata_fees = $request->team_kata_fees;
            $dataObj->team_kumite_fees = $request->team_kumite_fees;
            $dataObj->coach_fees = $request->coach_fees;
    
            $dataObj->level_id  = $request->level_id;
    
            $dataObj->start_date = $request->start_date;
            $dataObj->end_date = $request->end_date;
            $dataObj->student_reg_end_date = $request->student_reg_end_date;
            $dataObj->coach_end_date = $request->coach_end_date;
    
            $dataObj->type_id  = $request->type_id;
            
            $dataObj->last_modified = Carbon::now();
            $dataObj->last_modified_user_id = Auth::user()->id;
            $dataObj->save();

            $dataObj->save();

            return response([
                'data' => $dataObj,
                'message' => 'Competition updated successfully',
                'success' => 'true'
            ], 200);
        }
        else {
            return response([
                'message' => 'Competition not found',
                'success' => 'false'
            ], 404);
        }
    }

    public function destroy($id)
    {
        $dataObj = Competition::find($id);
        if ($dataObj) {
            $dataObj->delete();

            return response([
                'message' => 'Competition Deleted Successfully',
                'success' => 'true'
            ], 200);
        }
        else {
            return response([
                'message' => 'Competition not found',
                'success' => 'false'
            ], 404);
        }
    }
}
