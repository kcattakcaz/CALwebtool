@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="panel panel-default">
                <div class="panel-heading">{{$user->name}}'s Profile</div>

                <div class="tab-content">
                <div class="panel-body well">

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
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Notification Preferences</div>

                <div class="tab-content">
                <div class="panel-body well">

                    <p>Note that your permissions in a specific team will override the settings below.
                        For example, if you enable Submissions to Score for Judges, you will only receive e-mails
                        if you are actually a judge in that team.</p>

                    <form role="form" method="post" action="{{action('UserController@update', compact('user'))}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="action" value="modNotifications">

                        <div class="checkbox">
                            <label for="notify_new_subs"> <input name="notify_new_subs" id="notify_new_subs" class="" type="checkbox" value="1">New Submissions for Moderators and Admins (Daily)</label>

                        </div>

                        <div class="checkbox">
                            <label for="notify_must_score"> <input name="notify_must_score" id="notify_must_score" class="" type="checkbox" value="1">Submissions Ready for Judges (Weekly)</label>

                        </div>

                        <div class="checkbox">
                            <label for="notify_accept_reject"><input name="notify_accept_reject" id="notify_accept_reject" class="" type="checkbox" value="1">Final Approval / Rejection for Admins (As Needed) </label>
                        </div>

                        <div class="checkbox">
                            <label for="notify_scoring_complete"><input name="notify_scoring_complete" id="notify_scoring_complete" class="" type="checkbox" value="1">Judging Complete for Judges and Admins (As Needed)</label>

                        </div>

                        <button type="submit" class="btn btn-default">Save</button>


                    </form>


                </div>
                </div>
            </div>

            <div class="panel panel-default">

                <div class="panel-heading">Teams that {{$user->name}} is a member of:</div>

                <div class="tab-content">
                <div class="panel-body well">

                    <!-- this should be a table instead of another panel-->
                    @foreach($user->groups()->get() as $group)
                        <a href="{{action("GroupController@show",compact('group'))}}" class=" list-group-item">{{$group->name}}

                            @if($group->isJudge($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-edit"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                            @if($group->isMod($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-pencil"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                            @if($group->isAdmin($user->id))
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-star"> </span>
                            @else
                                <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                            @endif

                        </a>
                    @endforeach
                </div>
                </div>
            </div>


                <div class="panel panel-default">
                    <div class="panel-heading">Advanced User Management</div>

                    <div class="tab-content">
                    <div class="panel-body well">

                        <p>Take EXTREME care with these options</p>

                        <form role="form" method="post" action="{{action('UserController@destroy', compact('user'))}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">

                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">Deactivate Profile</button>
                            </div>

                        </form>

                        <form role="form" method="get" action="{{action('UserController@forceDelete', compact('user'))}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="GET">
                            <input type="hidden" name="user" value="{{$user->id}}">

                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">Force Delete</button>
                            </div>

                        </form>

                        <form role="form" method="post" action="{{action('UserController@destroy', compact('user'))}}">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="DELETE">

                            <div class="form-group">
                                <button type="submit" class="btn btn-danger">Reactivate Profile</button>
                            </div>

                        </form>


                    </div>
                    </div>
                </div>



        </div>
    </div>
</div>
@endsection
