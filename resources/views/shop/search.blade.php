<form method="get" action="/shop" id="searchForm">
    <div class="row">
        <div class="col-sm-6 mb-2">
            <input type="text" class="form-control" name="artist" id="artist"
                   value="{{ request()->artist }}"
                   placeholder="Filter Artist Or Record">
        </div>
        <div class="col-sm-4 mb-2">
            <select class="form-control" name="genre_id" id="genre_id">
                <option>Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->id }}">{{ ucfirst($genre->name) }}
                        {{ (request()->genre_id ==  $genre->id ? 'selected' : '') }}>{{ $genre->name }}</option></option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2 mb-2">
            <button type="submit" class="btn btn-success btn-block">Search</button>
        </div>
    </div>
</form>
<hr>
@if ($records->count() == 0)
    <div class="alert alert-danger alert-dismissible fade show">
        Can't find any artist or album with <b>'{{ request()->artist }}'</b> for this genre
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
@endif
