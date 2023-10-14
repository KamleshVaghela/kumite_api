<ul class="list-group">
  @forelse($categories as $key=>$rec)
      <li class="list-group-item list-group-item-action d-flex list-group-item-two-line"  onclick="loadContentDetails(this, 'category')"
      data-href="{{URL::to('admin/default_category/'.$rec->id.'/category')}}"
      name="li_category">
        <blockquote class="blockquote">
          <p class="mb-0">{{$rec->category}}</p>
          <footer class="blockquote-footer">{{$rec->gender}}</footer>
        </blockquote>
      </li>
  @empty
      <p class="bg-danger text-white p-1">No Item data found</p>
  @endforelse
</ul>
