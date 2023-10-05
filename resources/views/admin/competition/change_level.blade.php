@switch($level)
    @case('IDJ')
        <span>Inter-Dojo</span>
        <div class="form-group form-ripple position-relative">
          <label for="coach">Coach</label>
          <select id="coach" name="coach" class="form-control select2" style="width: 100%;">
              @foreach($coaches as $key=>$rec)
                <option value="{{$rec->COACH_ID}}">{{$rec->COACH_NAME}}</option>
              @endforeach
          </select>
          <small id="coach_error" class="form-text post_error"></small>
        </div>
        @break
    @case('ISC')
        <span>Inter-School</span>
        {{-- <div class="form-group form-ripple position-relative">
          <label for="state">State</label>
          <select id="state" name="state" class="form-control select2" style="width: 100%;">
              @foreach($states as $key=>$rec)
                <option value="{{$rec->STATE}}">{{$rec->STATE}}</option>
              @endforeach
          </select>
          <small id="state_error" class="form-text post_error"></small>
        </div> --}}
        <div class="form-group form-ripple position-relative">
          <label for="district">District</label>
          <select id="district" name="district" class="form-control select2" style="width: 100%;">
              @foreach($districts as $key=>$rec)
                <option value="{{$rec->GEOID}}">{{$rec->DISTRICT}}-{{$rec->STATE}}</option>
              @endforeach
          </select>
          <small id="state_error" class="form-text post_error"></small>
        </div>
        @break
    @case('IDS')
        <span>'IDS' => 'Inter-District',</span>
        @break
    @case('D')
        <span>'D' => 'District',</span>
        @break
    @case('IST')
        <span>'IST' => 'Inter-State',</span>
        @break
    @case('S')
        <span>'S' => 'State'</span>
        @break
    @case('N')
        <span>'N' => 'National'</span>
        @break 
    @case('I')
        <span>'I' => 'International'</span>
        @break 
    @default
        <span>Select Competition Level</span>
@endswitch