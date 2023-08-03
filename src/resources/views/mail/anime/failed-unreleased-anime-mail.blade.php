<x-mail::message>
# List or anime and errors for failed anime

<ul>
@foreach($failed as $animeId => $reason)
<li>
<a href="{{ route('anime.show', $animeId) }}">Anime</a> failed to update cause of: {{ $reason }}
</li>
@endforeach
</ul>

</x-mail::message>
