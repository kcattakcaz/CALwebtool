@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">HEY GUYS WHATS GOING ON THIS PAGE?!?!?!?</div>

                <div class="panel-body">
                    @if(Auth::check())
                        You are logged in!
                    @else
                        <strong>Please login to view your dashboard.</strong>

                        <p>
                            This site is for CAL staff, alumni committee and other authorized users, if you are a student
                            looking to fill out an application, please return to your college's home page, and follow a link
                            directly.
                        </p>

                        <p>
                            If you are having difficulty accessing your account, contact your group administrator.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
