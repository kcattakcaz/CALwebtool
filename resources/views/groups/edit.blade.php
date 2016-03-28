@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                    <form role="form" method="post" action="{{action('GroupController@update', compact('group'))}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH">

                    <div class="panel-heading">{{$group->name}}</div>

                    <div class="panel-body">

                        <div class="btn-group pull-right" role="group" aria-label="...">

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
                            <!--
                            <li><em>No Permissions-</em>This user can view group data but cannot modify it.
                                <span class="pull-right glyphicon glyphicon-user"> </span></li>
                            -->
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

                            <div class="panel panel-default">
                                <div class="panel-heading">{{$user->name}}</div>
                                <div class="panel-body">

                                    <label for="{{$user->id}}-administrator">
                                        <span style="padding-left:10px" class="glyphicon glyphicon-briefcase">: </span>
                                    </label>
                                    @if($group->isAdmin($user->id))
                                        <input type="checkbox" name="{{$user->id}}-administrator" id="{{$user->id}}-administrator checked="true"/>
                                    @else
                                        <input type="checkbox" name="{{$user->id}}-administrator" id="{{$user->id}}-administrator"/>
                                    @endif

                                    <label for="{{$user->id}}-moderator">
                                        <span style="padding-left:10px" class=" glyphicon glyphicon-inbox">: </span>
                                    </label>
                                    @if ($group->isMod($user->id))
                                        <input type="checkbox" name="{{$user->id}}-moderator" id="{{$user->id}}-moderator" checked="true"/>
 a                                  @else
                                        <input type="checkbox" name="{{$user->id}}-moderator" id="{{$user->id}}-moderator"/>
                                    @endif

                                    <label for="{{$user->id}}-creator">
                                        <span style="padding-left:10px" class=" glyphicon glyphicon-pencil">: </span>
                                    </label>
                                    @if ($group->isCreator($user->id))
                                        <input type="checkbox" name="{{$user->id}}-creator" id="{{$user->id}}-creator" checked="true"/>
                                    @else
                                        <input type="checkbox" name="{{$user->id}}-creator" id="{{$user->id}}-creator"/>
                                    @endif

                                    <label for="{{$user->id}}-adjudicator">
                                        <span style="padding-left:10px" class=" glyphicon glyphicon-star">: </span>
                                    </label>
                                    @if ($group->isJudge($user->id))
                                        <input type="checkbox" name="{{$user->id}}-adjudicator" id="{{$user->id}}-adjudicator" checked="true"/>
                                    @else
                                        <input type="checkbox" name="{{$user->id}}-adjudicator" id="{{$user->id}}-adjudicator"/>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        </p>

                    </div>
                        <div class="form-group">
                            <label for="new_group_users">Add group members:</label>
                            <select multiple="multiple" name="new_group_users[]" class="form-control" id="new_group_users">
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default pull-right">Save Changes</button>

                    </form>


                </div>
            </div>
        </div>
    </div>
    </div>
@endsection