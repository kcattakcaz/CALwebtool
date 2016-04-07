@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Submissions for {{$form->name}}</div>

                <div class="panel-body">
                    <div class="list-group">
                        @foreach($submissions as $submission)
                            <a href="{{action('SubmissionController@show',compact('submission'))}}" class="list-group-item">{{$submission->id}} {{$submission->name}} | {{$submission->email}} <strong class="pull-right">{{$submission->status}}</strong></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
