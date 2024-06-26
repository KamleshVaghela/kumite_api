<div class="container">
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <nav class="nav nav-pills flex-column flex-sm-row">
                    <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/refresh_data"}}"
                        data-details_key="refresh_data">
                        <button class="btn btn-outline-success" type="button">Refresh Data</button>
                    </a>
                    <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
                        href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout/data_table"}}"><button
                            class="btn btn-outline-success" type="button">Data Table</button></a>
                    <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
                        href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout/results/index"}}"><button
                            class="btn btn-outline-success" type="button">Results & Downloads</button></a>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <div class="card-header border-0">
                <h5 class="card-title">Kumite Actions</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <nav class="nav nav-pills flex-column flex-sm-row">
                    <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
                        href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/export_excel"}}">
                        <button class="btn btn-outline-primary" type="button">Export Excel</button></a>
                    <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/import_excel"}}"
                        data-details_key="importExcel">
                        <button class="btn btn-outline-info" type="button">Import Excel</button></a>

                    <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
                        href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout/index"}}">
                        <button class="btn btn-outline-primary" type="button">Board</button></a>
                </nav>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <div class="card-header border-0">
                <h5 class="card-title">Kata Actions</h5>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <div class="card">
                <nav class="nav nav-pills flex-column flex-sm-row">
                    <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
                        href="{{URL::to('admin/competition/board/'.$competition->COMP_ID).'/export_kata_excel'}}">
                        <button class="btn btn-outline-secondary" type="button">Export Excel</button></a>
                    <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID).'/import_kata_excel'}}"
                        data-details_key="importExcel">
                        <button class="btn btn-outline-secondary" type="button">Import Excel</button></a>

                    <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
                        href="{{URL::to('admin/competition/board/'.$competition->COMP_ID).'/kata/bout/index'}}">
                        <button class="btn btn-outline-secondary" type="button">Board</button></a>
                </nav>
            </div>
        </div>
    </div>
    <hr />
    <div class="row">
        <div class="col-sm">
            <div class="card" style="max-width: 350px;">
                <div class="card-header border-0">
                    <h5 class="card-title">Competition Details</h5>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Karate-Ka Display</th>
                                <td>{{ $competition->KARATE_KA_DISPLAY}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Remarks</th>
                                <td>{{ $competition->REMARKS}}</td>
                            </tr>
                            <tr>
                                <th scope="row">ID</th>
                                <td>{{ $competition->COMP_ID}} {{ $competition_id}} </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal fade" id="competition_detailsModal" tabindex="-1"></div>
                <div class="card-actions justify-content-end">
                    <button class="btn btn-icon" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/competition_details"}}"
                        data-details_key="competition_details" type="button">
                        <i class="material-icons">edit</i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card" style="max-width: 350px;">
                <div class="card-header border-0">
                    <h5 class="card-title">Important Dates</h5>
                </div>
                <div class="card-body pt-0">
                    <p class="card-text">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Karate-Ka End Date</th>
                                <td>{{ $competition->CLOSE_DATE_K}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Coach End Date</th>
                                <td>{{ $competition->CLOSE_DATE_C}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Start Date</th>
                                <td>{{ $competition->COMP_DATE}}</td>
                            </tr>
                            <tr>
                                <th scope="row">End Date</th>
                                <td>{{ $competition->COMP_END_DATE}}</td>
                            </tr>
                        </tbody>
                    </table>
                    </p>
                </div>
                <div class="modal fade" id="important_datesModal" tabindex="-1"></div>
                <div class="card-actions justify-content-end">
                    <button class="btn btn-icon" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/important_dates"}}"
                        data-details_key="important_dates" type="button">
                        <i class="material-icons">edit</i>
                    </button>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card" style="max-width: 350px;">
                <div class="card-header border-0">
                    <h5 class="card-title">Fees Details</h5>
                </div>
                <div class="card-body pt-0">
                    <p class="card-text">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">General</th>
                                <td>{{ $competition->FEES}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Kata</th>
                                <td>{{ $competition->FEES_KATA}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Kumite</th>
                                <td>{{ $competition->FEES_KUMITE}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Team Kata</th>
                                <td>{{ $competition->FEES_T_KATA}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Team Kumite</th>
                                <td>{{ $competition->FEES_T_KUMITE}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Coach</th>
                                <td>{{ $competition->COACH_FEES}}</td>
                            </tr>
                        </tbody>
                    </table>
                    </p>
                </div>
                <div class="modal fade" id="fees_detailsModal" tabindex="-1"></div>
                <div class="card-actions justify-content-end">
                    <button class="btn btn-icon" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/fees_details"}}"
                        data-details_key="fees_details" type="button">
                        <i class="material-icons">edit</i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-sm">
            <div class="card" style="max-width: 350px;">
                <div class="card-header border-0">
                    <h5 class="card-title">Level Details</h5>
                </div>
                <div class="card-body pt-0">
                    <p class="card-text">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Level</th>
                                <td>{{ $competition->TYPE}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Geo Id</th>
                                <td>{{ $competition->GEOID}}</td>
                            </tr>
                            <tr>
                                <th scope="row">State</th>
                                <td>{{ $competition->STATE}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Coach</th>
                                <td>{{ $competition->COACH_ID}}</td>
                            </tr>
                        </tbody>
                    </table>
                    </p>
                </div>
                <div class="card-actions justify-content-end">
                    {{-- <button class="btn btn-icon" onclick="loadDetails(this)" 
            data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/level_details"}}"
                    data-details_key="level_details"
                    type="button">
                    <i class="material-icons">edit</i>
                    </button> --}}
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card" style="max-width: 350px;">
                <div class="card-header border-0">
                    <h5 class="card-title">Bout Details</h5>
                </div>
                <div class="card-body pt-0">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row">Registration Count</th>
                                <td>{{ $compParticipants->count() }} / {{ $competition_parts->count() }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Participants</th>
                                <td>{{ $bout_participant_details->count() }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Bout Count</th>
                                <td>{{ $bouts->count() }}
                                    {{-- <button class="btn btn-icon" onclick="loadDetails(this)" 
                  data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout_details"}}"
                                    data-details_key="bout_details"
                                    type="button">
                                    <i class="material-icons">edit</i>
                                    </button> --}}
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">View Bout Board</th>
                                <td>{{ $bouts->count() }}</td>
                            </tr>
                        </tbody>
                    </table>
                    {{-- <button class="btn btn-icon" 
            onclick="loadDetails(this)" 
                  data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/bout_details"}}"
                    data-details_key="bout_details"
                    type="button">
                    <i class="material-icons">edit</i>
                    </button> --}}
                </div>
                <div class="modal fade" id="refresh_dataModal" tabindex="-1"></div>
                <div class="modal fade" id="importExcelModal" tabindex="-1"></div>
                <div class="modal fade" id="result_detailsModal" tabindex="-1"></div>
                <div class="modal fade" id="bout_detailsModal" tabindex="-1"></div>
                <div class="modal fade" id="clear_dataModal" tabindex="-1"></div>
                <div class="card-actions justify-content-end">
                    <button class="btn btn-icon" onclick="loadDetails(this)"
                        data-href="{{URL::to('admin/competition/board/'.$competition->COMP_ID)."/clear_data"}}"
                        data-details_key="clear_data" type="button">
                        <i class="material-icons">delete</i>
                    </button>
                </div>
            </div>
        </div>
    </div>