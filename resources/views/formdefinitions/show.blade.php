@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>{{$form->name}}</h1>

            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h4 class="panel-title">
                            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Form Settings
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                        <div class="panel-body">
                            @if($form->status == "Drafting")
                                A draft of the form has been saved, but it hasn't been scheduled yet.  It will <strong>NOT</strong> open for submissions until you schedule it!
                            @elseif($form->status == "Scheduled")
                                The form is scheduled to open on {{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$form->submissions_start)->toDayDateTimeString()}} (It's {{\Carbon\Carbon::now()->toDayDateTimeString()}} right now)
                            @elseif($form->status == "Accepting")
                                The form is now accepting submissions until {{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$form->submissions_end)->toDayDateTimeString()}} (It's {{\Carbon\Carbon::now()->toDayDateTimeString()}}
                            @elseif($form->status == "Reviewing")

                            @elseif($form->status == "Scored")

                            @elseif($form->status == "Archived")
                                The form is archived.
                            @endif

                            <br>

                            <div class="btn-group" role="group" aria-label="...">
                                <a href="{{action('FormDefinitionController@schedule',compact('form'))}}">
                                    <button type="button" class="btn btn-primary">Schedule</button>
                                </a>
                                <a href="{{action('FormDefinitionController@edit',compact('form'))}}">
                                    <button type="button" class="btn btn-default">Edit</button>
                                </a>
                                <a href="{{action('FormDefinitionController@delete',compact('form'))}}">
                                    <button type="button" class="btn btn-danger">Delete</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingTwo">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Submissions Requiring Moderation <strong class="pull-right">{{$new_submissions->count()}}</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <ul class="list-group">
                            @foreach($new_submissions as $submission)
                                <a href="{{action('SubmissionController@show',compact('submission'))}}" class="list-group-item clearfix">
                                    #{{$submission->id}}- <strong>{{$submission->name}}</strong> ({{$submission->email}})
                                    <em class="pull-right">{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}}</em>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingThree">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Submissions Awaiting Judging  <strong class="pull-right">{{$moderated_submissions->count()}}</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                        <ul class="list-group">
                            @foreach($moderated_submissions as $submission)
                                <a href="{{action('SubmissionController@show',compact('submission'))}}" class="list-group-item clearfix">
                                    #{{$submission->id}}- <strong>{{$submission->name}}</strong> ({{$submission->email}})
                                    <em class="pull-right">{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}}</em>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingSix">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                Submissions Judged  <strong class="pull-right">{{$judged_submissions->count()}}</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                        <ul class="list-group">
                            @foreach($moderated_submissions as $submission)
                                <a href="{{action('SubmissionController@show',compact('submission'))}}" class="list-group-item clearfix">
                                    #{{$submission->id}}- <strong>{{$submission->name}}</strong> ({{$submission->email}})
                                    <em class="pull-right">{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}}</em>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingFour">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Submissions Accepted  <strong class="pull-right">{{$accepted_submissions->count()}}</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                        <ul class="list-group">
                            @foreach($accepted_submissions as $submission)
                                <a href="{{action('SubmissionController@show',compact('submission'))}}" class="list-group-item clearfix">
                                    #{{$submission->id}}- <strong>{{$submission->name}}</strong> ({{$submission->email}})
                                    <em class="pull-right">{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}}</em>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingFive">
                        <h4 class="panel-title">
                            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                Submissions Rejected <strong class="pull-right">{{$rejected_submissions->count()}}</strong>
                            </a>
                        </h4>
                    </div>
                    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
                        <ul class="list-group">
                            @foreach($rejected_submissions as $submission)
                                <a href="{{action('SubmissionController@show',compact('submission'))}}" class="list-group-item clearfix">
                                    #{{$submission->id}}- <strong>{{$submission->name}}</strong> ({{$submission->email}})
                                    <em class="pull-right">{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submission->submitted)->toCookieString()}}</em>
                                </a>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>


        </div>
    </div>
</div>

<script src="{{secure_asset('js/fieldcontroller.js')}}"></script>

<script>


</script>


@endsection
