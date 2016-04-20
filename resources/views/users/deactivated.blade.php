@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Deactivated User Management

                </div>

                <div class="panel-body">


                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <a href="{{action('UserController@create')}}"<button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New User
                        </button>
                        </a>
                        <a href="{{action('UserController@index')}}"<button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Active Users
                        </button>
                        </a>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help
                        </button>
                    </div>

                    <br>

                    <hr>

                    <div class="list-group">

                        @foreach($users as $user)
                        <a href="{{action('UserController@show',['user'=>$user->id])}}" class="list-group-item">{{$user->name}}</a>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
