@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}" class="{{ $active ? 'active' : '' }}">
    {{ $slot }}
</a>
