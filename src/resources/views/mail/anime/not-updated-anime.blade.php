<x-mail::message>
# Some anime failed to update

Here is a list of failed anime and errors:

<x-mail::table>
| Anime | Error |
|-------|-------|
@foreach($failed as $animeId => $reason)
| <a href="{{ route('anime.show', $animeId) }}">{{ $animeId }}</a> | {{ $reason }} |
@endforeach
</x-mail::table>
</x-mail::message>
