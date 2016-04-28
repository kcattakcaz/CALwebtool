@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">User Management

                </div>

                <div class="tab-content">
                <div class="panel-body well">


                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <a href="{{action('UserController@create')}}"<button type="button" class="btn btn-primary">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New User
                        </button>
                        </a>
                        <a href="{{action('UserController@deactivatedIndex')}}"<button type="button" class="btn btn-warning">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Deactivated Users
                        </button>
                        </a>
                    </div>

                    <br><br>

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
</div>
@endsection
