<div class="row">

    <div class="col-sm-6 border-right" >

        <div class="form-group ">
            <label for="organisation_name"> Организация <span class="required-label"> * </span></label>
            <div class="autocomplete-control">
                <div class="input-group dropdown show">
                    <div class="input-group-prepend">
                        <span class="input-group-text" ><span class="fa fa-building" aria-hidden="true"></span></span>
                    </div>
                    <input value="{{$organisation->name}}" name="organisation_name" id="input_organisation_name" type="text"  disabled
                           class="form-control dropdown-toggle" >
                </div>
            </div>
        </div>

        @include('laravel-crm::partials.form.text',[
            'name' => 'title',
            'label' => ucfirst(__('laravel-crm::lang.title')),
            // 'required' => 'true'
            'value' => old('title', $lead->title ?? null)
        ])

        @include('laravel-crm::partials.form.textarea',[
             'name' => 'description',
             'label' => ucfirst(__('laravel-crm::lang.description')),
             'rows' => 5,
             'value' => old('description', $lead->description ?? null)
        ])

        <div class="form-group">
            <div class="row" >
                <div class="col-6">
                    <label for="call_back"> Перезвонить </label>
                    <div class="autocomplete-control">
                        <input type="datetime-local" value="{{$lead->call_back ?? ''}}" name="call_back"  id="input_call_back"  class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <label for="call_back"> Приедет </label>
                    <div class="autocomplete-control">
                        <input type="datetime-local" value="{{$lead->will_come ?? ''}}" name="will_come"  id="input_call_back"  class="form-control">
                    </div>
                </div>
            </div>
        </div>

        @if($pipeline)
            @include('laravel-crm::partials.form.select',[
                     'name'    => 'pipeline_stage_id',
                     'label'   => ucfirst(__('laravel-crm::lang.stage')),
                     'options' => $pipeline->pipelineStages()->orderBy('order')->orderBy('id')->pluck('name', 'id') ?? [],
                     'value'   =>  old('pipeline_stage_id', $lead->pipelineStage->id ?? $stage ??
                                            $pipeline->pipelineStages()->orderBy('order')->orderBy('id')->first()->id ?? null
                                 ),
            ])
        @endif

        @include('laravel-crm::partials.form.select',[
             'name'    => 'lead_source_id',
             'label'   => 'Источник',
             'options' => $lead_sources,
             'value' =>  old('lead_source_id', (isset($lead)) ? $lead->lead_source_id ?? '' : 0),
        ])

        @include('laravel-crm::partials.form.multiselect',[
                    'name' => 'labels',
                    'label' => ucfirst(__('laravel-crm::lang.labels')),
                    'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\optionsFromModel(\VentureDrake\LaravelCrm\Models\Label::all(), false),
                    'value' =>  old('labels', (isset($lead)) ? $lead->labels->pluck('id')->toArray() : null)
        ])

        @include('laravel-crm::partials.form.select',[
                 'name' => 'user_owner_id',
                 'label' => ucfirst(__('laravel-crm::lang.owner')),
                 'options' => ['' => ucfirst(__('laravel-crm::lang.unallocated'))] + \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\users(false),
                 'value' =>  old('user_owner_id', (isset($lead)) ? $lead->user_owner_id ?? '' : auth()->user()->id),
        ])

        @include('laravel-crm::fields.partials.model', ['model' => $lead ?? new \VentureDrake\LaravelCrm\Models\Lead()])

    </div>

    <div class="col-sm-6" >

        <h6 class="text-uppercase">
            <span class="fa fa-user" aria-hidden="true"></span>
            {{ strtoupper(__('laravel-crm::lang.person')) }}
        </h6>

        <hr />

        <span class="autocomplete-person">

            <div class="row">

                <div class="col-sm-6">
                    @include('laravel-crm::partials.form.text',[
                         'name' => 'phone',
                         'label' => ucfirst(__('laravel-crm::lang.phone')),
                         'value' => old('phone', $person->phone ?? null),
                    ])
                </div>

                <div class="col-sm-6">
                    @include('laravel-crm::partials.form.select',[
                         'name' => 'phone_type',
                         'label' => ucfirst(__('laravel-crm::lang.type')),
                         'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\phoneTypes(),
                         'value' => old('phone_type', $phone->type ??  'mobile'),
                         // 'attributes' => ['disabled' => 'disabled']
                   ])
                </div>

            </div>

            <div class="row" >
                 <div class="col-sm-12 border-right1">

                    <div class="row">

                        <input type="hidden" value="{{$person->id ?? ''}}"
                               name="person_id" id="person_id"
                               class="form-control dropdown-toggle" >

                        <div class="col">
                            <div class="form-group ">
                                <label > Имя <span class="required-label"> * </span></label>
                                <div class="autocomplete-control">
                                    <input id="input_first_name" type="text" name="first_name"
                                           value="{{$person->first_name ?? ''}}" class="form-control ">
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group ">
                                <label for="last_name"> Фамилия </label>
                                <div class="autocomplete-control">
                                   <input id="input_last_name" type="text" name="last_name"
                                          value="{{$person->last_name ?? ''}}" class="form-control ">
                                </div>
                             </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group ">
                                <label for="middle_name"> Отчество </label>
                                <div class="autocomplete-control">
                                   <input id="input_middle_name" type="text"
                                          value="{{$person->middle_name ?? ''}}" name="middle_name"  class="form-control ">
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group ">
                                <label for="birthday">День рождения </label>
                                <div class="autocomplete-control">
                                  <input type="text" value="{{$person->birthday ?? ''}}" name="birthday"  id="input_birthday"  class="form-control">
                                </div>
                            </div>
                        </div>

{{--                         <div class="col">--}}
{{--                            <div class="form-group ">--}}
{{--                              <label for="gender">Пол</label>--}}
{{--                                <select id="select_gender" name="gender" class="form-control custom-select ">--}}
{{--                                    <option value="0"></option>--}}
{{--                                    <option value="male">Мужской</option>--}}
{{--                                    <option value="female">Женский</option>--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}

                    </div>

                    <div class="row">

                        <div class="col-12">
                            <div class="form-group ">
                                <label for="middle_name"> Место проживания </label>
                                <div class="autocomplete-control">
                                   <input id="input_person_address" type="text" name="person_address"
                                          value="{{$person->person_address ?? ''}}" class="form-control ">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group" >

                                @if(!empty($person->check_address))
                                    <input type="checkbox" id="apple" name="check_address" value="1" checked >
                                @else
                                    <input type="checkbox" id="apple" name="check_address" value="1">
                                @endif

                                <label style="margin-left: 10px"> Проживает по месту регистрации </label>

                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group ">
                                <label for="middle_name"> Место регистрации </label>
                                <div class="autocomplete-control">
                                   <input id="input_register_address" type="text" name="register_address"
                                          value="{{$person->register_address ?? ''}}" class="form-control ">
                                </div>
                            </div>
                        </div>

                    </div>

                  </div>
            </div>

        </span>

    </div>

</div>
