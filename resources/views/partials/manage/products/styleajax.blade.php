 
 

 
 
 
    <div class="form-group">
        <div class="style_box">
            <label for="style_id">@lang('Style:')</label>
            <select name="style_id[]" id="style_id" placholder="Choose Style" multiple>

                <option value="">@lang('Choose Style')</option>
                    @if(!empty($styleArr))
                        @foreach($styleArr AS $k => $value)
                        
                            <option value="{{ $value['id'] }}"  >{{ $value['name'] }} </option>
                        @endforeach
                    @endif
            </select>
        </div>
    </div>
    