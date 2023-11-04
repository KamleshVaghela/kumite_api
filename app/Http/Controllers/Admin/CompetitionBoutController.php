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
use setasign\Fpdi\Fpdi;

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
                    DB::raw('count(*) as participant_count'), DB::raw("0 as custom_bout_id"), "bouts.gender" #DB::raw("'' as bout_number")
                )
                ->groupBy('bouts.id')
                ->groupBy('bouts.category')
                ->groupBy('bouts.gender')
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
                ->groupBy('custom_bouts.bout_number')
                ->groupBy('custom_bouts.category')
                ->groupBy('custom_bouts.gender')
                
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
        ->with('bout_id',$bout_id)
        ->with('custom_bout_id',$custom_bout_id);
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

    public function download_bout($decrypted_comp_id, $bout_id, $custom_bout_id)
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

        $player_count = count($participants_records);
        $player_conf = Config::get('constants.competition.'.$player_count);

        // dd($player_conf);

        $fpdi = new FPDI;
        
        $filePath = $player_count.".pdf";
        $outputFilePath = $decrypted_comp_id."_".$custom_bout_id."_output.pdf";
        
        $count = $fpdi->setSourceFile($filePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
              
            $fpdi->Image("logo.png",10,2, 32, 32);

            $fpdi->SetFont("helvetica", "b", 18);
            // $fpdi->SetTextColor(153,0,153);
  
            $left = 50;
            $top = 15;
            $text = "19TH International Wadokai Karate Championship-2023";
            $fpdi->Text($left,$top,$text);

            $left = 260;
            $top = 21;
            $text = "B-1";
            $fpdi->Text($left,$top,$text);
            
            $left = 50;
            $top = 25;
            $fpdi->SetFont("helvetica", "b", 17);
            $text = "U7 - Male - WYO - Upto 20 Kg";
            $fpdi->Text($left,$top,$text);

            foreach($participants_records as $key=>$rec) {
                // dd($rec);
                $player_key = $player_conf[$key];
                $competition_conf = Config::get('constants.competition.player_location.'.$player_key);
                // dd($competition_conf);
                $this->print_player_text($fpdi, $competition_conf, $rec);
            }

            // $left = 30;
            // $top = 45;
            // $fpdi->SetFont("helvetica", "b", 14);
            // $text = "Mahek Khetariya";
            // $fpdi->Text($left,$top,$text);

            // $this->print_text($fpdi, 100, 45, "ALPESH M RATHOD (108)", 14);
            // $this->print_text($fpdi, 100, 60, "ALPESH M RATHOD (108)", 14);
            // $this->print_text($fpdi, 100, 75, "ALPESH M RATHOD (108)", 15);
            // $this->print_text($fpdi, 100, 90, "ALPESH M RATHOD (108)", 15);
            // $this->print_text($fpdi, 100, 105, "ALPESH M RATHOD (108)", 15);
            // $this->print_text($fpdi, 100, 120, "ALPESH M RATHOD (108)", 15);
            // $this->print_text($fpdi, 100, 135, "ALPESH M RATHOD (108)", 15);
            // $this->print_text($fpdi, 100, 150, "ALPESH M RATHOD (108)", 15);

           
        }
  
         $fpdi->Output($outputFilePath, 'F');
         return response()->file($outputFilePath);
    }

    public function print_player_text($fpdi, $competition_conf, $player_data) {
        $left_1 = $competition_conf['left_1'];
        $left_2 = $competition_conf['left_2']; 
        $top = $competition_conf['right']; 
        $font = $competition_conf['font']; 
        $style = $competition_conf['style'];
        $fontsize = $competition_conf['fontsize']; 
        
        $fpdi->SetFont($font, $style, $fontsize);
        $fpdi->Text($left_1,$top,strtoupper($player_data->full_name));
        $fpdi->Text($left_2,$top,strtoupper($player_data->external_coach_name));
        // $this->print_text($fpdi, $left_1, $top, strtoupper($player_data["full_name"]), 15);
        // $this->print_text($fpdi, $left_2, $top, strtoupper($player_data["external_coach_name"]), 15);
        // strtoupper
    }

    public function print_text($fpdi, $left, $top, $text, $fontsize) {
        $fpdi->SetFont("helvetica", "b", $fontsize);
        $fpdi->Text($left,$top,$text);
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
