@switch($view_type)
    @case('index')
        <div class="container">
            <nav class="nav nav-pills flex-column flex-sm-row">
            <a class="flex-sm-fill text-sm-center nav-link active"  href="#"
                onclick="loadDetails(this)" 
                data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout/results/report/coach"}}" 
                data-details_key="result_coach_details"
            >
                <button class="btn btn-outline-danger" type="button">Coach Wise</button>
            </a>

            <a class="flex-sm-fill text-sm-center nav-link active"  href="#"
                onclick="loadDetails(this)" 
                data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout/results/report/team"}}" 
                data-details_key="result_team_details"
            >
                <button class="btn btn-outline-danger" type="button">Team Wise</button>
            </a>
            </nav>
        </div>
        <div class="modal fade" id="result_coach_detailsModal" tabindex="-1"></div>
        <div class="modal fade" id="result_team_detailsModal" tabindex="-1"></div>
        @break

    @case('coach')
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content" style="min-width: 700px">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">Result Details</h5>
            </div>

            <div id="div_ajax_{{$details_key}}"></div>
            <div class="modal-body">
            <table class="table table-striped">
                <tbody>
                    <tr class="table-primary">
                        <th scope="row">Coach</th>
                        <th scope="row">Gold</th>
                        <th scope="row">Silver</th>
                        <th scope="row">Bronze</th>
                        <th scope="row">Total Point(s)</th>
                    </tr>
                @forelse($result_data as $key=>$rec)
                    <tr>
                        <td scope="row">{{$rec->external_coach_name}}</td>
                        <td scope="row">{{$rec->total_gold}} ({{ ($rec->total_gold) * 3}})</td>
                        <td scope="row">{{$rec->total_silver}} ({{ ($rec->total_silver) * 2 }})</td>
                        <td scope="row">{{$rec->total_bronze_1 + $rec->total_bronze_2}} ({{$rec->total_bronze_1 + $rec->total_bronze_2}})</td>
                        <td scope="row"> {{ (($rec->total_gold) * 3) + ( ($rec->total_silver) * 2 ) + ($rec->total_bronze_1 + $rec->total_bronze_2) }}</td>
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
            <div class="modal-content" style="min-width: 700px">
                <div class="modal-header">
                    <h5 class="modal-title" id="{{$details_key}}ModalScrollableTitle">Result Details</h5>
            </div>

            <div id="div_ajax_{{$details_key}}"></div>
            <div class="modal-body">
            <table class="table table-striped">
                <tbody>
                    <tr class="table-primary">
                        <th scope="row">Coach</th>
                        <th scope="row">Gold</th>
                        <th scope="row">Silver</th>
                        <th scope="row">Bronze</th>
                        <th scope="row">Total Point(s)</th>
                    </tr>
                @forelse($result_data as $key=>$rec)
                    <tr>
                        <td scope="row">{{$rec->team}}</td>
                        <td scope="row">{{$rec->total_gold}} ({{ ($rec->total_gold) * 3}})</td>
                        <td scope="row">{{$rec->total_silver}} ({{ ($rec->total_silver) * 2 }})</td>
                        <td scope="row">{{$rec->total_bronze_1 + $rec->total_bronze_2}} ({{$rec->total_bronze_1 + $rec->total_bronze_2}})</td>
                        <td scope="row"> {{ (($rec->total_gold) * 3) + ( ($rec->total_silver) * 2 ) + ($rec->total_bronze_1 + $rec->total_bronze_2) }}</td>
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

    @default
        <span class="status">Reload</span>
@endswitch
