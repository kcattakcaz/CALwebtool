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

                    <ul class="list-group">

                        @foreach($users as $user)
                            <li class="list-group-item">
                                {{$user->name}}
                                <a style="margin-left:5px;" href="{{action('UserController@forceDelete',compact('user'))}}" class="pull-right"><button class="btn btn-sm btn-danger">Force Delete</button></a>
                                <a href="{{action('UserController@reactivate',compact('user'))}}" class="pull-right"><button class="btn btn-sm btn-primary">Reactivate</button></a>
                                <div class="clearfix"></div>
                            </li>
                        {{--<a href="{{action('UserController@show',['user'=>$user->id])}}" class="list-group-item">{{$user->name}}</a> --}}
                        @endforeach
                    </ul>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection