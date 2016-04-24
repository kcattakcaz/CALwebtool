@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <h1>Unscored Submissions <small>{{$form->name}}</small></h1>

                <div class="list-group">

                        @foreach($submissions as $submission)
                            <a href="{{action('SubmissionController@show',['submissions'=>$submission])}}" class="list-group-item">{{$submission->name}} <em class="pull-right">({{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}})</em></a>
                        @endforeach

                </div>

            </div>
        </div>
    </div>
@endsection
