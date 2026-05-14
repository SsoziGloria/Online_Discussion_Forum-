@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'forum-status-success']) }}>
        {{ $status }}
    </div>
@endif
