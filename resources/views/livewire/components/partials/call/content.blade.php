@if($editMode)
    <form wire:submit.prevent="update">
        @include('laravel-crm::livewire.components.partials.call.form-fields')
        <div class="form-group">
            <button type="button" class="btn btn-outline-secondary" wire:click="toggleEditMode()">{{ ucfirst(__('laravel-crm::lang.cancel')) }}</button>
            <button type="submit" class="btn btn-primary">{{ ucfirst(__('laravel-crm::lang.save')) }}</button>
        </div>
    </form>
@else
    {!! $call->description !!}
    <br />

    <span class="badge badge-secondary">{{ $call->start_at->format('H:i') }} на {{ $call->start_at->translatedFormat('j M Y') }}</span>
     к
    <span class="badge badge-secondary">{{ $call->finish_at->format('H:i') }} на {{ $call->finish_at->translatedFormat('j M Y') }}</span>

    @if($call->contacts->count() > 0)

        <hr /><h6><strong> Клиенты </strong></h6>

        @foreach($call->contacts as $contact)
            <span class="fa fa-user mr-1" aria-hidden="true"></span> <a href="{{ route('laravel-crm.people.show', $contact->entityable) }}">{{ $contact->entityable->name }}</a><br />
        @endforeach

    @endif
    @if($call->location)

        <hr /><h6><strong> Расположение </strong></h6>

        {{ $call->location }}

    @endif
    @if($call->location)

        <hr /> <h6><strong> Описание </strong></h6>

        {{ $call->description }}

    @endif
@endif
