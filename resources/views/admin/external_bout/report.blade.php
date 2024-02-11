<div id="admission_report_accordion">
    @forelse($external_competition as $key=>$rec)
    <div class="card" style="min-width: 100%;">
        <div class="card-header" id="heading{{$rec->id}}">
            <div class="container">
                <div class="row">
                    <figure data-toggle="collapse" data-target="#collapse{{$rec->id}}" aria-expanded="false"
                        aria-controls="collapse{{$rec->id}}">
                        <blockquote class="blockquote">
                            <p>{{$rec->name}} {{$rec->id}}</p>
                        </blockquote>
                        <figcaption class="blockquote-footer"> - </figcaption>
                    </figure>
                    <a class="nav-item nav-link" target="_blank"
                        href="{{URL::to('admin/external_bout/board/'.$rec->id)}}">View </a>
                </div>
            </div>
        </div>
        <div id="collapse{{$rec->id}}" class="collapse" aria-labelledby="heading{{$rec->id}}"
            data-parent="#admission_report_accordion">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td style="width: 25%">Unique Id</td>
                            <td>{{$rec->id}}</td>
                        </tr>
                        <tr>
                            <td>Competition Name</td>
                            <td>{{$rec->name}}</td>
                        </tr>
                        <tr>
                            <td>Short Description</td>
                            <td>{{$rec->short_description}}</td>
                        </tr>
                        <tr>
                            <td>Additional Details</td>
                            <td>{{$rec->additional_details}}</td>
                        </tr>
                        <tr>
                            <td>Start Date</td>
                            <td>{{$rec->start_date}}</td>
                        </tr>
                        <tr>
                            <td>End Date</td>
                            <td>{{$rec->end_date}}</td>
                        </tr>
                        <tr>
                            <td>Edit</td>
                            <td><a class="dropdown-item" href="#"
                                    data-href="{{URL::to('admin/competition/edit/'.$rec->id)}}"
                                    onclick="loadActivityData(this)"><span class="material-icons">edit</span></a></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @empty
    <p class="bg-danger text-white p-1">No Item data found</p>
    @endforelse
</div>