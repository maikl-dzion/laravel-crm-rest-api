@if($task->completed_at)
<span class="badge badge-success">Выпонено</span>
@else
<span class="badge badge-primary">В ожидании</span>
@endif
