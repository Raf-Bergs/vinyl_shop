<?php

namespace App\Http\Controllers;

use App\Genre;
use Http;
use Json;
use App\Record;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Master Page: http://vinyl_shop.test/shop or http://localhost:3000/shop
    public function index(Request $request)
    {

        $genre_id = $request->input('genre_id') ?? '%'; // $request->input('genre_id') OR $request->genre_id OR $request['genre_id'];;
        $artist_title = '%' . $request->input('artist') . '%'; // $request->input('artist') OR $request->artist OR $request['artist'];;

        $records = Record::with('genre')//get all records
            ->where([
                ['artist', 'like', $artist_title],
                ['genre_id', 'like', $genre_id]
            ])
            ->orWhere([
                ['title', 'like', $artist_title],
                ['genre_id', 'like', $genre_id]
            ])
        ->paginate(12);
            // OR ->appends(['artist' => $request->artist, 'genre_id' => $request->genre_id]);
        foreach ($records as $record) {
            $record->badge = $record->stock > 0 ? 'badge-success' : 'badge-danger';
            $record->price = number_format($record->price,2);
            $record->genre->name = ucfirst($record->genre->name);
            if (!$record->cover) {
                $record->cover = $record->cover ?? "https://coverartarchive.org/release/{$record->title_mbid}/front-250.jpg";
            }
        }
        $genres = Genre::orderBy('name')            // short version of orderBy('name', 'asc')
        ->has('records')
        ->withCount('records')
        ->get()
            ->transform(function ($item, $key) {
                // Set first letter of name to uppercase and add the counter
                $item->name = ucfirst($item->name) . ' (' . $item->records_count . ')';
                return $item;
            })
            ->makeHidden(['created_at', 'updated_at', 'records_count']);    // Remove all fields that you don't use inside the view
        $result = compact('genres','records');           // compact('records') is the same as ['records' => $records]
        Json::dump($result);                    // open http://vinyl_shop.test/shop?json
        return view('shop.index', $result);     // add $result as second parameter
        //return $records;
        //return view('shop.index',['records'=>$records]);
    }

    // Detail Page: http://vinyl_shop.test/shop/{id} or http://localhost:3000/shop/{id}
    public function show($id)
    {
        $record = Record::with('genre')->findOrFail($id);
        // dd($record);
// Real path to cover image
        $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-500.jpg";
// Combine artist + title
        $record->title = $record->artist . ' - ' . $record->title;
// Links to MusicBrainz API
// https://wiki.musicbrainz.org/Development/JSON_Web_Service
        $record->recordUrl = 'https://musicbrainz.org/ws/2/release/' . $record->title_mbid . '?inc=recordings+url-rels&fmt=json';
// If stock > 0: button is green, otherwise the button is red
        $record->btnClass = $record->stock > 0 ? 'btn-outline-success' : 'btn-outline-danger';
// You can't overwrite the attribute genre (object) with a string, so we make a new attribute
        $record->genreName = $record->genre->name;
// Hide attributes you don't need for the view
        $record->makeHidden(['genre', 'artist', 'genre_id', 'created_at', 'updated_at', 'title_mbid', 'genre']);

        // get record info and convert it to json
        $response = Http::get($record->recordUrl)->json();
        $tracks = collect($response['media'][0]['tracks'])
            ->transform(function ($item, $key) {
                $item['length'] = date('i:s', $item['length'] / 1000);      // PHP works with sec, not ms!!!
                unset($item['id'], $item['recording'], $item['number']);
                return $item;
            });

        $result = compact('tracks','record');
        Json::dump($result);
        return view('shop.show', $result);  // Pass $result to the view
//        return view('shop.show', ['id' => $id]);  // Send $id to the view
    }
}
