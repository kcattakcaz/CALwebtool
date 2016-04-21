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
                <h1>Edit a Form</h1>

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


                <form role="form">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="name">Form Name:</label>
                        <input name="name" type="text" class="form-control" id="name" value="{{$form->name}}">
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" rows="3" class="form-control" id="description">{{$form->description}}</textarea>
                    </div>

                    <hr>

                    <div class="row">
                        <div id="formdef_creator" class="form-group">
                            <label for="ftype_select" class="col-md-2">
                                Field Type:
                            </label>
                            <select class="col-md-3" id="ftype_select" name="ftype_select">
                                <option value="Text">Text</option>
                                <option value="Checkbox">Checkbox</option>
                                <option value="Radio">Radio Group</option>
                                <option value="Select">Select Dropdown</option>
                                <option value="Address">Address</option>
                                <option value="File">File</option>
                            </select>
                            <label for="ftype_name" class="col-md-1">
                                Name:
                            </label>
                            <input id="ftype_name" name="ftype_name" class="col-md-4" type="text"/>
                            <button type="button" id="btn_addField" class="btn btn-default col-md-offset-1 col-md-1">Add</button>

                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-xs-12" id="formdef_viewer">
                        </div>
                    </div>


                    <button id="btn_save_formdef" type="button" class="btn btn-default">Save</button>


                </form>


                <!--</div> -->
                <!--</div>-->
            </div>
        </div>
    </div>


    <script src="{{secure_asset('js/fieldcontroller.js')}}">

    </script>

    <link rel="stylesheet" href="{{secure_asset('css/datepicker.css')}}">

    <script src="https://cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
    </script>


    <script src="{{secure_asset('js/bootstrap-datepicker.js')}}">
    </script>

    <script>

        // $('#scores_due_date').datepicker();


        var ckeditor_description = CKEDITOR.replace( 'description' );

        var previousValuesObj = eval({!!\CALwebtool\Http\Controllers\FormDefinitionController::getDefinition($form)!!});
        console.log("prveious Values");
        console.log(previousValuesObj);

        var Field_Manager = new FieldController($("#formdef_viewer"),previousValuesObj); //Refer to public/js/fieldcontroller.js

        $("#btn_addField").on( "click", function() {
            var selected_field_type = $("#ftype_select").val();
            var entered_name = $("#ftype_name").val();
            if(entered_name.length == 0){
                alert("Please provide a name");
                return;
            }
            else {
                Field_Manager.newField(selected_field_type, entered_name);
                $("#ftype_name").val("");
            }

        });

        $("#btn_save_formdef").on("click",function(){

            var formdef = {};


            var fields = Field_Manager.getFieldDefinitions();

            formdef['name'] = $("#name").val();
            formdef['description'] = ckeditor_description.getData();
            formdef['group'] = $("#group_id").val();
            formdef['start_date'] =$("#start_date").val();
            formdef['end_date'] = $("#end_date").val();
            formdef['scores_date'] = $("#scores_date").val();
            formdef['definition'] = fields;


            if(formdef === null){
                alert("Error");
                console.log("FormDef:");
                console.log(Field_Manager.getErrors());
            }
            else{
                $.ajax({
                    url:"{{action('FormDefinitionController@update',compact('form'))}}",
                    headers:{'X-CSRF-TOKEN':"{{csrf_token()}}"},
                    method:"PUT",
                    data:formdef
                }).done(function(data,textStatus,jqXHR){
                    console.log(data);
                }).fail(function (jqXHR,textStatus,errorThrown) {
                    console.log("Error:"+errorThrown);
                    //console.log("Error:"+errorThrown);
                    var errorsJson = JSON.parse(jqXHR.responseText);
                    console.log(errorsJson);
                    var errorString = "The form couldn't be saved due to the following errors:\n\n";
                    for(fields in errorsJson){
                        console.log(fields);
                        if(fields == "status"){
                            alert(errorsJson.message);
                            return;
                        }
                        else{
                            console.log(errorsJson[fields]);
                            for(var i =0; i<errorsJson[fields].length; i++){
                                errorString += fields + " : " + errorsJson[fields][i] +"\n";
                            }
                        }
                    }
                    alert(errorString);
                })
            }
        })


    </script>

@endsection
