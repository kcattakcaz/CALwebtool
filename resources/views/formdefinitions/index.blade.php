@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
          {{--}}  <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">Forms</h3>

                </div>

                <div class="panel-body"> --}}

            <h1>Forms <small>All Teams</small></h1>

                    <div class="btn-group pull-right" role="group" aria-label="...">
                        <button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span> Help
                        </button>

                        <a href="{{action('FormDefinitionController@create')}}"><button type="button" class="btn btn-default">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Form
                        </button>
                        </a>
                    </div>

                    <br><br>

                    <hr>

                    <div class="list-group">

                        @foreach($forms as $form)
                        <a href="{{action('FormDefinitionController@show',compact('form'))}}" class="list-group-item">{{$form->id}} - {{$form->name}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
