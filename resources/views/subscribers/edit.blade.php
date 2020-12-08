@extends('layouts.app')

@section('content')

    <h2>Edit Subscriber</h2>

    <form action="/subscribers/{{$subscribe->id}}" method="post">

        {{csrf_field()}}

        {!! method_field('patch') !!}

        <div class="form-group">
            <label for="name">Имя:</label>
            <input class="form-control" type="text" required name="name" value="{{ $subscribe->name }}" />
        </div>

        <div class="form-group">
            <label for="lastname">Фамилия:</label>
            <input class="form-control" type="text" required name="lastname" value="{{ $subscribe->lastname }}" />
        </div>

        <div class="form-group">
            <label for="email">Эл.почта:</label>
            <input class="form-control" type="text" required name="email" value="{{ $subscribe->email }}" />
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Сохранить</button>
        </div>


        @include('layouts.error')

    </form>

@endsection