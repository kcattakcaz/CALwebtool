@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if(Auth::check())
                        Teams that you are a member of:
                        <div class="panel-body">
                            <table class="table table-striped">
                                <thead class="thead">
                                <tr>
                                    <th>Team Name</th>
                                    <th>Role in team.</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user->groups()->get() as $group)
                                    <tr>
                                        <td>{{$group->name}}</td>
                                        <td>{{$group->users()->find($user->id)->pivot->administrator}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <strong>Please login to view your dashboard, buddy.</strong>

                        <p>
                            This site is for CAL staff, alumni committee and other authorized users, if you are a student
                            looking to fill out an application, please return to your college's home page, and follow a link
                            directly.
                        </p>

                        <p>
                            If you are having difficulty accessing your account, contact your team administrator.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection