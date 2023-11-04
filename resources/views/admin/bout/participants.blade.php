<ul class="list-group">
  @forelse($participants_records as $key=>$rec)
      <li class="list-group-item list-group-item-action d-flex list-group-item-two-line"  onclick="loadContentDetails(this, 'karate_ka')"
      data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$rec->id.'/karate_ka')}}"
      name="li_karate_ka">
        <svg class="list-group-item-graphic" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" height="24" viewbox="0 0 24 24" width="24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z" /><path d="M0 0h24v24H0z" fill="none" /></svg>
        <span class="list-group-item-text">
          <span>{{$rec->full_name}}</span>
          <span>{{$rec->team}}</span>
        </span>
      </li>
  @empty
      <p class="bg-danger text-white p-1">No Item data found</p>
  @endforelse
</ul>
@if(count($participants_records) < 1)
    <div class="alert alert-warning">
        <strong>Sorry!</strong> No Product Found.
    </div>                                      
@else
<a class="nav-item nav-link active" target="_blank" href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$custom_bout_id.'/download_bout')}}">
  <button class="btn btn-icon btn-sm" type="button"><i class="material-icons">file_download</i></button>
</a>
<button class="btn btn-icon btn-sm" type="button"><i class="material-icons">output</i></button>
<button class="btn btn-icon btn-sm" type="button"><i class="material-icons">emoji_events</i></button>  
@endif

