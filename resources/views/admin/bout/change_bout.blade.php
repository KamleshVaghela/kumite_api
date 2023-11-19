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
        <td>External Coach Info</td>
        <td>{{$participants->external_coach_code}} - {{$participants->external_coach_name}}</td>
      </tr>

      <tr>
        <th scope="row">2</th>
        <td>Full Name</td>
        <td>{{$participants->full_name}}</td>
      </tr>

      @if(isset($boutList))
        <tr>
          <th scope="row">11</th>
          <td>Bout</td>
          <td>
            <div id="div_ajax_{{$details_key}}">
            </div>
            <div class="modal-body">
                <form class="px-4 needs-validation" id="form_{{$details_key}}" name="form_{{$details_key}}"  method="POST" >
                  @csrf

                    <div class="form-group">
                        <label for="sequence">Sequence</label>
                        <input type="number" class="form-control" id="sequence" name="sequence" placeholder="1-8" min="1" max="8" >
                        <small id="category_error" class="form-text post_error"></small>
                    </div>

                    <hr/>
                    <span class="chip">AND</span>

                    <label class="mr-sm-2 sr-only" for="inlineFormCustomSelect">Result</label>
                    <select class="custom-select mr-sm-2" id="bout_id" name="bout_id">
                      <option selected value="0" >None</option>
                        @forelse($boutList as $key=>$rec)
                            <option value="{{$rec->id}}" >{{$rec->bout_number}} : {{$rec->category}}</option>
                        @empty
                            <p class="bg-danger text-white p-1">No Item data found</p>
                        @endforelse
                    </select>
                    
                    <hr/>
                    <span class="chip">OR</span>
                    <div class="form-group">
                        <label for="bout_number">Bout Number</label>
                        <input type="text" class="form-control" id="bout_number" name="bout_number" placeholder="Bout Number" >
                        <small id="bout_number_error" class="form-text post_error"></small>
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <input type="text" class="form-control" id="category" name="category" placeholder="Category" >
                        <small id="category_error" class="form-text post_error"></small>
                    </div>

                    <input type="hidden" class="form-control" id="gender" name="gender" value="{{$participants->gender}}" >

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-href="{{URL::to('admin/competition/board/'.$decrypted_comp_id.'/bout/'.$bout_id.'/'.$custom_bout_id.'/'.$participants->id.'/save_change_bout')}}" id="{{$details_key}}Accept">Accept</button>
            </div>
          </td>
        </tr>
      @endif
    </tbody>
  </table>
</div>