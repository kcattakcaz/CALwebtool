@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$group->name}}</div>

                <div class="panel-body">

                    <div class="btn-group pull-right" role="group" aria-label="...">

                        <a href="{{action('GroupController@update',compact('group'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add User
                            </button>
                        </a>

                       <a> <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help
                        </button> </a>




                    </div>

                    <p>

                        {{$group->description}}
                    </p>

                    <br>

                    <p>
                        Members can have one or more of the following permissions: Moderator, Creator, Adjudicator,
                        or no permissions at all.
                        <ul>
                            <li><em>No Permissions-</em>This user can view group data but cannot modify it.
                                <span class="pull-right glyphicon glyphicon-user"> </span></li>
                            <li><em>Moderator-</em>Allows the user to approve/reject submissions
                                <span class="pull-right glyphicon glyphicon-inbox"> </span></li>
                            <li><em>Creator-</em>Allows the user to create/modify/delete forms
                                <span class="pull-right glyphicon glyphicon-pencil"> </span></li>
                            <li><em>Adjudicator-</em>Allows the user to score submissions
                                <span class="pull-right glyphicon glyphicon-star"> </span></li>
                            <li><em>Administrator-</em>Provides user with all permissions above, and also the ability
                                to add/remove users and modify permissions of other users
                                <span class="pull-right glyphicon glyphicon-briefcase"> </span></li>

                        </ul>

                    </p>

                    <div class="list-group">
                        <p>
                            <strong>Group Members</strong>

                        </p>

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
                                </div><!-- /btn-group -->
                            </div><!-- /input-group -->
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->

                        <p>
                            @foreach($group->users()->get() as $user)
                                <a href="#" class=" list-group-item">{{$user->name}}

                                    @if($group->users()->find($user->id)->pivot->administrator)
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-briefcase"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                    @if($group->users()->find($user->id)->pivot->moderator)
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-inbox"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                    @if($group->users()->find($user->id)->pivot->creator)
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-pencil"> </span>
                                    @else
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-minus"> </span>
                                    @endif

                                    @if($group->users()->find($user->id)->pivot->adjudicator)
                                        <span style="padding-left:5px; padding-right: 5px;" class="pull-right glyphicon glyphicon-star"> </span>
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
