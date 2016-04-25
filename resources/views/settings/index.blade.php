@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <!--<div class="panel panel-default">
                <div class="panel-heading">Administrator Settings</div>

                <div class="panel-body"> -->

                    <h1>Settings</h1>

                   <p>
                        You are logged in as a System Administrator.  You can add, delete, or edit teams, members, forms, ballots
                        and everything else related to the application.  With great power comes great responsibility.  Be careful!
                   </p>

                    <a href="{{action('GroupController@index')}}" class="list-group-item">Team Management <span class="pull-right glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                    <a href="{{action('UserController@index')}}" class="list-group-item">User Management <span class="pull-right glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                    <a href="{{action('HomeController@unavailable')}}" class="list-group-item">Audit and Log Data (Disabled)<span class="pull-right glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                    <a href="{{action('HomeController@unavailable')}}" class="list-group-item">System Maintenance (Disabled)<span class="pull-right glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                    <a href="{{action('HomeController@unavailable')}}" class="list-group-item">Backup and Restore (Disabled)<span class="pull-right glyphicon glyphicon-chevron-right" aria-hidden="true"></span></a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
