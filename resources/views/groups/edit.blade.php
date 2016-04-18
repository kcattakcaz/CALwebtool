@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">

                   {{--<!-- <form role="form" method="post" action="{{action('GroupController@update', compact('group'))}}"> -->
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PATCH"> --}}

                    <div class="panel-heading">{{$group->name}}</div>

                    <div class="panel-body">

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="btn-group pull-right" role="group" aria-label="...">
                            <a>
                                <button type="button" class="btn btn-default">
                                    <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help
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


                        </p>
                        <ul style="list-style-type:none">
                            <li>
                                <span class="glyphicon glyphicon-inbox"> </span>
                                <em> - Moderator-</em>Allows the user to approve/reject submissions
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-pencil"> </span>
                                <em> - Creator-</em>Allows the user to create/modify/delete forms
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-star"> </span>
                                <em> - Adjudicator-</em>Allows the user to score submissions
                            </li>
                            <li>
                                <span class="glyphicon glyphicon-briefcase"> </span>
                                <em> - Administrator-</em>Provides user with all permissions above, and also the ability
                                to add/remove users and modify permissions of other users

                            </li>

                        </ul>


                        <div class="list-group">
                            <p>
                                <strong>Group Members</strong>

                            </p>

                            <div class="">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Search</button>
                                        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal_add_user">Add User</button>
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


                        @foreach($group->users()->get() as $user)
                            @if($group->isAdmin($user->id))
                                    <div id="panel_{{$user->id}}" class="panel panel-info" data-user-is-admin="true" data-user-name="{{$user->name}}" data-user-email="{{$user->email}}" data-join-date="{{$group->users()->find($user->id)->pivot->created_at}}">
                                        <div class="panel-heading">{{$user->name}} (Admin)</div>
                            @else
                                <div id="panel_{{$user->id}}" class="panel panel-default" data-user-is-admin="false" data-user-name="{{$user->name}}" data-user-email="{{$user->email}}" data-join-date="{{$group->users()->find($user->id)->pivot->created_at}}">
                                    <div class="panel-heading">{{$user->name}}</div>
                            @endif
                                <div class="panel-body">

                                    <form style="display: inline-block" method="post" action="{{action("GroupController@update",compact('group'))}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="action" value="modMember">
                                        <input type="hidden" name="user_id" value="{{$user->id}}">

                                        <label for="{{$user->id}}-administrator">
                                            <span style="padding-left:10px" class="glyphicon glyphicon-briefcase">: </span>
                                        </label>
                                        @if($group->isAdmin($user->id))
                                            <input type="checkbox" name="administrator" id="{{$user->id}}-administrator" checked/>
                                        @else
                                            <input type="checkbox" name="administrator" id="{{$user->id}}-administrator"/>
                                        @endif

                                        <label for="{{$user->id}}-moderator">
                                            <span style="padding-left:10px" class=" glyphicon glyphicon-inbox">: </span>
                                        </label>
                                        @if ($group->isMod($user->id))
                                            <input type="checkbox" name="moderator" id="{{$user->id}}-moderator" checked/>
                                       @else
                                            <input type="checkbox" name="moderator" id="{{$user->id}}-moderator"/>
                                        @endif

                                        <label for="{{$user->id}}-creator">
                                            <span style="padding-left:10px" class=" glyphicon glyphicon-pencil">: </span>
                                        </label>
                                        @if ($group->isCreator($user->id))
                                            <input type="checkbox" name="creator" id="{{$user->id}}-creator" checked/>
                                        @else
                                            <input type="checkbox" name="creator" id="{{$user->id}}-creator"/>
                                        @endif

                                        <label for="{{$user->id}}-adjudicator">
                                            <span style="padding-left:10px" class=" glyphicon glyphicon-star">: </span>
                                        </label>
                                        @if ($group->isJudge($user->id))
                                            <input type="checkbox" name="adjudicator" id="{{$user->id}}-adjudicator" checked/>
                                        @else
                                            <input type="checkbox" name="adjudicator" id="{{$user->id}}-adjudicator"/>
                                        @endif

                                        <button type="submit" class="btn btn-default pull-left" id="btn_userSave_{{$user->id}}" name="btn_userSave">
                                            <span class="glyphicon glyphicon-save"></span> Save
                                        </button>
                                    </form>

                                    <form style="display:inline-block;" class="pull-right" method="post" action="{{action("GroupController@update",compact('group'))}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="PATCH">
                                        <input type="hidden" name="action" value="delMember">
                                        <input type="hidden" name="user_id" value="{{$user->id}}">
                                        <button id="btn_userDelete_{{$user->id}}" name="btn_userDelete_{{$user->id}}" type="submit" class="btn btn-default pull-right">
                                            <span class="glyphicon glyphicon-erase" aria-hidden="true"></span> Remove
                                        </button>
                                    </form>

                                    <script>

                                        $(document).ready(function(){
                                            if($("#{{$user->id}}-administrator").prop('checked')){
                                                $("#{{$user->id}}-creator").prop('checked',true).prop('disabled',true);
                                                $("#{{$user->id}}-moderator").prop('checked',true).prop('disabled',true);
                                                $("#{{$user->id}}-adjudicator").prop('checked',true).prop('disabled',true);
                                            }
                                        });

                                        $("#{{$user->id}}-administrator").on('change',function(){
                                            if($(this).prop('checked')){
                                                $("#{{$user->id}}-creator").prop('checked',true).prop('disabled',true);
                                                $("#{{$user->id}}-moderator").prop('checked',true).prop('disabled',true);
                                                $("#{{$user->id}}-adjudicator").prop('checked',true).prop('disabled',true);
                                            }
                                            else{
                                                $("#{{$user->id}}-creator").prop('checked',false).prop('disabled',false);
                                                $("#{{$user->id}}-moderator").prop('checked',false).prop('disabled',false);
                                                $("#{{$user->id}}-adjudicator").prop('checked',false).prop('disabled',false);

                                            }
                                        });
                                    </script>


                                    {{--
                                    <script>
                                        $('#btn_userDelete_{{$user->id}}').on('click',function(){
                                            $.ajax({
                                                url:"{!!action('GroupController@rupdate', compact('group', 'user'))!!}",
                                                headers:{'X-CSRF-TOKEN':"{{csrf_token()}}"},
                                                method:"DELETE"
                                            }).done(function(textStatus,jqXHR){
                                                location.reload();
                                            }).fail(function (jqXHR,textStatus,errorThrown) {
                                                console.log("Error:"+errorThrown);
                                            })
                                        })
                                    </script> --}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>



                {{--Add User Modal--}}
                <div class="modal fade" id="modal_add_user" role="dialog">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div clas="container-fluid">
                                <form method="post" action="{{action('GroupController@update',compact('group'))}}">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Add User</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="action" value="addMember">
                                        <input type="hidden" name="_method" value="PATCH">
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <label for="userlist">User:</label>
                                            <select id="userlist" name="user_id">
                                                @foreach($users as $user)
                                                    <option value="{{$user->id}}">{{$user->name}} ({{$user->email}})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">Administrator:</label>
                                            <input name="administrator" id="useradd_admin" type="checkbox" value="1">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">Creator:</label>
                                            <input name="creator" id="useradd_creator" type="checkbox" value="1">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">Moderator:</label>
                                            <input name="moderator" id="useradd_moderator" type="checkbox" value="1">
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-2">Judge:</label>
                                            <input name="adjudicator" id="useradd_adjudicator" type="checkbox" value="1">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Add</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        $("#userlist").selectize({
            create: false,
            highlight: true,
            sortField: 'text'
        });


        $("#useradd_admin").on('change',function(){
            if($(this).prop('checked')){
                $("#useradd_creator").prop('checked',true).prop('disabled',true);
                $("#useradd_moderator").prop('checked',true).prop('disabled',true);
                $("#useradd_adjudicator").prop('checked',true).prop('disabled',true);
            }
            else{
                $("#useradd_creator").prop('checked',false).prop('disabled',false);
                $("#useradd_moderator").prop('checked',false).prop('disabled',false);
                $("#useradd_adjudicator").prop('checked',false).prop('disabled',false);

            }
        });
    </script>

@endsection