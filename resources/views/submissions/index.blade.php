@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Choose a form to view its submissions. </div>

                <div class="panel-body">

                    <div class="list-group">
                        @foreach($forms as $form)
                            <a href="{{action('SubmissionController@getForm',['form'=>$form])}}" class="list-group-item">{{$form->name}} <em class="pull-right">({{$form->group()->first()->name}})</em></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
