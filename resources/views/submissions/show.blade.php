@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Submission {{$submissions->id}} for {{$form->name}}</div>

                <div class="panel-body">

                    @foreach($fields as $type=>$field)
                        @if($type =='Text')
                            @include('fields.submission.text',['text_field' => $field])
                        @elseif($type == 'Select')
                            @include('fields.submission.select',['select_field'=>$field])
                        @elseif($type == 'Checkbox')
                            @include('fields.submission.checkbox',['checkbox_field'=>$field])
                        @elseif($type == 'RadioGroup')
                            @include('fields.submission.radiogroup',['radiogroup_field'=>$field])
                        @else
                            {{json_encode($field)}}<br>
                        @endif
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
