@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Submission {{$submissions->id}} for {{$form->name}}</div>

                <div class="panel-body">

                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help
                        </button>

                        @can('moderate',$submissions)
                        <a href="{{action('SubmissionController@moderate',compact('submissions'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Send to Judges
                            </button>
                        </a>
                        @endcan

                        @can('reject',$submissions)
                        <a href="{{action('SubmissionController@reject',compact('submissions'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Reject
                            </button>
                        </a>
                        @endcan

                        @can('judge',$submissions)
                        <a href="{{action('ScoreController@create',compact('submissions'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Judge
                            </button>
                        </a>
                        @endcan

                        @can('unlock',$submissions)
                        <a href="{{action('SubmissionController@unlock',compact('submissions'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> Allow Editing
                            </button>
                        </a>
                        @endcan


                        {{--<a href="{{action('SubmissionController@delete',compact('submissions'))}}"><button type="button" class="btn btn-default">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> Delete
                            </button>
                        </a> --}}
                    </div>

                    <div class="clearfix"></div>

                    <br>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Status</span>
                            <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{$submissions->status}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Received</span>
                            <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submissions->created_at)->toCookieString()}}">
                        </div>
                    </div>

                    @if($submissions->submitted != $submissions->created_at)
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon" id="basic-addon1">Edited</span>
                                <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$submissions->submitted)->toCookieString()}}">
                            </div>
                        </div>
                    @endif

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">Name</span>
                            <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{$submissions->name}}">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon" id="basic-addon1">E-Mail</span>
                            <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{$submissions->email}}">
                        </div>
                    </div>

                    @foreach($fields as $field)
                        <div class="form-group">
                            @if($field->get('fieldDef')->type =='Text')
                                @include('fields.submission.text',['text_field' => $field])
                            @elseif($field->get('fieldDef')->type == 'Select')
                                @include('fields.submission.select',['select_field'=>$field])
                            @elseif($field->get('fieldDef')->type == 'Checkbox')
                                @include('fields.submission.checkbox',['checkbox_field'=>$field])
                            @elseif($field->get('fieldDef')->type == 'RadioGroup')
                                @include('fields.submission.radiogroup',['radiogroup_field'=>$field])
                            @else
                                {{json_encode($field)}}<br>
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
