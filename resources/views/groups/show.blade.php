@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$group->name}}</div>

                <div class="panel-body">

                    <div class="btn-group pull-right" role="group" aria-label="...">

                        <a href="{{action('GroupController@edit',compact('group'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Edit Team
                            </button>
                        </a>
                    </div>
                    <p>
                        {{$group->description}}
                    </p>
                    <br>
                    <p>
                        Members can have one or more of the following permissions: Administrator, Moderator, Creator, Adjudicator,
                        or no permissions at all.
                        <ul style="list-style-type:none">
                            <li>
                                <span class="glyphicon glyphicon-check"> </span>
                                <em> - Moderator-</em>Allows the user to approve/reject submissions
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-pencil"> </span>
                                <em> - Creator-</em>Allows the user to create/modify/delete forms
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-edit"> </span>
                                <em> - Adjudicator-</em>Allows the user to score submissions
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-star"> </span>
                                <em> - Administrator-</em>Provides user with all permissions above, and also the ability
                                to add/remove users and modify permissions of other users

                            </li>

                        </ul>

                    </p>

                    <div class="list-group">
                        <p>
                            <strong>Team Members</strong>

                        </p>
                        <!--
                        <div class="">
                            <div class="input-group">
                                  <input type="text" class="form-control" placeholder="Search for...">
                                  <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">Search</button>
                                  </span>
                                <div class="input-group-btn">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Sort <span class="caret"></span></button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="#">By Name</a></li>
                                        <li><a href="#">By Date Added</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a href="#">Show Admins Only</a></li>
                                    </ul>
                                </div>
                            </div>
                            </div>
                        </div>
                        -->

                        <p>
                            @foreach($group->users()->get() as $user)
                                <a href="{{action("UserController@show",compact('user'))}}" class=" list-group-item">{{$user->name}}

                                    @if($group->isAdmin($user->id))
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-star"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                    @if($group->isMod($user->id))
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-check"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                    @if($group->isCreator($user->id))
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-pencil"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                    @if($group->isJudge($user->id))
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-edit"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                </a>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
