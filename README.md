# Laravel CRUD

- installa laravel `composer create-project laravel/laravel:^9.2 crud_nintendo`
- installa pacchetto `composer require pacificdev/laravel_9_preset`
- esegui comando preset `php artisan preset:ui bootstrap`
- esegui npm `npm i && npm run dev`
- configura db connection

```text
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3307
DB_DATABASE=91_laravel_crud_nintendo
DB_USERNAME=fab
DB_PASSWORD=password
```

- crea db e fai migrazione `php artisan migrate`
- Creo Layout in views/layouts/app.blade.php

```php
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Nintendo @yield('title', 'Welcome')</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    @vite('resources/js/app.js')

</head>

<body>

    @include('partials.header')

    <main class="bg-light">
        @yield('content')
    </main>

    @include('partials.footer')
</body>

</html>

```

estendi layout nelle view

```php
@extends('layouts.app')


@section('content')
<h1>Welcome to nintendo</h1>
@endsection
```

- Crea rotte e page controller per pagine statiche.

```bash
php artisan make:controller PageController
```

creo metodo e poi le rotte

inside the PageController.php

```php
    public function index()
    {
        return view('welcome');
    }

```

crea la rotta

```php

Route::get('/', [PageController::class, 'index']);

```

- oppure creo modello, migrazione, controller, seeder (uso opzione -a) etc

- table: games
- id
- name
- price
- description
- has_demo
- release_date
  
```bash
php artisan make:model Game -a
```

Se vuoi mettere controller nel namespace admin devi spostarlo e creare cartella sootto Controller/

/Http/Controllers/Admin/GameController.php

```php

namespace App\Http\Controllers\Admin; // <--- update namespace

use App\Http\Requests\StoreGameRequest;
use App\Http\Requests\UpdateGameRequest;
use App\Models\Game;
use App\Http\Controllers\Controller; // <--- importa il controller

class GameController extends Controller
{
  // implementiation here ..
}

```

prapara la tabella del db + seeder

```php
 public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->text('image')->nullable();
            $table->float('price', unsigned: true)->nullable();
            $table->text('description')->nullable();
            $table->string('platform')->default('nintendo switch');
            $table->boolean('has_demo')->default(false);
            $table->date('release_date')->nullable();
            $table->timestamps();
        });
    }

```

prepara il seeder

```php

    public function run()
    {
        $games = config('db.games'); // < -- crea prima i dati nel file config/db.php

        foreach ($games as $game) {
            $newGame = new Game();
            $newGame->name = $game['name'];
            $newGame->image = $game['image'];
            $newGame->description = $game['description'];
            $newGame->price = $game['price'];
            $newGame->release_date = $game['release_date'];
            $newGame->platform = $game['platform'];
            $newGame->has_demo = $game['has_demo'];
            $newGame->save();
        }
    }

```

Migra la tabella e seeda il db

```bash

php artisan migrate
php artisan db:seed --class=GameSeeder

```

Crea Rotte di tipo resource per le 7 ops crud
web.php

```php
Route::resource('admin/games', GameController::class);
```

- implementa metodo index del GameController e recupera i dati
- crea la view per il metodo index del GameController
- stampa nella view i games

```php
    public function index()
    {
        //dd(Game::all());
        $games = Game::orderByDesc('id')->get();
        return view('admin.games.index', compact('games'));
    }

```

Ricorda di importare il modello  all'inizio del file `use App\Models\Game;`

Cra view admin/games/index.blade.php e usa forelse per iterare tra i records

```php

@extends('layouts.app')

@section('content')
<div class="container">

    @if (session('message'))
    <div class="alert alert-primary" role="alert">
        <strong>{{session('message')}}</strong>
    </div>

    @endif

    <h1>Games home</h1>


    <a class="btn btn-primary" href="{{route('admin.games.create')}}" role="button">Create</a>

    <div class="table-responsive">
        <table class="table table-secondary">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">IMG</th>
                    <th scope="col">NAME</th>
                    <th scope="col">ACTIONS</th>
                </tr>
            </thead>
            <tbody>

                @forelse ($games as $game)
                <tr>
                    <td scope="row">{{$game->id}}</td>
                    <td>
                        <img width="100" class="img-fluid" src="{{$game->image}}" alt="">
                    </td>
                    <td>{{$game->name}}</td>
                    <td>

                        <a class="btn btn-primary" href="{{route('admin.games.show', $game->id )}}" role="button">View</a>
                        <a class="btn btn-secondary" href="{{route('admin.games.edit', $game->id )}}" role="button">Edit</a>

                        /* Attenzione manca modale */
                        <form action="{{route('admin.games.destroy', $game->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>

                    </td>

                </tr>

                @empty
                <tr class="">
                    <td>No results</td>
                </tr>
                @endforelse


            </tbody>
        </table>
    </div>


</div>



@endsection
```

Implementa metodo create nel controller

```php
 /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.games.create');
    }
```

Crea view per la creazione di una risorsa

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Game create</h1>
    /* Occhio alla rotta, qui ci va il nome della rota non l'uri */
    <form action="{{route('admin.games.store')}}" method="post">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Zelda" aria-describedby="nameHelper">
            <small id="nameHelper" class="text-muted">Type the game name max: 200 characters</small>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="text" name="image" id="image" class="form-control" placeholder="https://" aria-describedby="imageHelper">
            <small id="imageHelper" class="text-muted">Type the game image max: 200 characters</small>
        </div>
        <div class="mb-3">
            <label for="platform" class="form-label">Platform</label>
            <input type="text" name="platform" id="platform" class="form-control" placeholder="Nintendo Switch" aria-describedby="platformHelper">
            <small id="platformHelper" class="text-muted">Type the game platform max: 200 characters</small>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="99.99" aria-describedby="priceHelper">
            <small id="priceHelper" class="text-muted">Type the game price max: 200 characters</small>
        </div>

        <div class="mb-3">
            <label for="release_date" class="form-label">release_date</label>
            <input type="date" name="release_date" id="release_date" class="form-control" placeholder="Zelda" aria-describedby="release_dateHelper">
            <small id="release_dateHelper" class="text-muted">Type the game release_date max: 200 characters</small>
        </div>


        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="has_demo" name="has_demo">
            <label class="form-check-label" for="has_demo">
                Demo avalable
            </label>
        </div>


        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="5"></textarea>
        </div>


        <button type="submit" class="btn btn-primary">Add Game</button>
    </form>

</div>
@endsection
```

- implementa metodo store

```php
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

```

Nota:
Unsando Game::create($data); ci si imbatte nell'errore causato dalle fillable properties. Settatele nel modello.

Nota:
->with aggiunge alla session una chiave message ed un valore che si puó recuperare nella view usando @if(session('message')) {{$session('message')}} @endif

Nota:
nel metodo store se si genera il controller con il modello usando `-a` le richieste dei form vengono gestite da due classi dedicate. nelle queli autorize deve restituire true.

```php
    public function authorize()
    {
        return true;
    }

```

- Rotta Show

```php
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

```

Mostra la view per la singola risorsa

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <img class="img-fluid" src="{{$game->image}}" alt="">
    <h1>{{$game->name}}</h1>
    <p>
        {{$game->description}}

    </p>
</div>


@endsection


```

Editiamop  una risorsa esistente
il metodo del controller restituisce una view a cui passiamo con compact il modello da visualizzare in pagina.

```php

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
```

prepara la view EDIT

Gestiamo l'invio del form di editing puntando alla rotta update e sovrascrivendo il metodo POST in PUT

```html
<form action="{{route('admin.games.update', $game->id)}}" method="post">
        @csrf
        @method('PUT')
 <!-- .... -->
</form>
```

aggiungi i campi precompilando i `value=''` dei vari input.

```php
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Game {{$game->name}} Editing</h1>

    <form action="{{route('admin.games.update', $game->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" placeholder="Zelda" aria-describedby="nameHelper" value="{{$game->name}}">
            <small id="nameHelper" class="text-muted">Type the game name max: 200 characters</small>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input type="text" name="image" id="image" class="form-control" placeholder="https://" aria-describedby="imageHelper" value="{{$game->image}}">
            <small id="imageHelper" class="text-muted">Type the game image max: 200 characters</small>
        </div>
        <div class="mb-3">
            <label for="platform" class="form-label">Platform</label>
            <input type="text" name="platform" id="platform" class="form-control" placeholder="Nintendo Switch" aria-describedby="platformHelper" value="{{$game->platform}}">
            <small id="platformHelper" class="text-muted">Type the game platform max: 200 characters</small>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" placeholder="99.99" aria-describedby="priceHelper" value="{{$game->price}}">
            <small id="priceHelper" class="text-muted">Type the game price max: 200 characters</small>
        </div>

        <div class="mb-3">
            <label for="release_date" class="form-label">release_date</label>
            <input type="date" name="release_date" id="release_date" class="form-control" placeholder="Zelda" aria-describedby="release_dateHelper" value="{{$game->release_date}}">
            <small id="release_dateHelper" class="text-muted">Type the game release_date max: 200 characters</small>
        </div>


        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="has_demo" name="has_demo" value="{{$game->has_demo}}">
            <label class="form-check-label" for="has_demo">
                Demo avalable
            </label>
        </div>


        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" name="description" id="description" rows="5">{{$game->description}}</textarea>
        </div>


        <button type="submit" class="btn btn-primary">Add Game</button>
    </form>

</div>


@endsection
```

Lasciamo gestire al metodo update del controller la rishiesta di aggiurnamento

```php
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
```

NOTA: occhio, ricorda di settare in UpodateGameRequest authorize su true.

Cancelliamo un record aggiungendo un form di cancellazione e puntando alla rotta destroy che poi sará gestita dal metodo destroy nel controller.

Puoi aggiungere un pulsante con un form alla view index.blade.php dei games per poter rimuovere una risorsa`

```html

<form action="{{route('admin.games.destroy', $game->id)}}" method="post">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">Delete</button>
</form>

```

Ora gestisci la cancellazione

```php
    public function destroy(Game $game)
    {
        $game->delete();
        return to_route('admin.games.index')->with('message', 'game deleted');
    }

```
