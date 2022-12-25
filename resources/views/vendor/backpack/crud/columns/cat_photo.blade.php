@php
    use App\Models\Cat;

    /** @var Cat $entry */
    $cat = $entry;
    $url = $cat->photos->isEmpty() ? asset('img/placeholder.png') : $cat->photos[0]["url"];
@endphp

<span>
    @if(empty($url))
        -
    @else
        <a href="{{ $url }}" target="_blank">
            <img src="{{ $url }}" style="max-height: 35px; width: auto; border-radius: 3px;" alt="{{ $cat->name }}" />
        </a>
    @endif
</span>
