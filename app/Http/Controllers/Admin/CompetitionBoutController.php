<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Competition;
use App\Models\CompetitionModel;
use App\Models\BoutParticipantDetail;
use App\Models\Bout;
use App\Models\Participant;
use Auth;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use Config;

class CompetitionBoutController extends Controller
{
    public function index($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        
        return View('admin.bout.index',compact('decrypted_comp_id'))
        ->with('competition',$competition);
    }

    public function report($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $bout_type = DB::table("bout_participant_details")    
        ->where('bout_participant_details.competition_id',$compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        if($bout_type) {
            if($bout_type->bout_id_count != "0") {
                $bout_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("bouts", function($join) {
                    $join->on("bouts.id", "=", "bout_participant_details.bout_id");
                })
                ->select("bouts.id as bouts_id", "bouts.category as bouts_category",
                    DB::raw('count(*) as participant_count'), DB::raw("0 as custom_bout_id"), "bouts.gender", DB::raw("'' as bout_number")
                )
                ->groupBy('bouts.id')
                ->orderBy('bouts.id')
                ->get();
            } else if($bout_type->custom_bouts_id_count != "0") {
                $bout_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("custom_bouts", function($join) {
                    $join->on("custom_bouts.id", "=", "bout_participant_details.custom_bouts_id");
                })
                ->select("custom_bouts.id as custom_bout_id", "custom_bouts.category as bouts_category",
                    DB::raw('count(*) as participant_count'), DB::raw("0 as bouts_id"), "custom_bouts.gender", "custom_bouts.bout_number" 
                )
                ->groupBy('custom_bouts.id')
                ->orderBy('custom_bouts.id')
                ->get();
            } else {
                $bout_records = [];
            }
        } else {
            $bout_records = [];
        }
        return View('admin.bout.report',compact('decrypted_comp_id'))
        ->with('bout_records',$bout_records)
        ;
    }

    public function data_table($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID',$decrypted_comp_id)->first();
        
        return View('admin.bout.data_table',compact('decrypted_comp_id'))
        ->with('competition',$competition);
    }

    public function data_table_report($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $bout_type = DB::table("bout_participant_details")    
        ->where('bout_participant_details.competition_id',$compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        if($bout_type) {
            if($bout_type->bout_id_count != "0") {
                $participants_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("bouts", function($join) {
                    $join->on("bouts.id", "=", "bout_participant_details.bout_id");
                })
                ->select("bouts.id as bouts_id", "bouts.category as bouts_category",
                    "participants.*", DB::raw("0 as custom_bout_id")
                )
                ->orderBy('bouts.id')
                ->get();
            } else if($bout_type->custom_bouts_id_count != "0") {
                $participants_records = DB::table("participants")    
                ->where('participants.competition_id',$compModel->id)
                ->leftJoin("bout_participant_details", function($join) {
                    $join->on("bout_participant_details.participant_id", "=", "participants.id");
                })
                ->leftJoin("custom_bouts", function($join) {
                    $join->on("custom_bouts.id", "=", "bout_participant_details.custom_bouts_id");
                })
                ->select("custom_bouts.id as custom_bout_id", "custom_bouts.category as bouts_category",
                    "participants.*", DB::raw("0 as bouts_id")
                )
                ->orderBy('custom_bouts.id')
                ->get();
            } else {
                $participants_records = [];
            }
        } else {
            $participants_records = [];
        }
        return View('admin.bout.data_table_report',compact('decrypted_comp_id'))
        ->with('participants_records',$participants_records);
    }

    public function participants($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        if($custom_bout_id != 0) {
            $participants_records = DB::table("bout_participant_details")
            ->where('bout_participant_details.custom_bouts_id',$custom_bout_id)
            ->where('participants.competition_id',$compModel->id)
            ->join("participants", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->select("participants.*")
            ->get();
        }
        else if($bout_id == "0") {
            $participants_records = DB::table("participants")    
            ->where('participants.competition_id',$compModel->id)
            ->leftJoin("bout_participant_details", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->whereNull('bout_participant_details.bout_id')
            ->select("participants.*")
            ->get();
        } else {
            $participants_records = DB::table("bout_participant_details")    
            ->where('bout_participant_details.bout_id',$bout_id)
            ->where('participants.competition_id',$compModel->id)
            ->join("participants", function($join) {
                $join->on("bout_participant_details.participant_id", "=", "participants.id");
            })
            ->select("participants.*")
            ->get();
        }
        
        return View('admin.bout.participants',compact('decrypted_comp_id'))
        ->with('participants_records',$participants_records)
        ->with('bout_id',$bout_id);
    }

    public function karate_ka($decrypted_comp_id, $bout_id, $participant_id)
    {
        $compModel = Competition::where('comp_id',$decrypted_comp_id)->first();

        $participants = DB::table("participants")    
        ->where('participants.competition_id',$compModel->id)
        ->where('participants.id',$participant_id)
        ->select("participants.*")
        ->first();
        
        return View('admin.bout.karate_ka',compact('decrypted_comp_id'))
        ->with('participants',$participants)
        ->with('bout_id',$bout_id);
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
