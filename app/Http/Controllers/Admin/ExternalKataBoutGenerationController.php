<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExternalCompetition;
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KataExternalCompDataExport;
use App\Imports\KataExternalCompDataImport;
use App\Models\KataExternalBoutTempExcel;
use App\Models\KataExternalBouts;
use App\Models\KataExternalBoutParticipantDetail;
use Config;
use setasign\Fpdi\Fpdi;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileHelper;
use SplFileObject;
use Illuminate\Http\Response;

class ExternalKataBoutGenerationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    private  $dt;

    public function __construct()
    {
        $this->dt = Carbon::now();
    }
    
    // public function index()
    // {
    //     return View('admin.external_bout.index');
    // }

    // public function report(Request $request)
    // {
    //     $external_competition = ExternalCompetition::all();

    //     return View('admin.external_bout.report',compact('external_competition'));
    // }

    // /**
    //  * Show the form for creating a new resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     return View('admin.external_bout.create');
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required',
    //     ]);
    //     $dataObj = new ExternalCompetition();
    //     $dataObj->short_description = $request->short_description;
    //     $dataObj->name = $request->name;
    //     $dataObj->additional_details = $request->additional_details;

    //     $dataObj->start_date = $request->start_date;
    //     $dataObj->end_date = $request->end_date;
    //     $dataObj->user_id =  Auth::user()->id;

    //     $dataObj->last_modified = \Carbon\Carbon::now();
    //     $dataObj->last_modified_user_id = Auth::user()->id;

    //     $dataObj->save();

    //     return response([
    //         'data' => $dataObj,
    //         'message' => 'ExternalCompetition Inserted successfully',
    //         'alert-type' => 'success'
    //     ], 200);
    // }

    
    // public function boardIndex($external_comp_id)
    // {
    //     $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

    //     return View('admin.external_bout.board.index')
    //     ->with('external_comp_id',$external_comp_id)
    //     ->with('external_competition',$external_competition);
    // }

    // public function boardReport(Request $request, $external_comp_id)
    // {
    //     $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

    //     return View('admin.external_bout.board.report')
    //     ->with('external_comp_id',$external_comp_id)
    //     ->with('external_competition',$external_competition);
    // }


    public function exportExcel($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        return Excel::download(new KataExternalCompDataExport($external_competition->id), 'Kata_ExternalCompetition_'.$external_competition->id.'.xlsx');
    }

    public function importExcel($external_comp_id, Request $request)
    {
        $details_key = $request->query('details_key');

        return View('admin.kata_external_bout.board.import_excel',compact('details_key'))
        ->with('external_comp_id',$external_comp_id);
    }

    public function postImportExcel($external_comp_id, Request $request) {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $data = KataExternalBoutTempExcel::where('external_competition_id',$external_comp_id)->delete();
        $bouts = KataExternalBouts::where('external_competition_id',$external_comp_id)->delete();
        $boutParticipantDetails = KataExternalBoutParticipantDetail::where('external_competition_id',$external_comp_id)->delete();

        Excel::import(new KataExternalCompDataImport($external_competition->id), 
                      $request->file('file')->store('files'));

        $excelRecords = KataExternalBoutTempExcel::where('external_competition_id',$external_comp_id)
        ->orderBy('id')
        // ->orderBy('category')
        ->get();

        $participant_cnt = 1;

        foreach($excelRecords as $records) {
            $boutData = KataExternalBouts::where('external_competition_id', $external_comp_id)
                ->where('gender',$records->gender)
                ->where('category',$records->category)
                ->first();
            
            if($boutData) {
                $participant_cnt = $participant_cnt + 1;

            } else {
                $boutData = new KataExternalBouts();
                $boutData->external_competition_id = $external_comp_id;
                $boutData->gender = $records->gender;
                $boutData->category = $records->category;
                $boutData->age_category = $records->age_category;
                $boutData->weight_category = $records->weight_category;
                $boutData->rank_category = $records->rank_category;
                $boutData->tatami = $records->tatami;
                $boutData->session = $records->session;
                $boutData->bout_number= $records->bout_number;
                $boutData->user_id= Auth::user()->id;
                $boutData->save();
                $participant_cnt = 1;
            }

            $compBoutParticipantDetail = new KataExternalBoutParticipantDetail();
            $compBoutParticipantDetail->external_competition_id = $external_comp_id;
            $compBoutParticipantDetail->bout_id = $boutData->id;
            $compBoutParticipantDetail->full_name = $records->full_name;
            $compBoutParticipantDetail->gender = $records->gender;
            $compBoutParticipantDetail->team = $records->team;
            $compBoutParticipantDetail->coach_name = $records->coach_name;
            $compBoutParticipantDetail->rank = $records->rank;
            $compBoutParticipantDetail->age = $records->age;
            $compBoutParticipantDetail->weight = $records->weight;
            $compBoutParticipantDetail->participant_sequence = $participant_cnt;
            $compBoutParticipantDetail->user_id = Auth::user()->id;
            $compBoutParticipantDetail->save();
        }
        return response([
            'data' => '',
            'message' => 'Excel Imported successfully',
            'alert-type' => 'success'
        ], 200);
    }

    public function data_table($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        
        return View('admin.kata_external_bout.data_table',compact('external_comp_id'))
        ->with('external_competition',$external_competition);
    }

    public function data_table_report($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $participants_records = DB::table("kata_external_participants")    
        ->where('kata_external_participants.external_competition_id',$external_comp_id)
        ->leftJoin("kata_external_bouts", function($join) {
            $join->on("kata_external_bouts.id", "=", "kata_external_participants.bout_id");
        })
        ->select(
            "kata_external_participants.id",
            "kata_external_participants.full_name",
            "kata_external_participants.gender",
            "kata_external_participants.team",
            "kata_external_participants.coach_name",
            "kata_external_participants.rank",
            "kata_external_participants.age",
            "kata_external_participants.weight",
            "kata_external_bouts.category",
            "kata_external_bouts.age_category",
            "kata_external_bouts.weight_category",
            "kata_external_bouts.rank_category",
            "kata_external_bouts.tatami",
            "kata_external_bouts.session",
            "kata_external_bouts.bout_number",
            )
        ->orderBy('kata_external_bouts.id')
        ->get();

        return View('admin.kata_external_bout.data_table_report',compact('external_comp_id'))
        ->with('participants_records',$participants_records);
    }

    public function board_list_index($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        
        return View('admin.kata_external_bout.bout.index',compact('external_comp_id'))
        ->with('external_competition',$external_competition);
    }

    public function board_list_report($external_comp_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $bout_records = DB::table("kata_external_participants")    
        ->where('kata_external_participants.external_competition_id',$external_comp_id)
        ->join("kata_external_bouts", function($join) {
            $join->on("kata_external_bouts.id", "=", "kata_external_participants.bout_id");
        })
        ->select("kata_external_bouts.id", "kata_external_bouts.category", "kata_external_bouts.gender" ,"kata_external_bouts.bout_number",
            DB::raw('count(*) as participant_count') 
        )
        ->groupBy('kata_external_bouts.id')
        ->groupBy('kata_external_bouts.category')
        ->groupBy('kata_external_bouts.gender')
        ->groupBy('kata_external_bouts.bout_number')
        ->orderBy('kata_external_bouts.id')
        ->get();
        
        return View('admin.kata_external_bout.bout.report',compact('external_comp_id'))
        ->with('bout_records',$bout_records);
    }

    public function board_list_index_participants($external_comp_id, $bout_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $participants_records = DB::table("kata_external_participants")    
        ->where('kata_external_participants.external_competition_id',$external_comp_id)
        ->where('kata_external_participants.bout_id',$bout_id)
        ->join("kata_external_bouts", function($join) {
            $join->on("kata_external_bouts.id", "=", "kata_external_participants.bout_id");
        })
        ->select(
            "kata_external_participants.id",
            "kata_external_participants.full_name",
            "kata_external_participants.gender",
            "kata_external_participants.team",
            "kata_external_participants.coach_name",
            "kata_external_participants.rank",
            "kata_external_participants.age",
            "kata_external_participants.weight",
            "kata_external_bouts.category",
            "kata_external_bouts.age_category",
            "kata_external_bouts.weight_category",
            "kata_external_bouts.rank_category",
            "kata_external_bouts.tatami",
            "kata_external_bouts.session",
            "kata_external_bouts.bout_number",
            "kata_external_participants.participant_sequence",
            "kata_external_participants.id as participants_id"
        )
        ->orderBy('kata_external_participants.participant_sequence')
        ->get();
        $boutObj = KataExternalBouts::find($bout_id);
            
        // dd($participants_records);
        return View('admin.kata_external_bout.bout.participants',compact('external_comp_id'))
        ->with('participants_records',$participants_records)
        ->with('boutObj',$boutObj);
    }

    public function board_list_index_karate_ka($external_comp_id, $bout_id, $participant_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();
        $boutObj = KataExternalBouts::find($bout_id);

        $participants = DB::table("kata_external_participants")    
        ->where('kata_external_participants.external_competition_id',$external_comp_id)
        ->where('kata_external_participants.id',$participant_id)
        ->select("kata_external_participants.*")
        ->first();

        // dd($participants);
        
        return View('admin.kata_external_bout.bout.karate_ka',compact('external_comp_id'))
        ->with('participants',$participants)
        ->with('bout_id',$bout_id) 
        ->with('details_key','result_details')
        ->with('boutObj',$boutObj);
    }

    public function print_player_text($fpdi, $competition_conf, $player_data, $key) {
        $left = $competition_conf['left'];
        $top = $competition_conf['right'] + ($competition_conf['space'] * $key); 
        $font = $competition_conf['font']; 
        $style = $competition_conf['style'];
        $fontsize = $competition_conf['fontsize']; 
        
        $fpdi->SetFont($font, $style, $fontsize);
        $fpdi->Text($left,$top,$player_data->full_name);
        $fpdi->SetFont($font, $style, $fontsize-4);
    }


    public function board_list_download_all_bout($external_comp_id) 
    {
        $bout_records = KataExternalBouts::where('external_competition_id',$external_comp_id)
        ->orderBy('id')
        ->get();

        $outputFileList=array();
        
        foreach($bout_records as $key=>$rec) {
            list($fpdi, $outputFilePath) = $this->generate_bout($external_comp_id, $rec->id);
            $fpdi->Output($outputFilePath, 'F');
            array_push($outputFileList,$outputFilePath);
        }

        // dd($outputFileList);
        $pageCount = 0;
        // initiate FPDI
        $pdf = new FPDI();

        // iterate through the files
        foreach ($outputFileList AS $file) {
            // get the page count
            $pageCount = $pdf->setSourceFile($file);
            // iterate through all pages
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                // import a page
                $templateId = $pdf->importPage($pageNo);
                // get the size of the imported page
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

                // use the imported page
                $pdf->useTemplate($templateId);
            }
        }
        $pdf->Output('D', "Kata_External_".$external_comp_id.".pdf", 'F');
        foreach ($outputFileList AS $file) {
            $flag = FileHelper::delete_files($file);
        }
    }

    public function generate_bout($external_comp_id, $bout_id)
    {
        $external_competition = ExternalCompetition::where('id',$external_comp_id)->first();

        $participants_records = DB::table("kata_external_participants")    
        ->where('kata_external_participants.external_competition_id',$external_comp_id)
        ->where('kata_external_participants.bout_id',$bout_id)
        ->join("kata_external_bouts", function($join) {
            $join->on("kata_external_bouts.id", "=", "kata_external_participants.bout_id");
        })
        ->select("kata_external_participants.*")
        ->orderBy('kata_external_participants.participant_sequence')
        ->get();
        $bout_record = KataExternalBouts::find($bout_id);

        // $player_count = count($participants_records);
        // $player_conf = Config::get('constants.competition.'.$player_count);

        // dd($player_conf);

        $fpdi = new FPDI;
        
        $filePath = "competition/template/kata_bout_sheet.pdf";
        $outputFilePath = "competition/tmp/".$external_comp_id."_". $bout_record->bout_number.".pdf";
        
        $count = $fpdi->setSourceFileWithParserParams($filePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);
              
            $fpdi->Image("logo.png",10,2, 32, 32);

            $fpdi->SetFont("helvetica", "b", 20);
            // $fpdi->SetTextColor(153,0,153);
  
            $left = 60;
            $top = 15;
            $fpdi->Text($left,$top,$external_competition->name);

            $left = 50;
            $top = 27;
            $fpdi->SetFont("helvetica", "b", 17);
            // $text = "U7 - Male - WYO - Upto 20 Kg";
            $fpdi->Text($left,$top,"Category: ".$bout_record->category);

            $left = 230;
            $top = 27;
            $fpdi->SetFont("helvetica", "b", 17);
            $fpdi->Text($left,$top,"Bout No: ".$bout_record->bout_number);
  
            $left = 230;
            $top = 35;
            $fpdi->SetFont("helvetica", "b", 17);
           
            $fpdi->Text($left,$top, 'Tatami '. $bout_record->tatami.' - '.$bout_record->session);
            $competition_conf = Config::get('constants.kata_player_location');

            foreach($participants_records as $key=>$rec) {
                $this->print_player_text($fpdi, $competition_conf, $rec, $key);
                
                $left = 200;
                $top = 205;
                $fpdi->SetFont("helvetica", "i", 10);
    
                $fpdi->Text($left,$top,"Bout Generated on ".$this->dt->toDateTimeString()." by Kumite App");
            }
        }
        // $fpdi = $this->generate_back_page($fpdi, $bout_record, $external_competition);
        return array( $fpdi, $outputFilePath);
    }

    public function board_list_download_bout($external_comp_id, $bout_id)
    {
        list($fpdi, $outputFilePath) = $this->generate_bout($external_comp_id, $bout_id);
        $fpdi->Output( $outputFilePath, 'F');

        // Create a SplFileObject instance
        $fileObject = new SplFileObject($outputFilePath);

        // Get the MIME type of the file using Laravel's Storage facade
        $mimeType = Storage::mimeType($outputFilePath);

        // Read the contents of the file
        $fileContents = $fileObject->fread($fileObject->getSize());

        $headers = [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'attachment; filename="' . "Kata_External_".$external_comp_id."_". $bout_id.".pdf" . '"',
        ];

        $flag = FileHelper::delete_files($outputFilePath);

        // return response()->download($outputFilePath, "Kata_External_".$external_comp_id."_". $bout_id.".pdf", $headers);
        return new Response($fileContents, 200, $headers);
    }

    public function generate_back_page($fpdi, $bout_record, $external_competition)
    {
        $backPagefilePath = "competition/template/kata_bout_sheet.pdf";

        $count = $fpdi->setSourceFile($backPagefilePath);
  
        for ($i=1; $i<=$count; $i++) {
  
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], array($size['width'], $size['height']));
            $fpdi->useTemplate($template);

            $fpdi->Image("logo.png",10,2, 32, 32);

            $fpdi->SetFont("helvetica", "i", 13);
            $left = 150;
            $top = 5;
            $fpdi->Text($left,$top,"Extra Sheet");

            $fpdi->SetFont("helvetica", "b", 20);
            // $fpdi->SetTextColor(153,0,153);
  
            $left = 60;
            $top = 15;
            $fpdi->Text($left,$top,$external_competition->name);

            $left = 50;
            $top = 27;
            $fpdi->SetFont("helvetica", "b", 17);
            // $text = "U7 - Male - WYO - Upto 20 Kg";
            $fpdi->Text($left,$top,"Category: ".$bout_record->category);

            $left = 230;
            $top = 27;
            $fpdi->SetFont("helvetica", "b", 17);
            $fpdi->Text($left,$top,"Bout No: ".$bout_record->bout_number);
  
            $left = 230;
            $top = 35;
            $fpdi->SetFont("helvetica", "b", 17);
           
            $fpdi->Text($left,$top, 'Tatami '. $bout_record->tatami.' - '.$bout_record->session);

            $left = 200;
            $top = 205;
            $fpdi->SetFont("helvetica", "i", 10);

            $fpdi->Text($left,$top,"Bout Generated on ".$this->dt->toDateTimeString()." by Kumite App");
        }
        return $fpdi;
    }
}