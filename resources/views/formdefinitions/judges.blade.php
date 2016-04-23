@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <!--
                <div class="panel panel-default">


                    <div class="panel-heading">Create a Form Definition</div>

                    <div class="panel-body">

                    -->
                <h1>Form Judges</h1>


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="judges_form" method="post" action="{{action('FormDefinitionController@updateJudges',compact('form'))}}">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="form-group">
                            <label for="judges">Current Judges:</label>
                            <select multiple id="judges" name="judges[]" class="form-control">
                                @foreach($current_judges as $cur_judge)
                                    <option value="{{$cur_judge->id}}">{{$cur_judge->name}} ({{$cur_judge->email}})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <button class="btn btn-info col-xs-offset-5 col-xs-1" id="btn_add_judge" type="button"><span class="glyphicon glyphicon-arrow-up"></span></button>
                        <button class="btn btn-info col-xs-1" id="btn_remove_judge" type="button"><span class="glyphicon glyphicon-arrow-down"></span></button>
                    </div>

                    <div class="row">
                        <div class="form-group">
                            <label for="avail_judges">Available Judges</label>
                            <select multiple id="avail_judges" name="avail_judges" class="form-control">
                                @foreach($available_judges as $avail_judge)
                                    <option value="{{$avail_judge->id}}">{{$avail_judge->name}} ({{$avail_judge->email}})</option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="row">
                        <button id="btn_save_judges" type="submit" class="btn btn-default">Save</button>
                    </div>
                </form>


                <!--</div> -->
                <!--</div>-->
            </div>
        </div>
    </div>

    <script>

        $("#btn_remove_judge").on('click',function(){
            $("#judges").children().filter(":selected").each(function(index){
                console.log($(this).val());
                $(this).detach();
                $("#avail_judges").append($(this));
            });
        });

        $("#btn_add_judge").on('click',function(){
            $("#avail_judges").children().filter(":selected").each(function(index){
                console.log($(this).val());
                $(this).detach();
                $("#judges").append($(this));
            });
        });

        $("#judges_form").on('submit',function(){
           $("#judges").children().each(function(index){
               $(this).attr('selected','selected');
            })
        });

    </script>

@endsection
