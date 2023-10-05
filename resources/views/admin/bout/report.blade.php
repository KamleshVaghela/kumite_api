<div id="admission_report_accordion" >
    @forelse($competition as $key=>$rec)
    <div class="card" style="min-width: 100%;">
        <div class="card-header" id="heading{{$rec->COMP_ID}}">
            <div class="container">
                <div class="row">
                    <figure data-toggle="collapse" data-target="#collapse{{$rec->COMP_ID}}" aria-expanded="false" aria-controls="collapse{{$rec->COMP_ID}}">
                        <blockquote class="blockquote">
                          <p>{{$rec->COMP_NAME}}</p>
                        </blockquote>
                        <figcaption class="blockquote-footer">{{$rec->TOTAL}}</figcaption>
                    </figure>
                    <a class="nav-item nav-link" href="{{URL::to('admin/competition/board/'.encrypt_val($rec->COMP_ID))}}">View </a>
                </div>
            </div>
        </div>
        <div id="collapse{{$rec->COMP_ID}}" class="collapse" aria-labelledby="heading{{$rec->COMP_ID}}" data-parent="#admission_report_accordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr><td style="width: 25%">Unique Id</td><td>{{$rec->COMP_ID}}</td></tr>
                        {{-- <tr>
                            <td>Edit</td>
                            <td><a class="dropdown-item" href="#" data-href="{{URL::to('admin/competition/edit/'.$rec->COMP_ID)}}" onclick="loadActivityData(this)" ><span class="material-icons">edit</span></a></td>
                        </tr> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
    @empty
        <p class="bg-danger text-white p-1">No Item data found</p>
    @endforelse
</div>
