@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$group->name}}</div>

                <div class="panel-body">
                    <p>
                        {{$group->description}}
                    </p>

                    <div class="list-group">

                        <p>
                            <strong>Group Administrators</strong>
                        </p>
                        @foreach($group->administratorUsers()->get() as $user)
                            <a href="#" class=" list-group-item">{{$user->name}}</a>
                        @endforeach
                        <br>
                        <p>
                            <strong>Group Members</strong>
                        </p>

                        @foreach($group->standardUsers()->get() as $user)
                            <a href="#" class=" list-group-item">{{$user->name}}</a>
                        @endforeach
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
