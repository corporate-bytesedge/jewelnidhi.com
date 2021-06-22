<div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6">

            @if(session()->has('price_updated'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <strong>{{session('price_updated')}}</strong>
                </div>
            @endif

            @include('includes.form_errors')

            {!! Form::model($pricesetting, ['method'=>'patch', 'action'=>['ManageSettingsController@updatePriceSetting'], 'files'=>true, 'onsubmit'=>'submit_button.disabled = true; submit_button.value = "' . __('Please Wait...') . '"; return true;']) !!}
            
                <div class="form-group" style="text-align:center;">
                    {!! Form::label('gold_rate', __('Gold Price:')) !!}
                </div>
                <div class="form-group">
                    @if($metalPurity)
                        @foreach($metalPurity AS $k => $price_setting)
                            @php $prorityCarat = str_replace(' ','_',$price_setting->name) @endphp 
                          
                        <div class="col-sm-3">
                            <div class="form-group">
                                {!! Form::label('gold_rate', __($price_setting['name'])) !!} 
                                {!! Form::text($price_setting->name,array_has($goldpricesetting, $prorityCarat) ? $goldpricesetting[$prorityCarat] : 0.00, ['class'=>'form-control', 'placeholder'=>__('Enter  Price')])!!}
                            </div>
                        </div>
                    @endforeach
                    @endif  
                    
                   
                </div>
                <div class="form-group " style="text-align:center;">
                    {!! Form::label('silver_rate', __('Silver Price:')) !!}
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('silver_jewellery_rate', __('Silver Jewellery:')) !!} 
                            {!! Form::text('silver_jewellery_rate',array_has($pricesetting, 'silver_jewellery_rate') ? $pricesetting['silver_jewellery_rate'] : null,['class'=>'form-control', 'placeholder'=>__('Enter Silver Jewellery Price')])!!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('silver_item_rate', __('Silver Item:')) !!}
                            {!! Form::text('silver_item_rate',array_has($pricesetting, 'silver_item_rate') ? $pricesetting['silver_item_rate'] : null,['class'=>'form-control', 'placeholder'=>__('Enter Silver Item Price')])!!}
                        </div>
                    </div>
                    
                </div>

                <div class="form-group " style="text-align:center;">
                    {!! Form::label('platinum_rate', __('Platinum Price:')) !!}
                </div>
                <div class="form-group">
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('platinum_rate', __('750KT:')) !!} 
                            {!! Form::text('750_KT',array_has($platiumpricesetting, 'platinum_seventy_five') ? $platiumpricesetting['platinum_seventy_five'] : null,['class'=>'form-control', 'placeholder'=>__('Enter Platinum Price')])!!}
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            {!! Form::label('platinum_rate', __('950KT:')) !!}
                            {!! Form::text('950_KT',array_has($platiumpricesetting, 'platinum_nigntifive_five') ? $platiumpricesetting['platinum_nigntifive_five'] : null,['class'=>'form-control', 'placeholder'=>__('Enter Platinum Price')])!!}
                        </div>
                    </div>
                    
                </div>
            

                <div class="form-group">
            {!! Form::submit(__('Update Price'), ['class'=>'btn btn-primary btn-block', 'name'=>'submit_button']) !!}
        </div>
            
            </div>
            
            
            

       

        {!! Form::close() !!}

    </div>
</div>