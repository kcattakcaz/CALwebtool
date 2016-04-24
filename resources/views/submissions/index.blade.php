@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <h1>Submissions <small>All Teams</small></h1>


            @foreach($forms as $form)
                @if($form->submissions()->get()->count() == 0)
                    @continue
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">{{$form->name}}</div>

                    <div class="panel-body">

                        <div class="list-group">
                            @if($form->group()->first()->isMod(Auth::user()->id))
                                @foreach($form->submissions()->where('status','Reviewing')->get() as $submission)
                                    <a href="{{action('SubmissionController@getForm',['form'=>$form])}}" class="list-group-item">{{$submission->name}} <em class="pull-right">({{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}})</em></a>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
