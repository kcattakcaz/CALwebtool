@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Group Management

                </div>

                <div class="panel-body">


                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <a href="{{action('GroupController@create')}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Group
                            </button>
                        </a>
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help
                        </button>
                    </div>

                    <br>

                    <hr>

                    <div class="list-group">

                        @foreach($groups as $group)
                        <a href="{{action('GroupController@show',['group'=>$group->id])}}" class="list-group-item">{{$group->name}}</a>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
