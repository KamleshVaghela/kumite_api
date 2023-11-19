@if(count($participants_records) > 0 and ($bout_id !=0 or $custom_bout_id !=0 ) )
<div class="d-flex justify-content-center">
  <div class="d-inline p-4 text-white">
    <a target="_blank" href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$custom_bout_id.'/download_bout')}}">
      <i class="material-icons" data-toggle="tooltip" title="Download Bout Pdf">file_download</i>
    </a>
  </div>
  <div class="modal fade" id="result_viewModal" tabindex="-1"></div>
</div>
@endif
<ul class="list-group">
  @forelse($participants_records as $key=>$rec)
      <li class="list-group-item list-group-item-action d-flex list-group-item-two-line" >
        <!-- <svg class="list-group-item-graphic" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" height="24" viewbox="0 0 24 24" width="24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" />
          <path d="M0 0h24v24H0z" fill="none" />
        </svg> -->
        <span class="chip">{{$rec->participant_sequence}}</span>
        <span class="list-group-item-text" onclick="loadContentDetails(this, 'karate_ka')"
          data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$custom_bout_id.'/'.$rec->id.'/karate_ka')}}"
          data-details_key="result_details" 
          name="li_karate_ka">
          <span>{{$rec->full_name}}</span>
          <span>{{$rec->team}}</span>
        </span>
        <span>
            @if( isset($boutObj->first) and $boutObj->first == $rec->id)
              Gold
            @elseif( isset($boutObj->second) and $boutObj->second == $rec->id)
              Silver
            @elseif( isset($boutObj->third_1) and $boutObj->third_1 == $rec->id)
              Bronze-1
            @elseif( isset($boutObj->third_2) and $boutObj->third_2 == $rec->id)
              Bronze-2  
            @else
            <a class="btn btn-icon ml-auto" href="#">
              <span class="material-icons"  onclick="loadContentDetails(this, 'karate_ka')"
              data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$custom_bout_id.'/'.$rec->id.'/change_bout')}}"
              data-details_key="change_bout_details" 
              name="li_change_bout">send</span>
            </a>
            @endif
        </span>
      </li>
  @empty
      <p class="bg-danger text-white p-1">No Item data found</p>
  @endforelse
</ul>

