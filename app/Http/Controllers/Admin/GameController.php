<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Http\Controllers\Controller;

//use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //dd(Game::all());
        $games = Game::orderByDesc('id')->get();
        return view('admin.games.index', compact('games'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.games.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGameRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGameRequest $request)
    {
        //dd($request->all());
        $data = [
            'name' => $request->name,
            'image' => $request->image,
            'platform' => $request->platform,
            'price' => $request->price,
            'release_date' => $request->release_date,
            'has_demo' => $request->has_demo,
            'description' => $request->description
        ];


        Game::create($data);

        return to_route('admin.games.index')->with('message', 'game added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        return view('admin.games.show', compact('game'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        return view('admin.games.edit', compact('game'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGameRequest  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGameRequest $request, Game $game)
    {
        //dd($request->all());
        /* TODO: check has demo not updating */
        $data = [
            'name' => $request->name,
            'image' => $request->image,
            'platform' => $request->platform,
            'price' => $request->price,
            'release_date' => $request->release_date,
            'has_demo' => $request->has_demo ? 1 : 0,
            'description' => $request->description
        ];
        $game->update($data);

        return to_route('admin.games.index')->with('message', 'game updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function destroy(Game $game)
    {
        $game->delete();
        return to_route('admin.games.index')->with('message', 'game deleted');
    }
}
