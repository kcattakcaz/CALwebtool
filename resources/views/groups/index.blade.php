@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Group Management</div>

                <div class="panel-body">
                    <p>
                        You can create, edit, and delete groups from here.  You may also add or remove users from a group.
                    </p>

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
