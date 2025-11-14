@component('laravel-crm::components.card')

        @component('laravel-crm::components.card-header')

            @slot('title')
                {{ ucfirst(__('laravel-crm::lang.edit_lead')) }} ({{$lead->lead_id}})
            @endslot

            @slot('actions')

                @include('laravel-crm::partials.return-button',[
                   'model' => $lead,
                   'route' => 'leads'
               ])

                @hasdealsenabled
                @can('edit crm leads')
                    | <a href="{{ route('laravel-crm.leads.convert-to-deal',$lead) }}" class="btn btn-success btn-sm">
                        {{ ucfirst(__('laravel-crm::lang.convert')) }}
                    </a>
                @endcan
                @endhasdealsenabled

                @include('laravel-crm::partials.navs.activities') |

                @can('edit crm leads')
                    <a href="{{ url(route('laravel-crm.leads.edit', $lead)) }}" type="button" class="btn btn-outline-secondary btn-sm">
                        <span class="fa fa-edit" aria-hidden="true"></span>
                    </a>
                @endcan

                @can('delete crm leads')
                    <form action="{{ route('laravel-crm.leads.destroy',$lead) }}" method="POST" class="form-check-inline mr-0 form-delete-button">
                        {{ method_field('DELETE') }}
                        {{ csrf_field() }}
                        <button class="btn btn-danger btn-sm" type="submit" data-model="{{ __('laravel-crm::lang.lead') }}"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
                    </form>
                @endcan

            @endslot

        @endcomponent

        <form method="POST" action="{{ url(route('laravel-crm.leads.update', $lead)) }}" >

            @csrf
            @method('PUT')

            @component('laravel-crm::components.card-body')

                <div class="row">
                    <div class="col-8" style="border-right: 1px gainsboro solid">
                        @include('laravel-crm::leads.partials.fields', ['generateTitle' => false])
                    </div>

                    <div class="col-4">
                        @include('laravel-crm::partials.activities', ['model' => $lead])
                    </div>
                </div>

            @endcomponent

            @component('laravel-crm::components.card-footer')
                <a href="{{ url(route('laravel-crm.leads.index')) }}" class="btn btn-outline-secondary" style="margin: 0px 5px 0px 5px !important;">
                    {{ ucfirst(__('laravel-crm::lang.cancel')) }}
                </a>
                <button type="submit" class="btn btn-primary">{{ ucwords(__('laravel-crm::lang.save_changes')) }}</button>
            @endcomponent

        </form>

@endcomponent

