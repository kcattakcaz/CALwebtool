@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Forms

                </div>

                <div class="tab-content">
                <div class="panel-body well">

            <!--<h1>Forms <small>All Teams</small></h1>-->

                    <!--<div class="btn-group pull-right" role="group" aria-label="..."> -->
                        <a href="{{action('FormDefinitionController@create')}}"><button type="button" class="pull-right btn btn-success">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> New Form
                        </button>
                        </a>
                    <!--</div> -->

                    <br><br>

                    <hr>

                    <div class="list-group">

                        @foreach($forms as $form)
                        <a href="{{action('FormDefinitionController@show',compact('form'))}}" class="list-group-item">{{$form->name}}</a>
                        @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
