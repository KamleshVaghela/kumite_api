<div class="tbodyDiv">
    <table id="resizeMe" class="table table-bordered table-striped text-center">
        <thead class="sticky-top bg-white">
            <tr>
                <th style="width: 450px;">Bouts</th>
                <th style="width: 450px;">Karate-Ka</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align: top">
                    <div class="overflow-auto" style="height: 800px;">
                        @if(count($bout_records) > 0)
                        <div class="d-flex justify-content-center">
                            <div class="d-inline p-4 text-white">
                                <a target="_blank"
                                    href="{{URL::to('admin/external_bout/board/'.$external_comp_id.'/bout/list/download_all_bout_kata')}}">
                                    <i class="material-icons" data-toggle="tooltip"
                                        title="Download Bout Pdf">file_download</i>
                                </a>
                            </div>
                        </div>
                        @endif
                        <ul class="list-group">
                            @forelse($bout_records as $key=>$rec)

                            <div class="card border" style="width: 100%;">
                                <div class="card-body">
                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center"
                                        onclick="loadContentDetails(this, 'participants', 'karate_ka')"
                                        data-href="{{URL::to('admin/external_bout/board/'.$external_comp_id.'/bout/list/'.$rec->id.'/participants_kata')}}"
                                        name="li_participants" id="li_participants_{{$external_comp_id}}_{{$rec->id}}">
                                        {{$rec->bout_number}}-{{$rec->category}}
                                        <span class="badge badge-primary badge-pill">{{$rec->participant_count}}</span>
                                    </li>
                                </div>
                            </div>
                            @empty
                            <p class="bg-danger text-white p-1">No Item data found</p>
                            @endforelse
                        </ul>
                    </div>
                </td>
                <td style="vertical-align: top">
                    <div class="overflow-auto" style="height: 800px;" id="div_participants"></div>
                </td>
                <td style="vertical-align: top">
                    <div class="overflow-auto" style="height: 800px;" id="div_karate_ka"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>