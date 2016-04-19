@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$user->name}}</div>

                <div class="panel-body">

                    <form role="form" method="post" action="{{action('UserController@update', compact('user'))}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="action" value="modUser">
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
                            <button type="submit" class="btn btn-default">Submit</button>


                        </form>


                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Notification Preferences</div>

                <div class="panel-body">

                    <p>Note that your permissions in a specific team will override the settings below.
                        For example, if you enable Submissions to Score for Judges, you will only receive e-mails
                        if you are actually a judge in that team.</p>

                    <form role="form" method="post" action="{{action('UserController@update', compact('user'))}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="action" value="modNotifications">

                        <div class="form-group">
                            <label for="notify_new_subs">New Unmoderated Submissions (Daily)</label>
                            <input name="notify_new_subs" id="notify_new_subs" class="form-control" type="checkbox" value="1">
                        </div>

                        <div class="form-group">
                            <label for="notify_must_score">Submissions to Score for Judges (weekly)</label>
                            <input name="notify_must_score" id="notify_must_score" class="form-control" type="checkbox" value="1">
                        </div>

                        <div class="form-group">
                            <label for="notify_scoring_complete">All Judges' Scores Received (as needed)</label>
                            <input name="notify_scoring_complete" id="notify_scoring_complete" class="form-control" type="checkbox" value="1">
                        </div>

                        <button type="submit" class="btn btn-default">Save</button>


                    </form>


                </div>
            </div>

            <div class="panel panel-default">

                <div class="panel-heading">Teams that {{$user->name}} is a member of:</div>

                <div class="panel-body">

                    <!-- this should be a table instead of another panel-->
                    @foreach($user->groups()->get() as $group)
                        <a href="{{action("GroupController@show",compact('group'))}}" class=" list-group-item">{{$group->name}}

                            @if($group->isAdmin($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-briefcase"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                            @if($group->isMod($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-inbox"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                            @if($group->isCreator($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-pencil"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                            @if($group->isJudge($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-star"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                        </a>
                    @endforeach
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
