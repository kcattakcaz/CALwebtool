@extends('layouts.app')

@section('content')
    <div class="container-fluid">


        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <h1>
                    @if($time == "morning")
                        Good morning
                    @elseif($time == "afternoon")
                        Good afternoon
                    @elseif($time == "evening")
                        Good evening
                    @endif

                    <small>{{$user->name}}</small></h1>
            </div>
        </div>


        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    @foreach($groups as $index=>$group)
                        @if($index == 0)
                            <li role="presentation" class="active"><a href="#{{$group->id}}" aria-controls="home" role="tab" data-toggle="tab"><strong>{{$group->name}}</strong></a></li>
                        @else
                            <li role="presentation"><a href="#{{$group->id}}" aria-controls="profile" role="tab" data-toggle="tab"><strong>{{$group->name}}</strong></a></li>
                        @endif
                    @endforeach

                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    @foreach($groups as $index=>$group)
                        @if($index==0)
                            @if($group->isAdmin(Auth::user()->id))
                                @include('dashboard.teamadmin',["active"=>true])
                            @elseif($group->isJudge(Auth::user()->id))
                                @include('dashboard.judge',["active"=>true])
                            @else
                                <div role="tabpanel" class="tab-pane active" id="{{$group->id}}">
                                    <div class="well">
                                        <h4>You're a member of this team, but you don't have any specific role.</h4>
                                    </div>
                                </div>
                            @endif

                        @else
                            @if($group->isAdmin(Auth::user()->id))
                                @include('dashboard.teamadmin',["active"=>false])
                            @elseif($group->isJudge(Auth::user()->id))
                                @include('dashboard.judge',["active"=>false])
                            @else
                                <div role="tabpanel" class="tab-pane" id="{{$group->id}}">
                                    <div class="well">
                                        <h4>You're a member of this team, but you don't have any specific role.</h4>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                </div>
             </div>

        </div>


        {{--@if($judge_groups->count() > 0)
            @include('dashboard.judge')
        @endif
        --}}

    </div>
@endsection
