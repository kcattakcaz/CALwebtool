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
                    <h1>Create a Form</h1>

                   <p>
                       Form Definitions really allow you to express the layout and content of a Form.
                    </p>

                    <p>
                        Note that Form Definitions belong to the group as a whole, and not to a specific user.
                        Any user with sufficient permission in this group will be able to modify it.
                    </p>

            <ul class="nav nav-tabs">
                <li id="nav_tab_name" role="presentation" class="active"><a href="#">1- Name</a></li>
                <li id="nav_tab_schedule" role="presentation"><a href="#">2- Schedule</a></li>
                <li id="nav_tab_definition" role="presentation"><a href="#">3- Definition</a></li>
                <li id="nav_tab_judges" role="presentation"><a href="#">4- Judges</a></li>
            </ul>


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

                        <div id="form_tab_name">
                            <div class="form-group">
                                <label for="name">Form Name:</label>
                                <input name="name" type="text" class="form-control" id="name">
                            </div>

                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea name="description" rows="3" class="form-control" id="description"></textarea>
                            </div>
                        </div>

                        <div id="form_tab_schedule" style="display:none;">

                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="start_date">Submissions Start Date:</label>
                                    <input name="start_date" type="text" class="form-control" value="" data-date-format="mm/dd/yy" id="start_date" >
                                </div>

                                <div class="form-group col-xs-6">
                                    <label for="end_date">Submissions End Date:</label>
                                    <input name="end_date" type="text" class="form-control" value="" data-date-format="mm/dd/yy" id="end_date" >
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-xs-6">
                                    <label for="scores_date">Judge's Scores Due</label>
                                    <input name="scores_date" type="text" class="form-control" value="" data-date-format="mm/dd/yy" id="scores_date" >
                                </div>

                                <div class="form-group col-xs-6">
                                    <label for="group_id">Group:</label>
                                    <select name="group_id" class="form-control" id="group_id">
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->name}}</option>

                                        @endforeach
                                    </select>
                                </div>

                            </div>

                        </div>


                        <div id="form_tab_definition" style="display:none;">
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
                        </div>

                        <div id="form_tab_judges" style="display:none;">
                            <h1>Judges</h1>
                        </div>

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


       $("#nav_tab_schedule").on('click',function(){
           $(this).addClass('active');

           $("#form_tab_name").slideUp('fast');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_schedule").slideDown('slow');

           $("#nav_tab_name").removeClass('active');
           $("#nav_tab_judges").removeClass('active');
           $("#nav_tab_definition").removeClass('active');

       });


       $("#nav_tab_name").on('click',function(){
           $(this).addClass('active');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_name").slideDown('slow');


           $("#nav_tab_judges").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_definition").removeClass('active');
       });


       $("#nav_tab_definition").on('click',function(){
           $(this).addClass('active');
           $("#form_tab_name").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_definition").slideDown('slow');


           $("#nav_tab_judges").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_name").removeClass('active');
       });


       $("#nav_tab_judges").on('click',function(){
           $(this).addClass('active');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_name").slideUp('fast');
           $("#form_tab_judges").slideDown('slow');


           $("#nav_tab_name").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_definition").removeClass('active');
       });

        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var start_date = $('#start_date').datepicker({
            onRender: function(date) {
                return date.valueOf() < now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > end_date.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                end_date.setValue(newDate);
            }
            start_date.hide();
            $('#end_date')[0].focus();
        }).data('datepicker');

        var end_date = $('#end_date').datepicker({
            onRender: function(date) {
                return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > scores_date.date.valueOf()) {
                var newDate = new Date(ev.date);
                newDate.setDate(newDate.getDate() + 1);
                scores_date.setValue(newDate);
            }
            end_date.hide();
            $('#scores_date')[0].focus();
        }).data('datepicker');

       var scores_date = $('#scores_date').datepicker({
           onRender: function(date) {
               return date.valueOf() <= end_date.date.valueOf() ? 'disabled' : '';
           }
       }).on('changeDate', function(ev) {
           scores_date.hide();
           $('#scores_date')[0].focus();
       }).data('datepicker');


       var ckeditor_description = CKEDITOR.replace( 'description' );

        var Field_Manager = new FieldController($("#formdef_viewer"),null); //Refer to public/js/fieldcontroller.js

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
                    url:"{{action('FormDefinitionController@store')}}",
                    headers:{'X-CSRF-TOKEN':"{{csrf_token()}}"},
                    method:"POST",
                    data:formdef
                }).done(function(data,textStatus,jqXHR){
                    console.log(data);
                    location.replace("{{action('FormDefinitionController@index')}}");
                }).fail(function (jqXHR,textStatus,errorThrown) {
                    console.log("Error:"+errorThrown);
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
