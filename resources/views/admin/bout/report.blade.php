<div class="tbodyDiv">
    <table id="resizeMe" class="table table-bordered table-striped text-center">
        <thead class="sticky-top bg-white">
            <tr>
                <th style="width: 400px;">Bouts</th>
                <th style="width: 450px;">Karate-Ka</th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="vertical-align: top">
                    <div class="overflow-auto" style="height: 800px;">
                    <ul class="list-group">
                        @forelse($bout_records as $key=>$rec)
                            @if (isset($rec->bouts_id))
                            <div class="card border" style="width: 100%;">
                                <div class="card-body">
                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center" 
                                        onclick="loadContentDetails(this, 'participants', 'karate_ka')"
                                        data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$rec->bouts_id.'/participants')}}"
                                        name="li_participants"
                                    >
                                        {{$rec->bouts_id}}->{{$rec->bouts_category}}
                                        <span class="badge badge-primary badge-pill">{{$rec->participant_count}}</span>
                                    
                                    </li>
                                </div>
                            </div>
                            @else
                            <div class="card border" style="width: 100%;">
                                <div class="card-body">
                                    <li class="list-group-item d-flex list-group-item-action justify-content-between align-items-center"
                                        onclick="loadContentDetails(this, 'participants', 'karate_ka')"
                                        data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/0/participants')}}"
                                        name="li_participants"
                                    >
                                        0->Bout not Assigned
                                        <span class="badge badge-primary badge-pill">{{$rec->participant_count}}</span>
                                    </li>
                                </div>
                            </div>
                            @endif
                        @empty
                            <p class="bg-danger text-white p-1">No Item data found</p>
                        @endforelse
                    </ul>
                    </div>
                </td>
                <td style="vertical-align: top">
                    <div class="overflow-auto"  style="height: 800px;" id="div_participants"></div>
                </td>
                <td style="vertical-align: top">
                    <div class="overflow-auto"  style="height: 800px;" id="div_karate_ka"></div>
                </td>
            </tr>
        </tbody>
    </table>
</div>