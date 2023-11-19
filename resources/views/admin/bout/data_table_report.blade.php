<div class="container">
  <div class="row">
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
          <th>Bout Id</th>
        </tr>
    </thead>
    <tbody>
      @forelse($participants_records as $key=>$rec)
        <tr>
          <td>{{ $rec->id }}</td>
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
          <td>{{ $rec->bouts_id }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="12" >No Record Found</td>
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
          <th>Bout Id</th>
      </tr>
  </tfoot>
  </table>
  </div>
</div>