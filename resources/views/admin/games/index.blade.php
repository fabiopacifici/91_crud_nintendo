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