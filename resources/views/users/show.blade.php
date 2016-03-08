@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$user->name}}</div>

                <div class="panel-body">
                    <p>
                       Modification of the user profile is not enabled at this time
                    </p>
                </div>
            </div>
            <div class="panel panel-default">

                <div class="panel-heading">Group Membership</div>

                <div class="panel-body">
                    @foreach($user->groups()->get() as $group)

                        <div class="panel panel-default">
                            <div class="panel-heading">{{$group->name}}</div>

                            <div class="panel-body">
                                @if($group->users()->find($user->id)->pivot->administrator)
                                    <p>Group Administrator</P>
                                @endif
                                @if($group->users()->find($user->id)->pivot->moderatorr)
                                    <p>Moderator</P>
                                @endif
                                @if($group->users()->find($user->id)->pivot->creator)
                                    <p>Creator</P>
                                @endif
                                @if($group->users()->find($user->id)->pivot->adjudicator)
                                    <p>Adjudicator</P>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </div>
</div>
@endsection
