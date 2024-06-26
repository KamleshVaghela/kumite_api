@switch($view_type)
@case('index')
<div class="container">
    <nav class="nav nav-pills flex-column flex-sm-row">
        <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
            data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID).'/bout/results/report/coach'}}"
            data-details_key="result_coach_details">
            <button class="btn btn-outline-danger" type="button">Coach Wise Results</button>
        </a>

        <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
            data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID).'/bout/results/report/team'}}"
            data-details_key="result_team_details">
            <button class="btn btn-outline-danger" type="button">Team Wise Results</button>
        </a>

        <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
            data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID).'/bout/results/report/download'}}"
            data-details_key="result_download_details">
            <button class="btn btn-outline-danger" type="button">Downloads</button>
        </a>
    </nav>
</div>
<div class="modal fade" id="result_coach_detailsModal" tabindex="-1"></div>
<div class="modal fade" id="result_team_detailsModal" tabindex="-1"></div>
<div class="modal fade" id="result_download_detailsModal" tabindex="-1"></div>
@break

@case('coach')
<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="min-width: 800px">
        <div class="modal-header">
            <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">Result Details</h5>
        </div>

        <div id="div_ajax_{{$details_key}}"></div>
        <div class="modal-body">
            <table class="table table-striped">
                <thead>
                    <tr class="table-primary">
                        <th scope="row"></th>
                        <th scope="row" colspan="3">Kumite</th>
                        <th scope="row" colspan="3">Kata</th>
                        <th scope="row">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-primary">
                        <th scope="row">Coach</th>
                        <th scope="row">Gold</th>
                        <th scope="row">Silver</th>
                        <th scope="row">Bronze</th>
                        <th scope="row">Gold</th>
                        <th scope="row">Silver</th>
                        <th scope="row">Bronze</th>
                        <th scope="row">Gold|Silver|Bronze</th>
                    </tr>
                    @forelse($result_data as $key=>$rec)
                    <tr>
                        <td scope="row">{{$rec->external_coach_name}}</td>
                        <td scope="row"><kbd>{{$rec->total_gold}}</kbd>({{ ($rec->total_gold) * 5}})</td>
                        <td scope="row"><kbd>{{$rec->total_silver}}</kbd>({{ ($rec->total_silver) * 3 }})</td>
                        <td scope="row"><kbd>{{$rec->total_bronze_1 + $rec->total_bronze_2}}</kbd>
                            ({{$rec->total_bronze_1 + $rec->total_bronze_2}})</td>

                        <td scope="row"><kbd>{{$rec->total_gold_kata}}</kbd>({{ ($rec->total_gold_kata) * 5}})</td>
                        <td scope="row"><kbd>{{$rec->total_silver_kata}}</kbd>({{ ($rec->total_silver_kata) * 3 }})</td>
                        <td scope="row"><kbd>{{$rec->total_bronze_1_kata + $rec->total_bronze_2_kata}}</kbd>
                            ({{$rec->total_bronze_1_kata + $rec->total_bronze_2_kata}})</td>
                        <td scope="row">
                            <kbd>{{$rec->total_gold + $rec->total_gold_kata}}</kbd>
                            <kbd>{{$rec->total_silver + $rec->total_silver_kata}}</kbd>
                            <kbd>{{$rec->total_bronze_1 + $rec->total_bronze_2 + $rec->total_bronze_1_kata + $rec->total_bronze_2_kata }}</kbd>
                            {{ (($rec->total_gold) * 5) + ( ($rec->total_silver) * 3 ) + ($rec->total_bronze_1 + $rec->total_bronze_2)
                            + (($rec->total_gold_kata) * 5) + ( ($rec->total_silver_kata) * 3 ) + ($rec->total_bronze_1_kata + $rec->total_bronze_2_kata) }}
                        </td>
                    </tr>
                    @empty
                    <p class="bg-danger text-white p-1">No Item data found</p>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
        </div>
    </div>
</div>
@break

@case('team')
<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="min-width: 800px">
        <div class="modal-header">
            <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">Result Details</h5>
        </div>

        <div id="div_ajax_{{$details_key}}"></div>
        <div class="modal-body">
            <table class="table table-striped">
                <thead>
                    <tr class="table-primary">
                        <th scope="row"></th>
                        <th scope="row" colspan="3">Kumite</th>
                        <th scope="row" colspan="3">Kata</th>
                        <th scope="row">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-primary">
                        <th scope="row">Coach</th>
                        <th scope="row">Gold</th>
                        <th scope="row">Silver</th>
                        <th scope="row">Bronze</th>
                        <th scope="row">Gold</th>
                        <th scope="row">Silver</th>
                        <th scope="row">Bronze</th>
                        <th scope="row">Gold|Silver|Bronze</th>
                    </tr>
                    @forelse($result_data as $key=>$rec)
                    <tr>
                        <td scope="row">{{$rec->team}}</td>
                        <td scope="row"><kbd>{{$rec->total_gold}}</kbd> ({{ ($rec->total_gold) * 5}})</td>
                        <td scope="row"><kbd>{{$rec->total_silver}}</kbd> ({{ ($rec->total_silver) * 3 }})</td>
                        <td scope="row"><kbd>{{$rec->total_bronze_1 + $rec->total_bronze_2}}</kbd>
                            ({{$rec->total_bronze_1 + $rec->total_bronze_2}})</td>
                        <td scope="row"><kbd>{{$rec->total_gold_kata}}</kbd>({{ ($rec->total_gold_kata) * 5}})</td>
                        <td scope="row"><kbd>{{$rec->total_silver_kata}}</kbd>({{ ($rec->total_silver_kata) * 3 }})</td>
                        <td scope="row"><kbd>{{$rec->total_bronze_1_kata + $rec->total_bronze_2_kata}}</kbd>
                            ({{$rec->total_bronze_1_kata + $rec->total_bronze_2_kata}})</td>
                        <td scope="row">
                            <kbd>{{$rec->total_gold + $rec->total_gold_kata}}</kbd>
                            <kbd>{{$rec->total_silver + $rec->total_silver_kata}}</kbd>
                            <kbd>{{$rec->total_bronze_1 + $rec->total_bronze_2 + $rec->total_bronze_1_kata + $rec->total_bronze_2_kata }}</kbd>
                            {{ (($rec->total_gold) * 5) + ( ($rec->total_silver) * 3 ) + ($rec->total_bronze_1 + $rec->total_bronze_2) 
                            + (($rec->total_gold_kata) * 5) + ( ($rec->total_silver_kata) * 3 ) + ($rec->total_bronze_1_kata + $rec->total_bronze_2_kata) }}
                        </td>
                    </tr>
                    @empty
                    <p class="bg-danger text-white p-1">No Item data found</p>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
        </div>
    </div>
</div>
@break

@case('download')
<div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content" style="min-width: 700px">
        <div class="modal-header">
            <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">Coach Wise Download Result</h5>
        </div>
        <div id="div_ajax_{{$details_key}}"></div>
        <div class="modal-body">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="download_type" id="download_results" value="result">
                <label class="form-check-label" for="download_results">Results</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="download_type" checked id="download_cards"
                    value="cards">
                <label class="form-check-label" for="download_cards">Cards</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="download_type" id="download_certificate"
                    value="certificate">
                <label class="form-check-label" for="download_certificate">Certificate</label>
            </div>

            <div class="form-group form-ripple position-relative">
                <label for="coach">Coach</label>
                <select id="external_coach_code" name="external_coach_code" class="form-control select2"
                    style="width: 100%;">
                    @foreach($coach_list as $key=>$rec)
                    <option value="{{$rec->external_coach_code}}">{{$rec->external_coach_name}}</option>
                    @endforeach
                </select>
                <small id="coach_error" class="form-text post_error"></small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Decline</button>
            <button type="button" class="btn btn-primary"
                data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout/results/report/download/"}}"
                onclick="downloadFile(this)">Download</button>
        </div>
    </div>
</div>
@break

@default
<span class="status">Reload</span>
@endswitch