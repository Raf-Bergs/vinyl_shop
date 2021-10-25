<?php

namespace App\Http\Controllers;

use App\Genre;
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

        $genres = Genre::orderBy('name')
            ->has('records')
            ->withCount('records')
            ->get()
            ->transform(function($genre,$key){
                $genre->name = ucfirst($genre->name) . "({$genre->records_count})";
                return $genre;
            })->makeHidden(['updated_at','created_at','records_count']);


        $records = Record::with('genre')//get all records
            ->where([
                ['artist', 'like', $artist_title],
                ['genre_id', 'like', $genre_id]
            ])
            ->orWhere([
                ['title', 'like', $artist_title],
                ['genre_id', 'like', $genre_id]
            ])
            ->paginate(12)
            ->appends(['artist'=> $request->input('artist'), 'genre_id' => $request->input('genre_id')]);
            // OR ->appends(['artist' => $request->artist, 'genre_id' => $request->genre_id]);
        foreach ($records as $record) {
            $record->badge = $record->stock > 0 ? 'badge-success' : 'badge-danger';
            $record->price = number_format($record->price,2);
            $record->genre->name = ucfirst($record->genre->name);
            if (!$record->cover) {
                $record->cover = $record->cover ?? "https://coverartarchive.org/release/{$record->title_mbid}/front-250.jpg";
            }
        }
        $result = compact('genres','records');           // compact('records') is the same as ['records' => $records]
        Json::dump($result);                    // open http://vinyl_shop.test/shop?json
        return view('shop.index', $result);     // add $result as second parameter
        //return $records;
        //return view('shop.index',['records'=>$records]);
    }

    // Detail Page: http://vinyl_shop.test/shop/{id} or http://localhost:3000/shop/{id}
    public function show($id)
    {
        return view('shop.show', ['id' => $id]);  // Send $id to the view
    }
}
