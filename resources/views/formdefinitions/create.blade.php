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


                        <div id="formdef_creator" class="form-group row">
                            <label for="ftype_select" class="col-xs-2">
                                Field Type:
                            </label>
                            <select class="col-xs-3" id="ftype_select" name="ftype_select">
                                @foreach($field_types as $key=>$ftype)
                                    <option value="{{$key}}">{{$ftype->get("name")}}</option>
                                @endforeach
                            </select>
                            <label for="ftype_name" class="col-xs-1">
                                Name:
                            </label>
                            <input id="ftype_name" name="ftype_name" class="col-xs-4" type="text"/>
                            <button type="button" id="btn_addField" class="btn btn-default col-xs-offset-1 col-xs-1">Add</button>

                        </div>

                        <div class="col-xs-12" id="formdef_viewer">
                        </div>



                        <button type="submit" class="btn btn-default">Submit</button>

                        <div class="panel-group">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#collapse1">Collapsible panel</a>
                                    </h4>
                                </div>
                                <div id="collapse1" class="panel-collapse collapse">
                                    <div class="panel-body">Panel Body</div>
                                    <div class="panel-footer">Panel Footer</div>
                                </div>
                            </div>
                        </div>
                </div>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

    <script>

        function FieldController()  {
            this.field_objects = [];
            this.current_field_count = 1;
            this.supported_types = {}

            this.newField = function(type,name){

                var panel_group = $("<div class='panel-group'>");

                var panel = $("<div class='panel panel-default'>");
                var panel_heading = $("<div class='panel-heading><h3 class='panel-title'>Hello</h3></div>");

                console.log(panel_heading);

                panel.append(panel_heading);

                var panel_collapse = $("<div class='panel-collapse collapse in'>");

                var panel_body = $("<div class='panel-body'>");
                var panel_footer = $(" <div class='panel-footer'><a href='#collapse' data-toggle='collapse'>Collapse</a></div>");

                if(type == "Text"){
                    var nF = new TextField(this.getUniqueFieldId(name),name);
                }

                this.field_objects.push(nF);
                nF.renderOptions(panel_body,null);
                panel_collapse.append(panel_body);
                panel_collapse.append(panel_footer);
                panel.append(panel_collapse);
                panel_group.append(panel);
                $('#formdef_viewer').append(panel_group);

                $("#"+this.id+"_panel_collapse").on('click',function(){

                })
            };

            this.delField = function(fieldId){
                return null;
            };

            this.getUniqueFieldId = function (name) { //generate a unique ID for this field based upon its name
                i = 2;
                var fieldID = name.replace(/\W+/g, "_"); //replace all non-alphanumeric characters with a _ //TEST WHAT HAPPENS WITH A SINGE SPACE!
                while(true){
                    if(this.field_objects.hasOwnProperty(fieldID)){
                        fieldID = fieldID + "_" + i.toString();
                        i++;
                    }
                    else{
                        return fieldID;
                    }
                }
            }
        }

        function TextField(id,name){
            this.type = "Text";
            this.id = id;
            this.name = name;
            this.required = false;
            this.is_multi_line = false;
            this.max_length = 500;
            this.min_length = 0;

            this.elementref_name = null;
            this.elementref_required = null;
            this.elementref_multiline = null;
            this.elementref_maxlength = null;
            this.elementref_minlength = null;

            /**
             * renderOptions(parentElementRef,currentValuesObj)
             *
             * parentElementRef: Use a JQuery selector to refer to the parent element the field should be appended to
             * currentvaluesObj: An object containing key-value pairs of all the parameters needed to restore a field
             *
             * renderOptions will append to a given parent element the HTML elements needed to set the values of this field
             * you may also provide values to fill the fields from a saved state.  It will also setup the object so that you
             * can later call retrieveValues() to get the field parameters
             *
             **/
            this.renderOptions = function(parentElementRef,currentValuesObj){
                if(currentValuesObj === null){

                    //Name Text Input Field
                        var name_group = $("<div class='form-group'>");
                        name_group.append("<label for='name'>Name</label>");
                        this.elementref_name = $("<input class='form-control' type='text' name='"+this.id+"_name' id='"+this.id+"_name'>");
                        name_group.append(this.elementref_name);
                        parentElementRef.append(name_group);

                    //Required Select Field
                        var required_group = $("<div class='form-group'>");
                        required_group.append("<label for='required'>Required</label>");
                        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
                                .append(
                                        $("<option value='false'>False: Is Optional</option>"),
                                        $("<option value='true'> True: Is Required</option>")
                                );
                        required_group.append(this.elementref_required);
                        parentElementRef.append(required_group);

                    //Multiline Select Field
                        var multiline_group = $("<div class='form-group'>");
                        multiline_group.append("<label for='required'>Multiline</label>");
                        this.elementref_multiline = $("<select class='form-control' name='"+this.id+"_multiline' id='"+this.id+"_multiline'>")
                                .append(
                                        $("<option value='false'>False: Is Single Line</option>"),
                                        $("<option value='true'> True: Mutli Line</option>")
                                );
                        multiline_group.append(this.elementref_multiline);
                        parentElementRef.append(multiline_group);

                    //Min Length Input Field
                        var minlength_group = $("<div class='form-group'>");
                        minlength_group.append("<label for='name'>Min Length</label>");
                        this.elementref_minlength = $("<input class='form-control' type='text' name='"+this.id+"_minlength' id='"+this.id+"_minlength'>");
                        minlength_group.append(this.elementref_minlength);
                        parentElementRef.append(minlength_group);

                    //Max Length Input Field
                        var maxlength_group = $("<div class='form-group'>");
                        maxlength_group.append("<label for='name'>Max Length</label>");
                        this.elementref_maxlength = $("<input class='form-control' type='text' name='"+this.id+"_maxlength' id='"+this.id+"_maxlength'>");
                        maxlength_group.append(this.elementref_maxlength);
                        parentElementRef.append(maxlength_group);

                }
                else{
                    parentElementRef.append(alert("I can't do that yet!"));
                }
            }

            this.renderView = function(){
                return null;
            }

        }


        var Field_Manager = new FieldController($("#formdef_viewer"));

        $("#btn_addField").on( "click", function() {
            var selected_field_type = $("#ftype_select").val();
            var entered_name = $("#ftype_name").val();
            Field_Manager.newField(selected_field_type,entered_name);

        });


    </script>

@endsection
