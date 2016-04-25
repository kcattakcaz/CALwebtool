@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            <!--
            <div class="panel panel-default">


                <div class="panel-heading">Create a Form Definition</div>

                <div class="panel-body">

                -->

            <div class="row">
                    <h1>Create a Form</h1>

                   <p>
                       Form Definitions allow you to express the layout and content of a Form.
                   </p>
            </div>


            <div class="row">


                <ul class="nav nav-tabs">
                    <li id="nav_tab_name" role="presentation" class="form_switch_to_name active"><a href="#">1- Name</a></li>
                    <li id="nav_tab_schedule" role="presentation" class="form_switch_to_schedule"><a href="#">2- Schedule</a></li>
                    <li id="nav_tab_definition" role="presentation" class="form_switch_to_definition"><a href="#">3- Definition</a></li>
                    <li id="nav_tab_judges" role="presentation" class="form_switch_to_judges"><a href="#">4- Judges</a></li>
                    <li id="nav_tab_advanced" role="presentation" class="form_switch_to_advanced"><a href="#">Advanced</a></li>

                </ul>
            </div>


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

                        <div class="row">
                            <br>
                            <div id="form_tab_name">
                                <div class="form-group">
                                    <label for="name">Form Name:</label>
                                    <input name="name" type="text" class="form-control" id="name">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description:</label>
                                    <textarea name="description" rows="3" class="form-control" id="description"></textarea>
                                </div>

                                <div>
                                    <a href="#" class="form_switch_to_schedule">
                                        <button type="button" class="btn btn-info pull-right">Next</button>
                                    </a>
                                </div>

                            </div>



                        </div>

                        <div id="form_tab_schedule" style="display:none;">

                            <div class="row">
                                <br>
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

                            </div>

                            <div>
                                <a href="#" class="form_switch_to_name">
                                    <button type="button" class="btn btn-info pull-left">Previous</button>
                                </a>

                                <a href="#" class="form_switch_to_definition">
                                    <button type="button" class="btn btn-info pull-right">Next</button>
                                </a>
                            </div>

                        </div>


                        <div id="form_tab_definition" style="display:none;">
                            <br>
                            <div class="row">
                                <div id="formdef_creator" class="form-group">
                                    <div class="form-group-sm col-md-5">
                                        <label for="ftype_select" class="">
                                            Field Type:
                                        </label>
                                        <select class="form-control" id="ftype_select" name="ftype_select">
                                            <option value="Text">Text</option>
                                            <option value="Checkbox">Checkbox</option>
                                            <option value="Radio">Radio Group</option>
                                            <option value="Select">Select Dropdown</option>
                                            <option value="Address">Address</option>
                                            <option value="File">File</option>
                                        </select>
                                    </div>

                                    <div class="form-group-sm col-md-5">
                                        <label for="ftype_name">
                                            Name:
                                        </label>
                                    <input id="ftype_name" name="ftype_name" class="form-control" type="text"/>
                                    </div>
                                    <div class="form-group-sm col-md-offset-1 col-md-1">
                                        <label>Add to Form</label>
                                        <button type="button" id="btn_addField" class="btn btn-default form-control">Add</button>
                                    </div>

                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-xs-12" id="formdef_viewer">
                                </div>
                            </div>

                            <div>
                                <a href="#" class="form_switch_to_schedule pull-left">
                                    <button type="button" class="btn btn-info">Previous</button>
                                </a>

                                <a href="#" class="form_switch_to_judges pull-right">
                                    <button type="button" class="btn btn-info">Next</button>
                                </a>
                            </div>

                        </div>

                        <div id="form_tab_judges" style="display:none;">
                            <h1>Judges</h1>
                            <div class="row">

                                <div class="form-group col-xs-12">
                                    <label for="group_id">Group:</label>
                                    <select name="group_id" class="form-control" id="group_id">
                                        <option value="null">None</option>
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}">{{$group->name}}</option>

                                        @endforeach
                                    </select>
                                </div>

                            </div>

                            <div class="row">
                                <div class="form-group col-xs-12">
                                    <label for="judges">Group:</label>
                                    <select multiple name="judges[]" class="form-control" id="judges">

                                    </select>
                                </div>
                            </div>

                            <div>
                                <a href="#" class="form_switch_to_definition">
                                    <button class="btn btn-info">Previous</button>
                                </a>

                                <button id="btn_save_formdef" type="button" class="btn btn-success pull-right">Save</button>

                            </div>




                        </div>

                        <div id="form_tab_advanced" style="display: none;">
                            <div class="row">
                                <div class="form-group">
                                    <label for="sub_accept_action">Action after a successful submission:</label>
                                    <select id="sub_accept_action" name="sub_accept_action" class="form-control">
                                        <option value="default">Show Default Message</option>
                                        <option value="custom_message">Custom Message</option>
                                        <option value="custom_redir">Custom Redirect</option>
                                    </select>
                                </div>

                            </div>

                            <div id="form_adv_def" class="row">
                                <p>Thanks, USER NAME, your submission has been accepted.</p>
                            </div>

                            <div id="form_adv_redir" class="row" style="display:none;">
                                <div class="form-group">
                                    <label for="sub_accept_redir">Redirect to URL:</label>
                                    <input id="sub_accept_redir" type="text" class="form-control" name="sub_accept_redir" placeholder="http://">
                                </div>
                            </div>
                            <div id="form_adv_msg" class="row" style="display:none;">
                                <div class="form-group">
                                    <label for="sub_accept_content">Custom Message:</label>
                                    <textarea id="sub_accept_content" name="sub_accept_content" class="form-control" rows="3"></textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <label for="use_custom_css">
                                        Use custom CSS on form:
                                    </label>
                                    <select id="use_custom_css" class="form-control" name="use_custom_css">
                                        <option value="false">No, use default stylesheet</option>
                                        <option value="true">Yes, use custom stylesheet</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="custom_css_url">
                                        Custom Stylesheet URL:
                                    </label>
                                    <input id="custom_css_url" class="form-control" name="custom_css_url" type="text" placeholder="https://www.msucalawards.org/public/css/forms.css">
                                </div>
                            </div>

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

<script src="https://cdn.ckeditor.com/4.5.8/standard/ckeditor.js"></script>

<script>

</script>


<script src="{{secure_asset('js/bootstrap-datepicker.js')}}">
</script>

    <script>

        var ckeditor_description = CKEDITOR.replace( 'description' );
        var ckeditor_accept_content = CKEDITOR.replace('sub_accept_content');

       // $('#scores_due_date').datepicker();

       function switchToSchedule() {
           $("#nav_tab_schedule").addClass('active');

           $("#form_tab_name").slideUp('fast');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_schedule").slideDown('slow');

           $("#nav_tab_name").removeClass('active');
           $("#nav_tab_judges").removeClass('active');
           $("#nav_tab_definition").removeClass('active');
           $("#nav_tab_advanced").removeClass('active');
       }

       function switchToName(){
           $("#nav_tab_name").addClass('active');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_advanced").slideUp('fast');
           $("#form_tab_name").slideDown('slow');
           
           $("#nav_tab_judges").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_definition").removeClass('active');
           $("#nav_tab_advanced").removeClass('active');
       }
       
       function switchToDefintion(){
           $("#nav_tab_definition").addClass('active');
           $("#form_tab_name").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_advanced").slideUp('fast');
           $("#form_tab_definition").slideDown('slow');


           $("#nav_tab_judges").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_name").removeClass('active');
           $("#nav_tab_advanced").removeClass('active');
       }
       
       function switchToJudges() {
           $("#nav_tab_judges").addClass('active');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_name").slideUp('fast');
           $("#form_tab_advanced").slideUp('fast');
           $("#form_tab_judges").slideDown('slow');


           $("#nav_tab_name").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_definition").removeClass('active');
           $("#nav_tab_advanced").removeClass('active');
       }

       function switchToAdvanced(){
           $("#nav_tab_advanced").addClass('active');
           $("#form_tab_definition").slideUp('fast');
           $("#form_tab_schedule").slideUp('fast');
           $("#form_tab_name").slideUp('fast');
           $("#form_tab_judges").slideUp('fast');
           $("#form_tab_advanced").slideDown('slow');


           $("#nav_tab_name").removeClass('active');
           $('#nav_tab_schedule').removeClass('active');
           $("#nav_tab_definition").removeClass('active');
           $("#nav_tab_judges").removeClass('active');
       }

       $(".form_switch_to_schedule").on('click',function(){
           switchToSchedule();
       });


       $(".form_switch_to_name").on('click',function(){
           switchToName();
       });


       $(".form_switch_to_definition").on('click',function(){
           switchToDefintion();
       });


       $(".form_switch_to_judges").on('click',function(){
           switchToJudges();
       });

       $(".form_switch_to_advanced").on('click',function(){
           switchToAdvanced();
       })

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
            formdef['sub_accept_action'] = $("#sub_accept_action").val();
            formdef['sub_accept_redir'] = $("#sub_accept_redir").val();
            formdef['sub_accept_content'] = ckeditor_accept_content.getData();
            formdef['judges'] = $("#judges").val();
            formdef['use_custom_css'] = $("#use_custom_css").val();
            formdef['custom_css_url'] = $("#custom_css_url").val();


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

                });
            }
        });


       $("#group_id").on('change',function () {
           var judge_select = $("#judges");
           judge_select.empty();
           var users = group_users[$(this).val()];
           console.log(users);
           $.each(users,function (value,key) {
               judge_select.append($("<option>").attr('value',key.id).text(key.name));
           })
       });

       $("#sub_accept_action").on('change',function(){
           var new_val = $(this).val();

           console.log(new_val);

           if(new_val == "default"){
               $("#form_adv_def").show();
               $("#form_adv_msg").hide();
               $('#form_adv_redir').hide();
           }
           else if(new_val == "custom_message"){
               $("#form_adv_def").hide();
               $("#form_adv_msg").show();
               $('#form_adv_redir').hide();
           }
           else if(new_val == "custom_redir"){
               $("#form_adv_def").hide();
               $("#form_adv_msg").hide();
               $('#form_adv_redir').show();
           }
       });


        var group_users = {!! $judges->toJson() !!};

    </script>

@endsection
