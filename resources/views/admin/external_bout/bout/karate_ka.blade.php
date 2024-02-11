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
            <tr>
                <th scope="row">1</th>
                <td>Id</td>
                <td>{{$participants->id}}</td>
            </tr>

            <tr>
                <th scope="row">2</th>
                <td>Team</td>
                <td>{{$participants->team}}</td>
            </tr>

            <tr>
                <th scope="row">3</th>
                <td>Full Name</td>
                <td>{{$participants->full_name}}</td>
            </tr>

            <tr>
                <th scope="row">4</th>
                <td>Gender</td>
                <td>{{$participants->gender}}</td>
            </tr>

            <tr>
                <th scope="row">5</th>
                <td>Age</td>
                <td>{{$participants->age}}</td>
            </tr>
            <tr>
                <th scope="row">6</th>
                <td>Weight</td>
                <td>{{$participants->weight}}</td>
            </tr>

            <tr>
                <th scope="row">7</th>
                <td>Rank</td>
                <td>{{$participants->rank}}</td>
            </tr>
        </tbody>
    </table>
</div>