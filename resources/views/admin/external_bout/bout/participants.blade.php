@if(count($participants_records) > 0)
<div class="d-flex justify-content-center">
    <div class="d-inline p-4 text-white">
        <a target="_blank"
            href="{{URL::to('admin/external_bout/board/'.$external_comp_id.'/bout/list/'.$boutObj->id.'/download_bout')}}">
            <i class="material-icons" data-toggle="tooltip" title="Download Bout Pdf">file_download</i>
        </a>
    </div>
    <div class="modal fade" id="result_viewModal" tabindex="-1"></div>
</div>
@endif
<ul class="list-group">
    @forelse($participants_records as $key=>$rec)
    <li class="list-group-item list-group-item-action d-flex list-group-item-two-line">
        <span class="chip">{{$rec->participant_sequence}}</span>
        <span class="list-group-item-text" onclick="loadContentDetails(this, 'karate_ka')"
            data-href="{{URL::to('admin/external_bout/board/'.$external_comp_id.'/bout/list/'.$rec->id.'/karate_ka/'.$rec->participants_id)}}"
            data-details_key="result_details" name="li_karate_ka">
            <span>{{$rec->full_name}}</span>
            <span>{{$rec->team}}</span>
        </span>
    </li>
    @empty
    <p class="bg-danger text-white p-1">No Item data found</p>
    @endforelse
</ul>