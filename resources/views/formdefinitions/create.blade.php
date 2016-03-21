@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create a Form Definition</div>

                <div class="panel-body">
                   <p>
                       Form Definitions allow you to express the layout and content of a Form.
                    </p>

                    <p>
                        Note that Form Definitions belong to the group as a whole, and not to a specific user.
                        Any user with sufficient permission in this group will be able to modify it.
                    </p>


                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form role="form" method="post" action="{{action('GroupController@store')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Form Name:</label>
                            <input name="name" type="text" class="form-control" id="name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" rows="3" class="form-control" id="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="group_id">Group:</label>
                            <select name="group_id" class="form-control" id="group_id">
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>


                        <div id="formdef_creator" class="form-group">
                            <label for="ftype_select" class="col-xs-2">
                                Field Type:
                            </label>
                            <select class="col-xs-7" id="ftype_select" name="ftype_select">
                                @foreach($field_types as $key=>$ftype)
                                    <option value="{{$key}}">{{$ftype->get("name")}}</option>
                                @endforeach
                            </select>
                            <button type="button" id="btn_addField" class="btn btn-default col-xs-offset-1 col-xs-2">Add</button>
                            <br>
                            <div id="formdef_viewer">

                            </div>

                        </div>



                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<script src="http://code.jquery.com/jquery-1.12.0.min.js"></script>


    <script>
        $("#btn_addField").on( "click", function() {
            //console.log($("#ftype_select").val());
            if($("#ftype_select").val() == "Text"){
              $("#formdef_viewer").append("<p>Text Field</p>")
            }
        });
    </script>

@endsection
