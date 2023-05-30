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