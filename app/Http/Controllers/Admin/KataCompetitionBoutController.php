<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Bout;
use App\Models\BoutKataParticipantDetail;
use App\Models\Competition;
use App\Models\CompetitionModel;
use App\Models\CompetitionPartModel;
use App\Models\customKataBout;
use App\Models\Participant;
use Auth;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDF;
use setasign\Fpdi\Fpdi;
use SplFileObject;

class KataCompetitionBoutController extends Controller
{
    private $dt;

    public function __construct()
    {
        $this->dt = Carbon::now();
    }

    public function index($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID', $decrypted_comp_id)->first();

        return View('admin.kata_bout.index', compact('decrypted_comp_id'))
        ->with('competition', $competition);
    }

    public function report($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();

        $bout_type = DB::table('bout_kata_participant_details')
        ->where('bout_kata_participant_details.competition_id', $compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        if ($bout_type) {
            if ($bout_type->bout_id_count != '0') {
                $bout_records = DB::table('participants')
                ->where('participants.competition_id', $compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kata')
                          ->orWhere('participants.kata', '=', '1');
                })
                ->leftJoin('bout_kata_participant_details', function ($join) {
                    $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
                })
                ->join('bouts', function ($join) {
                    $join->on('bouts.id', '=', 'bout_kata_participant_details.bout_id');
                })
                ->select('bouts.id as bouts_id', 'bouts.category as bouts_category',
                    DB::raw('count(*) as participant_count'), DB::raw('0 as custom_bout_id'), 'bouts.gender' //DB::raw("'' as bout_number")
                )
                ->groupBy('bouts.id')
                ->groupBy('bouts.category')
                ->groupBy('bouts.gender')
                ->orderBy('bouts.id')
                ->get();
            } elseif ($bout_type->custom_bouts_id_count != '0') {
                $bout_records = DB::table('participants')
                ->where('participants.competition_id', $compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kata')
                          ->orWhere('participants.kata', '=', '1');
                })
                ->join('bout_kata_participant_details', function ($join) {
                    $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
                })
                ->leftJoin('custom_kata_bouts', function ($join) {
                    $join->on('custom_kata_bouts.id', '=', 'bout_kata_participant_details.custom_bouts_id');
                })
                ->select(DB::raw('ifnull(custom_kata_bouts.id,0) as custom_bout_id'), 'custom_kata_bouts.category as bouts_category',
                    DB::raw('count(*) as participant_count'), DB::raw('0 as bouts_id'), 'custom_kata_bouts.gender', 'custom_kata_bouts.bout_number'
                )
                ->groupBy('custom_kata_bouts.id')
                ->groupBy('custom_kata_bouts.bout_number')
                ->groupBy('custom_kata_bouts.category')
                ->groupBy('custom_kata_bouts.gender')

                ->orderBy('custom_kata_bouts.bout_number')
                ->get();
            } else {
                $bout_records = [];
            }
        } else {
            $bout_records = [];
        }

        return View('admin.kata_bout.report', compact('decrypted_comp_id'))
        ->with('bout_records', $bout_records)
        ->with('bout_id', $bout_type->bout_id_count)
        ->with('custom_bout_id', $bout_type->custom_bouts_id_count);
    }

    public function data_table($decrypted_comp_id)
    {
        $competition = CompetitionModel::where('COMP_ID', $decrypted_comp_id)->first();

        return View('admin.kata_bout.data_table', compact('decrypted_comp_id'))
        ->with('competition', $competition);
    }

    public function data_table_report($decrypted_comp_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();

        $bout_type = DB::table('bout_kata_participant_details')
        ->where('bout_kata_participant_details.competition_id', $compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        if ($bout_type) {
            if ($bout_type->bout_id_count != '0') {
                $participants_records = DB::table('participants')
                ->where('participants.competition_id', $compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kumite')
                          ->orWhere('participants.kumite', '=', '1');
                })
                ->leftJoin('bout_kata_participant_details', function ($join) {
                    $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
                })
                ->leftJoin('bouts', function ($join) {
                    $join->on('bouts.id', '=', 'bout_kata_participant_details.bout_id');
                })
                ->select('bouts.id as bouts_id', 'bouts.category as bouts_category',
                    'participants.*', DB::raw('0 as custom_bout_id')
                )
                ->orderBy('bouts.id')
                ->get();
            } elseif ($bout_type->custom_bouts_id_count != '0') {
                $participants_records = DB::table('participants')
                ->where('participants.competition_id', $compModel->id)
                ->where(function ($query) {
                    $query->whereNull('participants.kumite')
                          ->orWhere('participants.kumite', '=', '1');
                })
                ->join('bout_kata_participant_details', function ($join) {
                    $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
                })
                ->leftJoin('custom_kata_bouts', function ($join) {
                    $join->on('custom_kata_bouts.id', '=', 'bout_kata_participant_details.custom_bouts_id');
                })
                ->select('custom_kata_bouts.id as custom_bout_id', 'custom_kata_bouts.category as bouts_category',
                    'participants.*', DB::raw('0 as bouts_id')
                )
                ->orderBy('custom_kata_bouts.id')
                ->get();
            } else {
                $participants_records = [];
            }
        } else {
            $participants_records = [];
        }

        return View('admin.kata_bout.data_table_report', compact('decrypted_comp_id'))
        ->with('participants_records', $participants_records)
        ->with('bout_id', $bout_type->bout_id_count)
        ->with('custom_bout_id', $bout_type->custom_bouts_id_count);
    }

    public function participants($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();

        if ($custom_bout_id != 0) {
            $participants_records = DB::table('bout_kata_participant_details')
            ->where('bout_kata_participant_details.custom_bouts_id', $custom_bout_id)
            ->where('participants.competition_id', $compModel->id)
            ->join('participants', function ($join) {
                $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
            })
            ->leftJoin('custom_kata_bouts', function ($join) {
                $join->on('custom_kata_bouts.id', '=', 'bout_kata_participant_details.custom_bouts_id');
            })
            ->select('participants.*', 'custom_kata_bouts.first', 'custom_kata_bouts.second', 'custom_kata_bouts.third_1', 'custom_kata_bouts.third_2', 'bout_kata_participant_details.participant_sequence')
            ->orderBy('bout_kata_participant_details.participant_sequence')
            ->get();
            $boutObj = customKataBout::find($custom_bout_id);
        } elseif ($bout_id != '0') {
            $participants_records = DB::table('participants')
            ->where('participants.competition_id', $compModel->id)
            ->where(function ($query) {
                $query->whereNull('participants.kumite')
                      ->orWhere('participants.kumite', '=', '1');
            })
            ->leftJoin('bout_kata_participant_details', function ($join) {
                $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
            })
            ->leftJoin('bouts', function ($join) {
                $join->on('bouts.id', '=', 'bout_kata_participant_details.bout_id');
            })
            ->select('participants.*', 'bouts.first', 'bouts.second', 'bouts.third_1', 'bouts.third_2', 'bout_kata_participant_details.participant_sequence')
            ->orderBy('bout_kata_participant_details.participant_sequence')
            ->get();
            $boutObj = Bout::find($bout_id);
        } else {
            $participants_records = DB::table('participants')
            // ->where('bout_kata_participant_details.bout_id',$bout_id)
            ->where('participants.competition_id', $compModel->id)
            ->where(function ($query) {
                $query->whereNull('participants.kumite')
                      ->orWhere('participants.kumite', '=', '1');
            })
            ->leftJoin('bout_kata_participant_details', function ($join) {
                $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
            })
            // ->leftJoin("bouts", function($join) {
            //     $join->on("bouts.id", "=", "bout_kata_participant_details.bout_id");
            // })
            ->whereNull('bout_kata_participant_details.id')
            ->select('participants.*', 'bout_kata_participant_details.participant_sequence')
            ->orderBy('bout_kata_participant_details.participant_sequence')
            ->get();
            $boutObj = Bout::find($bout_id);
        }
        // dd($participants_records);
        return View('admin.kata_bout.participants', compact('decrypted_comp_id'))
        ->with('participants_records', $participants_records)
        ->with('bout_id', $bout_id)
        ->with('custom_bout_id', $custom_bout_id)
        ->with('boutObj', $boutObj);
    }

    public function change_bout($decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();
        $boutList = null;

        $participant = DB::table('participants')
        ->where('participants.competition_id', $compModel->id)
        ->where('participants.id', $participant_id)
        ->select('participants.*')
        ->first();

        if ($bout_id != '0') {
            $boutListSql = " 
            SELECT C.id, C.gender,C.category, 0 as bout_number ,count(D.id) as total_participants
            FROM bouts C
            LEFT JOIN bout_kata_participant_details D on D.custom_bouts_id = C.id
            where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
            group by C.id, C.gender,C.category, C.bout_number
            Having total_participants < 9
            ";
        } elseif ($custom_bout_id != '0') {
            $boutListSql = "  
            SELECT C.id, C.gender,C.category, C.bout_number ,count(D.id) as total_participants
            FROM custom_kata_bouts C
            LEFT JOIN bout_kata_participant_details D on D.custom_bouts_id = C.id
            where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
            group by C.id, C.gender,C.category, C.bout_number
            Having total_participants < 9
            ";
        } else {
            $bout_type = DB::table('bout_kata_participant_details')
            ->where('bout_kata_participant_details.competition_id', $compModel->id)
            ->select(
                DB::raw('count(bout_id) as bout_id_count'),
                DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
            )
            ->first();
            if ($bout_type->bout_id_count != '0') {
                $boutListSql = " 
                SELECT C.id, C.gender,C.category, 0 as bout_number ,count(D.id) as total_participants
                FROM bouts C
                LEFT JOIN bout_kata_participant_details D on D.custom_bouts_id = C.id
                where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
                group by C.id, C.gender,C.category, C.bout_number
                Having total_participants < 9
                ";
            } elseif ($bout_type->custom_bouts_id_count != '0') {
                $boutListSql = " 
                SELECT C.id, C.gender,C.category, C.bout_number ,count(D.id) as total_participants
                FROM custom_kata_bouts C
                LEFT JOIN bout_kata_participant_details D on D.custom_bouts_id = C.id
                where C.competition_id=$compModel->id AND C.gender='$participant->gender' AND C.first IS NULL AND C.second IS NULL AND C.third_1 IS NULL AND C.third_2 IS NULL 
                group by C.id, C.gender,C.category, C.bout_number
                Having total_participants < 9
                ";
            } else {
                $boutListSql = '';
            }
        }
        // dd($boutListSql);
        if ($boutListSql != '') {
            $boutList = DB::select($boutListSql);
        }

        return View('admin.kata_bout.change_bout', compact('decrypted_comp_id'))
        ->with('participants', $participant)
        ->with('bout_id', $bout_id)
        ->with('custom_bout_id', $custom_bout_id)
        ->with('details_key', 'change_bout_details')
        ->with('boutList', $boutList);
    }

    public function save_change_bout(Request $request, $decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id)
    {
        $request->validate([
            'bout_id' => 'required',
            'bout_number' => 'required_if:bout_id,!=,0',
            'category' => 'required_if:bout_id,!=,0',
            'tatami' => 'required_if:bout_id,!=,0',
            'session' => 'required_if:bout_id,!=,0',
        ]);

        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();

        $bout_type = DB::table('bout_kata_participant_details')
        ->where('bout_kata_participant_details.competition_id', $compModel->id)
        ->select(
            DB::raw('count(bout_id) as bout_id_count'),
            DB::raw('count(custom_bouts_id) as custom_bouts_id_count')
        )
        ->first();

        $boutData = null;

        if ($request->bout_id != 0) {
            if ($bout_type->bout_id_count != '0') {
                $boutData = Bout::where('id', $request->bout_id)->first();
            } elseif ($bout_type->custom_bouts_id_count != '0') {
                $boutData = customKataBout::where('id', $request->bout_id)->first();
            }
        } else {
            if ($bout_type->bout_id_count != '0') {
                $boutData = new Bout();
                $boutData->competition_id = $compModel->id;
                $boutData->gender = $request->gender;
                $boutData->category = $request->category;
                $boutData->bout_number = $request->bout_number;
                $boutData->tatami = $request->tatami;
                $boutData->session = $request->session;
                $boutData->save();
            } elseif ($bout_type->custom_bouts_id_count != '0') {
                $boutData = new customKataBout();
                $boutData->competition_id = $compModel->id;
                $boutData->gender = $request->gender;
                $boutData->category = $request->category;
                $boutData->bout_number = $request->bout_number;
                $boutData->tatami = $request->tatami;
                $boutData->session = $request->session;
                $boutData->save();
            }
        }

        $compBoutParticipantDetail = BoutKataParticipantDetail::where('participant_id', $participant_id)
        ->where('competition_id', $boutData->competition_id)
        ->first();

        if ($compBoutParticipantDetail) {
            $compBoutParticipantDetailList = BoutKataParticipantDetail::where('competition_id', $boutData->competition_id)
            ->where('participant_sequence', '>', $compBoutParticipantDetail->participant_sequence);

            if ($boutData instanceof \App\Models\Bout) {
                $compBoutParticipantDetailList
                ->where('bout_id', $compBoutParticipantDetail->bout_id)
                ->update([
                    'participant_sequence' => DB::raw('`participant_sequence` - 1'),
                ]);
            } else {
                $compBoutParticipantDetailList
                ->where('custom_bouts_id', $compBoutParticipantDetail->custom_bouts_id)
                ->update([
                    'participant_sequence' => DB::raw('`participant_sequence` - 1'),
                ]);
            }

            if ($boutData instanceof \App\Models\Bout) {
                $compBoutParticipantDetail->bout_id = $boutData->id;
            } else {
                $compBoutParticipantDetail->custom_bouts_id = $boutData->id;
            }
            $compBoutParticipantDetail->participant_sequence = $request->sequence;
            $compBoutParticipantDetail->last_modified = \Carbon\Carbon::now();
            $compBoutParticipantDetail->last_modified_user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();
        } else {
            $compBoutParticipantDetail = new BoutKataParticipantDetail();
            $compBoutParticipantDetail->competition_id = $boutData->competition_id;
            if ($boutData instanceof \App\Models\Bout) {
                $compBoutParticipantDetail->bout_id = $boutData->id;
            } else {
                $compBoutParticipantDetail->custom_bouts_id = $boutData->id;
            }
            $compBoutParticipantDetail->participant_id = $participant_id;
            $compBoutParticipantDetail->participant_sequence = $request->sequence;
            $compBoutParticipantDetail->user_id = Auth::user()->id;
            $compBoutParticipantDetail->last_modified = \Carbon\Carbon::now();
            $compBoutParticipantDetail->last_modified_user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();
        }

        $compBoutParticipantDetailList = BoutKataParticipantDetail::where('competition_id', $boutData->competition_id)
        ->where('participant_sequence', '>=', $compBoutParticipantDetail->participant_sequence)
        ->where('id', '!=', $compBoutParticipantDetail->id);

        if ($boutData instanceof \App\Models\Bout) {
            $compBoutParticipantDetailList
            ->where('bout_id', $boutData->id)
            ->update([
                'participant_sequence' => DB::raw('`participant_sequence` + 1'),
            ]);
        } else {
            $compBoutParticipantDetailList
            ->where('custom_bouts_id', $boutData->id)
            ->update([
                'participant_sequence' => DB::raw('`participant_sequence` + 1'),
            ]);
        }

        return response([
            'data' => $compBoutParticipantDetail,
            'updated_data' => $compBoutParticipantDetailList,
            'message' => 'Result updated successfully',
            'alert-type' => 'success',
        ], 200);
    }

    public function karate_ka($decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();
        $boutObj = null;

        if ($bout_id != '0') {
            $boutObj = Bout::find($bout_id);
        } elseif ($custom_bout_id != '0') {
            $boutObj = customKataBout::find($custom_bout_id);
        }
        // dd($dataObj);

        $participants = DB::table('participants')
        ->where('participants.competition_id', $compModel->id)
        ->where('participants.id', $participant_id)
        ->select('participants.*')
        ->first();

        // dd($participants);

        return View('admin.kata_bout.karate_ka', compact('decrypted_comp_id'))
        ->with('participants', $participants)
        ->with('bout_id', $bout_id)
        ->with('custom_bout_id', $custom_bout_id)
        ->with('details_key', 'result_details')
        ->with('boutObj', $boutObj);
    }

    public function save_data(Request $request, $decrypted_comp_id, $bout_id, $custom_bout_id, $participant_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();
        $participant = Participant::find($participant_id);
        $competitionPartModel = CompetitionPartModel::find($participant->external_unique_id);

        if ($bout_id != '0') {
            $dataObj = Bout::find($bout_id);
        } elseif ($custom_bout_id != '0') {
            $dataObj = customKataBout::find($custom_bout_id);
        }
        if ($request->result == '1') {
            $dataObj->first = $participant_id;
            $competitionPartModel->KATA_RES = 1;

            if ($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if ($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
            if ($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }
            if ($dataObj->third_3 == $participant_id) {
                $dataObj->third_3 = 0;
            }
        } elseif ($request->result == '2') {
            $dataObj->second = $participant_id;
            $competitionPartModel->KATA_RES = 2;

            if ($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if ($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
            if ($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }
            if ($dataObj->third_3 == $participant_id) {
                $dataObj->third_3 = 0;
            }
        } elseif ($request->result == '3') {
            $dataObj->third_1 = $participant_id;
            $competitionPartModel->KATA_RES = 3;

            if ($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if ($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if ($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }
            if ($dataObj->third_3 == $participant_id) {
                $dataObj->third_3 = 0;
            }
        } elseif ($request->result == '4') {
            $dataObj->third_2 = $participant_id;
            $competitionPartModel->KATA_RES = 3;

            if ($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if ($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if ($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
            if ($dataObj->third_3 == $participant_id) {
                $dataObj->third_3 = 0;
            }
        } elseif ($request->result == '5') {
            $dataObj->third_2 = $participant_id;
            $competitionPartModel->KATA_RES = 3;

            if ($dataObj->first == $participant_id) {
                $dataObj->first = 0;
            }
            if ($dataObj->second == $participant_id) {
                $dataObj->second = 0;
            }
            if ($dataObj->third_1 == $participant_id) {
                $dataObj->third_1 = 0;
            }
            if ($dataObj->third_2 == $participant_id) {
                $dataObj->third_2 = 0;
            }
        }
        $dataObj->save();
        $competitionPartModel->save();

        return response([
            'data' => $dataObj,
            'message' => 'Result updated successfully',
            'alert-type' => 'success',
        ], 200);
    }

    public function download_all_bout($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();

        if ($custom_bout_id != 0) {
            $bout_records = customKataBout::where('competition_id', $compModel->id)->orderBy('bout_number')->get();
        } elseif ($bout_id == '0') {
            $bout_records = Bout::where('competition_id', $compModel->id)->get();
        } else {
            $bout_records = Bout::where('competition_id', $compModel->id)->get();
        }

        $outputFileList = [];

        foreach ($bout_records as $key => $rec) {
            if ($custom_bout_id != 0) {
                $boutParticipantDetailCount = BoutKataParticipantDetail::where('competition_id', $compModel->id)->where('custom_bouts_id', $rec->id)->get()->count();
                if ($boutParticipantDetailCount != 0) {
                    [$fpdi, $outputFilePath] = $this->generate_bout($decrypted_comp_id, 0, $rec->id);
                }
            } else {
                $boutParticipantDetailCount = BoutKataParticipantDetail::where('competition_id', $compModel->id)->where('bout_id', $rec->id)->get()->count();
                if ($boutParticipantDetailCount != 0) {
                    [$fpdi, $outputFilePath] = $this->generate_bout($decrypted_comp_id, $rec->id, 0);
                }
            }
            $fpdi->Output($outputFilePath, 'F');
            array_push($outputFileList, $outputFilePath);
        }

        // dd($outputFileList);
        $pageCount = 0;
        // initiate FPDI
        $pdf = new FPDI();

        // iterate through the files
        foreach ($outputFileList as $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                // use the imported page
                $pdf->useTemplate($templateId);
            }
        }
        $pdf->Output('D', 'Kata_'.$decrypted_comp_id.'.pdf', 'F');

        foreach ($outputFileList as $file) {
            $flag = FileHelper::delete_files($file);
        }
    }

    public function generate_bout($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        $compModel = Competition::where('comp_id', $decrypted_comp_id)->first();

        if ($custom_bout_id != 0) {
            $participants_records = DB::table('bout_kata_participant_details')
            ->where('bout_kata_participant_details.custom_bouts_id', $custom_bout_id)
            ->where('participants.competition_id', $compModel->id)
            ->join('participants', function ($join) {
                $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
            })
            ->select('participants.*')
            ->orderBy('bout_kata_participant_details.participant_sequence')
            ->get();
            $bout_record = customKataBout::find($custom_bout_id);
        } elseif ($bout_id == '0') {
            $participants_records = DB::table('participants')
            ->where('participants.competition_id', $compModel->id)
            ->where(function ($query) {
                $query->whereNull('participants.kumite')
                      ->orWhere('participants.kumite', '=', '1');
            })
            ->leftJoin('bout_kata_participant_details', function ($join) {
                $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
            })
            ->whereNull('bout_kata_participant_details.bout_id')
            ->select('participants.*')
            ->orderBy('bout_kata_participant_details.participant_sequence')
            ->get();
            $bout_record = Bout::find($bout_id);
        } else {
            $participants_records = DB::table('bout_kata_participant_details')
            ->where('bout_kata_participant_details.bout_id', $bout_id)
            ->where('participants.competition_id', $compModel->id)
            ->join('participants', function ($join) {
                $join->on('bout_kata_participant_details.participant_id', '=', 'participants.id');
            })
            ->select('participants.*')
            ->orderBy('bout_kata_participant_details.participant_sequence')
            ->get();
            $bout_record = Bout::find($bout_id);
        }
        // dd($bout_record);

        $player_count = count($participants_records);
        $player_conf = Config::get('constants.competition.'.$player_count);

        // dd($player_conf);

        $fpdi = new FPDI;

        $filePath = 'competition/template/kata_bout_sheet.pdf';
        $outputFilePath = 'competition/tmp/'.'Kata_'.$compModel->id.'_'.$bout_record->bout_number.'.pdf';

        $count = $fpdi->setSourceFileWithParserParams($filePath);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($template);

            $fpdi->Image('logo.png', 10, 2, 32, 32);

            $fpdi->SetFont('helvetica', 'b', 20);
            // $fpdi->SetTextColor(153,0,153);

            $left = 60;
            $top = 15;
            $fpdi->Text($left, $top, $compModel->name);

            $left = 50;
            $top = 27;
            $fpdi->SetFont('helvetica', 'b', 17);
            // $text = "U7 - Male - WYO - Upto 20 Kg";
            $fpdi->Text($left, $top, 'Category: '.$bout_record->category);

            $left = 230;
            $top = 27;
            $fpdi->SetFont('helvetica', 'b', 17);
            $fpdi->Text($left, $top, 'Bout No: '.$bout_record->bout_number);

            $left = 230;
            $top = 35;
            $fpdi->SetFont('helvetica', 'b', 17);

            $fpdi->Text($left, $top, 'Tatami '.$bout_record->tatami.' - '.$bout_record->session);
            $competition_conf = Config::get('constants.kata_player_location');
            foreach ($participants_records as $key => $rec) {
                $this->print_player_text($fpdi, $competition_conf, $rec, $key);

                $left = 200;
                $top = 205;
                $fpdi->SetFont('helvetica', 'i', 10);

                $fpdi->Text($left, $top, 'Bout Generated on '.$this->dt->toDateTimeString().' by Kumite App');
            }
        }
        // $fpdi = $this->generate_back_page($fpdi, $bout_record, $compModel);
        return [$fpdi, $outputFilePath];
    }

    public function download_bout($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
        [$fpdi, $outputFilePath] = $this->generate_bout($decrypted_comp_id, $bout_id, $custom_bout_id);
        $fpdi->Output($outputFilePath, 'F');

        // Create a SplFileObject instance
        $fileObject = new SplFileObject($outputFilePath);

        // Get the MIME type of the file using Laravel's Storage facade
        $mimeType = Storage::mimeType($outputFilePath);

        // Read the contents of the file
        $fileContents = $fileObject->fread($fileObject->getSize());

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="'.'Kata_'.$decrypted_comp_id.'_'.$bout_id.'.pdf'.'"',
        ];

        $flag = FileHelper::delete_files($outputFilePath);

        // return response()->download($outputFilePath, "Kata_".$decrypted_comp_id."_". $bout_id.".pdf", $headers);
        return new Response($fileContents, 200, $headers);
        // $fpdi->Output('I', $outputFilePathNew, true);
    }

    public function generate_back_page($fpdi, $bout_record, $compModel)
    {
        $backPagefilePath = 'competition/template/kata_bout_sheet.pdf';

        $count = $fpdi->setSourceFile($backPagefilePath);

        for ($i = 1; $i <= $count; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($template);

            $fpdi->Image('logo.png', 10, 2, 32, 32);

            $fpdi->SetFont('helvetica', 'i', 13);
            $left = 150;
            $top = 5;
            $fpdi->Text($left, $top, 'Extra Sheet');

            $fpdi->SetFont('helvetica', 'b', 20);
            // $fpdi->SetTextColor(153,0,153);

            $left = 60;
            $top = 15;
            $fpdi->Text($left, $top, $compModel->name);

            $left = 50;
            $top = 27;
            $fpdi->SetFont('helvetica', 'b', 17);
            // $text = "U7 - Male - WYO - Upto 20 Kg";
            $fpdi->Text($left, $top, 'Category: '.$bout_record->category);

            $left = 230;
            $top = 27;
            $fpdi->SetFont('helvetica', 'b', 17);
            $fpdi->Text($left, $top, 'Bout No: '.$bout_record->bout_number);

            $left = 230;
            $top = 35;
            $fpdi->SetFont('helvetica', 'b', 17);

            $fpdi->Text($left, $top, 'Tatami '.$bout_record->tatami.' - '.$bout_record->session);

            $left = 200;
            $top = 205;
            $fpdi->SetFont('helvetica', 'i', 10);

            $fpdi->Text($left, $top, 'Bout Generated on '.$this->dt->toDateTimeString().' by Kumite App');
        }

        return $fpdi;
    }

    public function result_view($decrypted_comp_id, $bout_id, $custom_bout_id)
    {
    }

    public function print_player_text($fpdi, $competition_conf, $player_data, $key)
    {
        $left = $competition_conf['left'];
        $top = $competition_conf['right'] + ($competition_conf['space'] * $key);
        $font = $competition_conf['font'];
        $style = $competition_conf['style'];
        $fontsize = $competition_conf['fontsize'];

        $fpdi->SetFont($font, $style, $fontsize);
        $fpdi->Text($left, $top, $player_data->full_name);
        $fpdi->SetFont($font, $style, $fontsize - 4);
        // strtoupper
    }

    public function print_text($fpdi, $left, $top, $text, $fontsize)
    {
        $fpdi->SetFont('helvetica', 'b', $fontsize);
        $fpdi->Text($left, $top, $text);
    }

    public function create()
    {
        //
    }
}
