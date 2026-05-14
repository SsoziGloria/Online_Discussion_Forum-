@props(['value'])

<label {{ $attributes->merge(['class' => 'forum-label']) }}>
    {{ $value ?? $slot }}
</label>
