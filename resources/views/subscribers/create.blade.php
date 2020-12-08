
@extends('layouts.app')


@section('content')

    <h2>Add Subscriber</h2>

    <form action="/subscribers" method="post">

        {{csrf_field()}}

        <div class="form-group">
            <label for="name">Имя:</label>
            <input class="form-control" type="text" required name="name" value="" />
        </div>

        <div class="form-group">
            <label for="lastname">Фамилия:</label>
            <input class="form-control" type="text" required name="lastname" />
        </div>

        <div class="form-group">
            <label for="email">Эл.почта:</label>
            <input class="form-control" type="text" required name="email" />
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Отправить</button>
        </div>

        @if(session()->has('message'))
            <div class="alert alert-success">
                {{ session()->get('message') }}
            </div>
        @endif

        @include('layouts.error')

    </form>

@endsection