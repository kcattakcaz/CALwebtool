@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$user->name}}</div>

                <div class="panel-body">

                    <p>
                        User modification is unavailable at this time.
                    </p>

                    <form role="form" method="post" action="{{action('UserController@update', compact('user'))}}">
                        {{ csrf_field() }}
                            <div class="form-group">
                                <label for="name">User Name:</label>
                                <input name="name" type="text" class="form-control" id="name" value="{{$user->name}}">
                            </div>

                            <div class="form-group">
                                <label for="email">E-Mail:</label>
                                <input name="email" type="email" class="form-control" id="email" value="{{$user->email}}">
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input name="password" type="password" class="form-control" id="password">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password:</label>
                                <input name="password_confirmation" type="password" class="form-control" id="password_confirmation">
                            </div>

                        <!--
                            <button type="submit" class="btn btn-default">Submit</button>
                        -->

                        </form>


                </div>
            </div>
            <div class="panel panel-default">

                <div class="panel-heading">Groups that {{$user->name}} is a member of:</div>

                <div class="panel-body">

                    <!-- this should be a table instead of another panel-->
                    @foreach($user->groups()->get() as $group)

                        <div class="panel panel-default">
                            <div class="panel-heading">{{$group->name}}</div>

                            <div class="panel-body">
                                @if($group->users()->find($user->id)->pivot->administrator)
                                    <p>Group Administrator</p>
                                @endif
                                @if($group->users()->find($user->id)->pivot->moderator)
                                    <p>Moderator</p>
                                @endif
                                @if($group->users()->find($user->id)->pivot->creator)
                                    <p>Creator</p>
                                @endif
                                @if($group->users()->find($user->id)->pivot->adjudicator)
                                    <p>Adjudicator</p>
                                @endif
                            </div>

                            <!--
                            CHANGE GROUP PERMISSIONS HERE
                            -->

                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
