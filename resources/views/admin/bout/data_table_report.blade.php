<div class="container">
    <div class="row">
        <div class="modal fade" id="addModal" tabindex="-1"></div>
        <table id="datatables-example" class="table" style="width:120%">
            <thead>
                <tr>
                    <th>Id</th>
                    {{--<th>Competition Id</th>
          <th>external_unique_id</th>
          <th>external_coach_code</th> --}}
                    <th style="width:150px;">Team</th>
                    <th style="width:250px;">Bout Category</th>
                    <th style="width:200px;">Full Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Rank</th>
                    <th># of Competition</th>
                    <th># of Membership</th>
                    <th>Bout Number</th>
                </tr>
            </thead>
            <tbody>
                @forelse($participants_records as $key=>$rec)
                <tr>
                    <td>
                        <a href="#" onclick="loadLoadDetails(this)"
                            data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/data_table/'.$rec->id.'/get_participant_data')}}"
                            data-details_key="edit_view">
                            {{ $rec->id }}
                        </a>
                    </td>
                    {{-- <td>{{ $rec->competition_id }}</td>
                    <td>{{ $rec->external_unique_id }}</td>
                    <td>{{ $rec->external_coach_code }}</td> --}}
                    <td>{{ $rec->team }}</td>
                    <td>{{ $rec->bouts_category }}</td>
                    <td>{{ $rec->full_name }}</td>
                    <td>{{ $rec->gender }}</td>
                    <td>{{ $rec->age }}</td>
                    <td>{{ $rec->weight }}</td>
                    <td>{{ $rec->rank }}</td>
                    <td>{{ $rec->no_of_part }}</td>
                    <td>{{ $rec->no_of_year }}</td>
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
                    {{--<th>Competition Id</th>
        <th>external_unique_id</th>
        <th>external_coach_code</th> --}}
                    <th style="width:150px;">Team</th>
                    <th style="width:250px;">Bout Category</th>
                    <th style="width:200px;">Full Name</th>
                    <th>Gender</th>
                    <th>Age</th>
                    <th>Weight</th>
                    <th>Rank</th>
                    <th># of Competition</th>
                    <th># of Membership</th>
                    <th>Bout Number</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>