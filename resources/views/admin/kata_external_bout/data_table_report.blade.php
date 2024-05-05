<div class="container">
    <div class="row">
        <table id="datatables-example" class="table" style="width:120%">
            <thead>
                <tr>
                    <th>Id</th>
                    <th style="width:200px;">Full Name</th>
                    <th>Gender</th>
                    <th style="width:150px;">Team</th>
                    <th>Coach</th>
                    <th>Rank</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Category</th>
                    <th>Age Category</th>
                    <th>Weight Category</th>
                    <th>Rank Category</th>
                    <th>Tatami</th>
                    <th>Session</th>
                    <th>Bout Number</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants_records as $key=>$rec)
                <tr>
                    <td>{{ $rec->id }}</td>
                    <td>{{ $rec->full_name }}</td>
                    <td>{{ $rec->gender }}</td>
                    <td>{{ $rec->team }}</td>
                    <td>{{ $rec->coach_name }}</td>
                    <td>{{ $rec->rank }}</td>
                    <td>{{ $rec->age }}</td>
                    <td>{{ $rec->weight }}</td>

                    <td>{{ $rec->category }}</td>
                    <td>{{ $rec->age_category }}</td>
                    <td>{{ $rec->weight_category }}</td>

                    <td>{{ $rec->rank_category }}</td>
                    <td>{{ $rec->tatami }}</td>
                    <td>{{ $rec->session }}</td>
                    <td>{{ $rec->bout_number }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="12">No Record Found</td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <th>Id</th>
                    <th style="width:200px;">Full Name</th>
                    <th>Gender</th>
                    <th style="width:150px;">Team</th>
                    <th>Coach</th>
                    <th>Rank</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Category</th>
                    <th>Age Category</th>
                    <th>Weight Category</th>
                    <th>Rank Category</th>
                    <th>Tatami</th>
                    <th>Session</th>
                    <th>Bout Number</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>