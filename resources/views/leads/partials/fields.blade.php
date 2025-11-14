<div class="row">

    <div class="col-sm-6 border-right" >

{{--        @livewire('live-lead-form',[--}}
{{--            'lead' => $lead ?? null,--}}
{{--            'generateTitle' => $generateTitle ?? true,--}}
{{--            'client' => $client ?? null,--}}
{{--            'organisation' => $organisation ?? null,--}}
{{--            'person' => $person ?? null--}}
{{--        ])--}}

{{--        @if(!empty($action) && $action == 'edit')--}}

{{--            @livewire('live-lead-form',[--}}
{{--                'lead' => $lead ?? null,--}}
{{--                'generateTitle' => $generateTitle ?? true,--}}
{{--                'client' => $client ?? null,--}}
{{--                'organisation' => $organisation ?? null,--}}
{{--                'person' => $person ?? null--}}
{{--            ])--}}

{{--        @else--}}

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

{{--        @endif--}}

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

{{--        @include('laravel-crm::partials.form.text',[--}}
{{--            'name' => 'call_back',--}}
{{--            'label' => 'Перезвонить',--}}
{{--            // 'required' => 'true'--}}
{{--            'value' => old('title', $lead->call_back ?? null)--}}
{{--        ])--}}

        <div class="form-group">

            <div class="row" >

                <div class="col-6">

                    <label for="call_back"> Перезвонить </label>
                    <div class="autocomplete-control">
                        <input type="datetime-local" value="{{$lead->call_back ?? ''}}" name="call_back"  id="input_call_back"  class="form-control">
                    </div>

                </div>

                <div class="col-6">

                    <label for="call_back"> Приехал </label>
                    <div class="autocomplete-control">
                        <input type="datetime-local" value="{{$lead->will_come ?? ''}}" name="will_come"  id="input_call_back"  class="form-control">
                    </div>

                </div>

            </div>

        </div>

{{--        <div class="row">--}}
{{--            <div class="col-sm-6">--}}
{{--                @include('laravel-crm::partials.form.text',[--}}
{{--                      'name' => 'amount',--}}
{{--                      'label' => ucfirst(__('laravel-crm::lang.value')),--}}
{{--                      'prepend' => '<span class="fa fa-dollar" aria-hidden="true"></span>',--}}
{{--                      'value' => old('amount', ((isset($lead->amount)) ? ($lead->amount / 100) : null) ?? null)--}}
{{--                  ])--}}
{{--            </div>--}}
{{--            <div class="col-sm-6">--}}
{{--                @include('laravel-crm::partials.form.select',[--}}
{{--                    'name' => 'currency',--}}
{{--                    'label' => ucfirst(__('laravel-crm::lang.currency')),--}}
{{--                    'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\currencies(),--}}
{{--                    'value' => old('currency', $lead->currency ?? \VentureDrake\LaravelCrm\Models\Setting::currency()->value ?? 'USD')--}}
{{--                ])--}}
{{--            </div>--}}
{{--        </div>--}}

        @if($pipeline)
            @include('laravel-crm::partials.form.select',[
                     'name' => 'pipeline_stage_id',
                     'label' => ucfirst(__('laravel-crm::lang.stage')),
                     'options' => $pipeline->pipelineStages()
                                            ->orderBy('order')
                                            ->orderBy('id')
                                            ->pluck('name', 'id') ?? [],
                     'value' =>  old('pipeline_stage_id', $lead->pipelineStage->id ?? $stage ?? $pipeline->pipelineStages()
                                            ->orderBy('order')
                                            ->orderBy('id')
                                            ->first()->id ?? null),
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

    <div class="col-sm-6" style="border:0px red solid" >

        <h6 class="text-uppercase">
            <span class="fa fa-user" aria-hidden="true"></span>
            {{ strtoupper(__('laravel-crm::lang.person')) }}
        </h6>

        <hr />

        <span class="autocomplete-person">

            <div class="row">

{{--                <div class="col-sm-12">--}}

{{--                    <div class="form-group ">--}}
{{--                        <label for="input_client_name"> Клиент </label>--}}
{{--                        <div class="autocomplete-control">--}}
{{--                            <div class="input-group dropdown">--}}
{{--                                <div class="input-group-prepend">--}}
{{--                                   <span class="input-group-text" ><span class="fa fa-address-card" aria-hidden="true"></span></span>--}}
{{--                                </div>--}}
{{--                                <input name="client_name" value="" id="input_client_name" type="text"  class="form-control dropdown-toggle">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                </div>--}}

{{--                <div class="col-sm-12 mb-4" >--}}
{{--                    <label for="input_client_name">  </label>--}}
{{--                    <div class="autocomplete-control">--}}
{{--                        <div class="input-group dropdown">--}}
{{--                            <div class="input-group-prepend">--}}
{{--                               <span class="input-group-text" ><span class="fa fa-address-card" aria-hidden="true"></span></span>--}}
{{--                            </div>--}}
{{--                            <input name="person_name" value="" id="input_client_name" type="text"  class="form-control dropdown-toggle">--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="col-sm-6">

                    @include('laravel-crm::partials.form.text',[
                     'name' => 'phone',
                     'label' => ucfirst(__('laravel-crm::lang.phone')),
                     'value' => old('phone', $person->phone ?? null),
                      // 'attributes' => ['disabled' => 'disabled']
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

{{--            <div class="row">--}}
{{--                <div class="col-sm-6">--}}
{{--                    @include('laravel-crm::partials.form.text',[--}}
{{--                     'name' => 'email',--}}
{{--                     'label' => ucfirst(__('laravel-crm::lang.email')),--}}
{{--                     'value' => old('email', $email->address ?? null),--}}
{{--                     // 'attributes' => ['disabled' => 'disabled']--}}
{{--                  ])--}}
{{--                </div>--}}
{{--                <div class="col-sm-6">--}}
{{--                    @include('laravel-crm::partials.form.select',[--}}
{{--                     'name' => 'email_type',--}}
{{--                     'label' => ucfirst(__('laravel-crm::lang.type')),--}}
{{--                     'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\emailTypes(),--}}
{{--                     'value' => old('email_type', $email->type ?? 'work'),--}}
{{--                     // 'attributes' => ['disabled' => 'disabled']--}}
{{--                  ])--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="row" >
                 <div class="col-sm-12 border-right">

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

                        <div class="col-8">
                            <div class="form-group ">
                                <label for="middle_name"> Место регистрации </label>
                                <div class="autocomplete-control">
                                   <input id="input_register_address" type="text" name="register_address"
                                          value="{{$person->register_address ?? ''}}" class="form-control ">
                                </div>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group" >

                                 <label> Проживает по месту регистрации </label>

                                @if(!empty($person->check_address))
                                    <input type="checkbox" id="apple" name="check_address" value="1" checked >
                                @else
                                    <input type="checkbox" id="apple" name="check_address" value="1">
                                @endif

                            </div>
                        </div>

                    </div>

                  </div>
            </div>

        </span>

{{--        <h6 class="text-uppercase mt-4"><span class="fa fa-building" aria-hidden="true"></span> {{ strtoupper(__('laravel-crm::lang.organization')) }} </h6>--}}

{{--        <hr />--}}

{{--        <span class="autocomplete-organisation">--}}
{{--         --}}
{{--            @include('laravel-crm::partials.form.text',[--}}
{{--               'name' => 'line1',--}}
{{--               'label' => ucfirst(__('laravel-crm::lang.address_line_1')),--}}
{{--               'value' => old('line1', $address->line1 ?? null),--}}
{{--               'attributes' => [--}}
{{--                         'disabled' => 'disabled'--}}
{{--                     ]--}}
{{--            ])--}}
{{--            @include('laravel-crm::partials.form.text',[--}}
{{--               'name' => 'line2',--}}
{{--               'label' => ucfirst(__('laravel-crm::lang.address_line_2')),--}}
{{--               'value' => old('line2', $address->line2 ?? null),--}}
{{--               'attributes' => [--}}
{{--                         'disabled' => 'disabled'--}}
{{--                     ]--}}

{{--            ])--}}
{{--            @include('laravel-crm::partials.form.text',[--}}
{{--               'name' => 'line3',--}}
{{--               'label' => ucfirst(__('laravel-crm::lang.address_line_3')),--}}
{{--               'value' => old('line3', $address->line3 ?? null),--}}
{{--               'attributes' => [--}}
{{--                         'disabled' => 'disabled'--}}
{{--                     ]--}}
{{--            ])--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-6">--}}
{{--                    @include('laravel-crm::partials.form.text',[--}}
{{--                       'name' => 'city',--}}
{{--                       'label' => ucfirst(__('laravel-crm::lang.suburb')),--}}
{{--                       'value' => old('city', $address->city ?? null),--}}
{{--                        'attributes' => [--}}
{{--                            'disabled' => 'disabled'--}}
{{--                         ]--}}
{{--                    ])--}}
{{--                </div>--}}
{{--                <div class="col-sm-6">--}}
{{--                    @include('laravel-crm::partials.form.text',[--}}
{{--                       'name' => 'state',--}}
{{--                       'label' => ucfirst(__('laravel-crm::lang.state')),--}}
{{--                       'value' => old('state', $address->state ?? null),--}}
{{--                       'attributes' => [--}}
{{--                                 'disabled' => 'disabled'--}}
{{--                        ]--}}
{{--                    ])--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <div class="row">--}}
{{--                <div class="col-sm-6">--}}
{{--                    @include('laravel-crm::partials.form.text',[--}}
{{--                       'name' => 'code',--}}
{{--                       'label' => ucfirst(__('laravel-crm::lang.postcode')),--}}
{{--                       'value' => old('code', $address->code ?? null),--}}
{{--                        'attributes' => [--}}
{{--                         'disabled' => 'disabled'--}}
{{--                        ]--}}
{{--                    ])--}}
{{--                </div>--}}
{{--                <div class="col-sm-6">--}}
{{--                    @include('laravel-crm::partials.form.select',[--}}
{{--                     'name' => 'country',--}}
{{--                     'label' => ucfirst(__('laravel-crm::lang.country')),--}}
{{--                     'options' => \VentureDrake\LaravelCrm\Http\Helpers\SelectOptions\countries(),--}}
{{--                     'value' => old('country', $address->country ?? 'United States'),--}}
{{--                     'attributes' => [--}}
{{--                         'disabled' => 'disabled'--}}
{{--                     ]--}}
{{--                  ])--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </span>--}}

    </div>

</div>
