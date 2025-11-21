<div class="card shadow p-3 mb-5 bg-white rounded" style="min-width: 90%;">
    <table class="table table-striped">
        <thead>
            <tr class="table-primary">
                <th scope="col">#</th>
                <th scope="col">Description</th>
                <th scope="col">Value</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($boutObj))
            <tr>
                <th scope="row">11</th>
                <td>Medal</td>
                <td>
                    <div id="div_ajax_{{$details_key}}">
                    </div>
                    <div class="modal-body">
                        <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}"
                            method="POST">
                            @csrf
                            <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Result</label>
                            <select class="custom-select mr-sm-2" id="result" name="result">
                                <option selected value="0">None</option>
                                <option value="1" @if(isset($boutObj->first) and $boutObj->first == $participants->id)
                                    selected
                                    @endif
                                    >Gold</option>
                                <option value="2" @if(isset($boutObj->second) and $boutObj->second == $participants->id)
                                    selected
                                    @endif
                                    >Silver</option>
                                <option value="3" @if(isset($boutObj->third_1) and $boutObj->third_1 ==
                                    $participants->id)
                                    selected
                                    @endif
                                    >Bronze-1</option>
                                <option value="4" @if(isset($boutObj->third_2) and $boutObj->third_2 ==
                                    $participants->id)
                                    selected
                                    @endif
                                    >Bronze-2</option>
                                @if(Auth::user()->email == '1kamlesh2410@gmail.com')
                                    <option value="5"
                                    @if(isset($boutObj->third_3) and $boutObj->third_3 == $participants->id)
                                        selected
                                        @endif
                                    >Bronze-3</option>
                                    @endif
                            </select>
                            <input type="hidden" id="boutKey"
                                value="li_participants_{{$decrypted_comp_id}}_{{$bout_id}}_{{$custom_bout_id}}" />
                            <input type="hidden" id="detailsKey" value="{{$details_key}}" />
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary"
                            data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/kata/bout/'.$bout_id.'/'.$custom_bout_id.'/'.$participants->id.'/save_data')}}"
                            id="{{$details_key}}Accept">Accept</button>
                    </div>
                </td>
            </tr>
            @endif

            <tr>
                <th scope="row">1</th>
                <td>Id</td>
                <td>{{$participants->id}}</td>
            </tr>

            <tr>
                <th scope="row">2</th>
                <td>External Unique Id</td>
                <td>{{$participants->external_unique_id}}</td>
            </tr>

            <tr>
                <th scope="row">3</th>
                <td>External Coach Info</td>
                <td>{{$participants->external_coach_code}} - {{$participants->external_coach_name}}</td>
            </tr>

            <tr>
                <th scope="row">4</th>
                <td>Team</td>
                <td>{{$participants->team}}</td>
            </tr>

            <tr>
                <th scope="row">5</th>
                <td>Full Name</td>
                <td>{{$participants->full_name}}</td>
            </tr>

            <tr>
                <th scope="row">6</th>
                <td>Gender</td>
                <td>{{$participants->gender}}</td>
            </tr>

            <tr>
                <th scope="row">7</th>
                <td>Age</td>
                <td>{{$participants->age}}</td>
            </tr>

            <tr>
                <th scope="row">8</th>
                <td>No of Participation</td>
                <td>{{$participants->no_of_part}}</td>
            </tr>

            <tr>
                <th scope="row">9</th>
                <td>No of Years</td>
                <td>{{$participants->no_of_year}}</td>
            </tr>

            <tr>
                <th scope="row">10</th>
                <td>Weight</td>
                <td>{{$participants->weight}}</td>
            </tr>

            <tr>
                <th scope="row">11</th>
                <td>Rank</td>
                <td>{{$participants->rank}}</td>
            </tr>
        </tbody>
    </table>
</div>