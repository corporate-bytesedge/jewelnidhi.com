<div class="form-group">

    {!! Form::label('country', __('Country:')) !!} 
    <select name="country" id="country" class="form-control">
         
        <option value="India">India</option>
        <!-- @php
            if (!empty($countries)):
                foreach ($countries as $country):
                    echo '<option value="'.$country['country_name'].'">'.$country['country_name'].'</option>';
                endforeach;
            endif;
        @endphp -->
    </select>

</div>