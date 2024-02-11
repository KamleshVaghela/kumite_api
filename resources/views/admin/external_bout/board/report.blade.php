<div class="container">
    <nav class="nav nav-pills flex-column flex-sm-row">
        <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
            href="{{URL::to('admin/external_bout/board/'.$external_competition->id)."/export_excel"}}"><button
                class="btn btn-outline-secondary" type="button">Export Excel</button></a>
        <a class="flex-sm-fill text-sm-center nav-link active" href="#" onclick="loadDetails(this)"
            data-href="{{URL::to('admin/external_bout/board/'.$external_competition->id)."/import_excel"}}"
            data-details_key="importExcel">
            <button class="btn btn-outline-info" type="button">Import Excel</button></a>

        <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
            href="{{URL::to('admin/external_bout/board/'.$external_competition->id)."/bout/data_table"}}"><button
                class="btn btn-outline-success" type="button">Data Table</button></a>
        <a class="flex-sm-fill text-sm-center nav-link active" target="_blank"
            href="{{URL::to('admin/external_bout/board/'.$external_competition->id)."/bout/list/index"}}"><button
                class="btn btn-outline-primary" type="button">Board</button></a>
    </nav>
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
                                <th scope="row">Name</th>
                                <td>{{ $external_competition->name}}</td>
                            </tr>
                            <tr>
                                <th scope="row">short_description</th>
                                <td>{{ $external_competition->short_description}}</td>
                            </tr>
                            <tr>
                                <th scope="row">additional_details</th>
                                <td>{{ $external_competition->additional_details}} </td>
                            </tr>
                        </tbody>
                    </table>
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
                                <th scope="row">Start Date</th>
                                <td>{{ $external_competition->start_date}}</td>
                            </tr>
                            <tr>
                                <th scope="row">End Date</th>
                                <td>{{ $external_competition->end_date}}</td>
                            </tr>
                        </tbody>
                    </table>
                    </p>
                </div>
            </div>
        </div>
    </div>