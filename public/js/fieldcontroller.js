/**
 * Created by Zach Jaghory on 3/25/16.
 */


function FieldController()  {
    this.field_objects = {};
    this.current_field_count = 1;
    this.supported_types = {};
    this.errorList = [];

    this.newField = function(type,name){

        var field_id = this.getUniqueFieldId(type);
        console.log("New Field created with ID: "+field_id);

        var newField = null;

        if(type == "Text"){
            newField = new TextField(field_id,name);
        }
        else if(type == "Checkbox"){
            newField = new CheckBoxField(field_id,name);
        }
        else if(type == "Select"){
            newField = new SelectField(field_id,name);
        }
        else if(type == "Radio"){
            newField = new RadioGroupField(field_id,name);
        }

        if(newField === null){
            console.log("Failed to create field of given type "+type+" (the attempt to create the object returned null)");
            alert("There was a problem creating a field of the type you selected, please sign out, close all browser windows, then try again.  If the problem persists, please refer to the documentation.")
            return;
        }
        this.field_objects[field_id] =  newField;

        var panel_group = $("<div class='panel-group'>").attr("id","panel_group_"+field_id);

        var panel = $("<div class='panel panel-default'>");
        var panel_heading = $("<div class='panel-heading'>");
        var panel_title = $("<h3 class='panel-title'>");
        var title_link = $("<a href='#"+field_id+"'>").attr('data-toggle','collapse').attr('id',field_id+"_title_link").text(name +" ("+field_id + ")");
        var delete_link = $("<a href='#"+field_id+"_delete'>").addClass('pull-right glyphicon glyphicon-remove').on('click',function(){
            Field_Manager.delField(field_id);
        });
        panel_title.append(title_link);
        panel_title.append(delete_link);
        panel_heading.append(panel_title);

        panel.append(panel_heading);

        var panel_collapse = $("<div class='panel-collapse collapse in'>").attr('id',field_id);

        var panel_body = $("<div class='panel-body'>");
        var panel_footer = $(" <div class='panel-footer'>");
        var collapse_link = $("<a href='#"+field_id+"'>").attr('data-toggle','collapse').attr('id',field_id+"_collapse_link").text("Close");
        panel_footer.append(collapse_link);


        newField.renderOptions(panel_body,null);
        panel_collapse.append(panel_body);
        panel_collapse.append(panel_footer);
        panel.append(panel_collapse);
        panel_group.append(panel);
        $('#formdef_viewer').append(panel_group);

    };

    this.delField = function(fieldId){
        delete this.field_objects[fieldId];
        $("#panel_group_"+fieldId).remove();
        return true;
    };

    this.getFieldDefinitions = function(){
        var definitionArray = [];

        for(var field in this.field_objects){
            //console.log(field);
            console.log(this.field_objects[field].getValuesObj());
            definitionArray.push(this.field_objects[field].getValuesObj());
        }
        if(this.errorList.length == 0){
            return definitionArray;
        }
        else{
            return null;
        }


    };

    this.getUniqueFieldId = function (type) { //generate a unique ID for this field based upon its type
        i = 2;
        //var fieldID = type.replace(/\W+/g, "_"); //replace all non-alphanumeric characters with a _
        var fieldID = type + "_1";
        while(true){
            if(this.field_objects.hasOwnProperty(fieldID)){
                fieldID = type + "_" + i.toString();
                i++;
            }
            else{
                return fieldID;
            }
        }
    };

    this.addError = function(error){
        this.errorList.push(error);
    }

    this.getErrors = function(){
        var errors = this.errorList;
        this.errorList = [];
        return errors;
    };
}

function TextField(id,name){
    this.type = "Text";
    this.id = id;
    this.name = name;
    this.required = false;
    //this.is_multi_line = false;
    this.max_length = 500;
    this.min_length = 0;
    this.text_type = "Plain";

    this.elementref_name = null;
    this.elementref_required = null;
    this.elementref_multiline = null;
    this.elementref_maxlength = null;
    this.elementref_minlength = null;
    this.elementref_texttype = null;

    /**
     * renderOptions(parentElementRef,currentValuesObj)
     *
     * parentElementRef: Use a JQuery selector to refer to the parent element the field should be appended to
     * currentvaluesObj: An object containing key-value pairs of all the parameters needed to restore a field
     *
     * renderOptions will append to a given parent element the HTML elements needed to set the values of this field
     * you may also provide values to fill the fields from a saved state.  It will also setup the object so that you
     * can later call getValuesObj() to get the field parameters
     *
     **/
    this.renderOptions = function(parentElementRef,currentValuesObj){
        if(currentValuesObj === null){

            //Name Text Input Field//
            var name_group = $("<div class='form-group'>");
            name_group.append("<label for='name'>Name</label>");
            this.elementref_name = $("<input class='form-control' type='text' name='"+this.id+"_name' id='"+this.id+"_name'>").val(this.name);
            name_group.append(this.elementref_name);
            parentElementRef.append(name_group);
            this.elementref_name.on('keyup',{field_id:this.id},function(event){
                //console.log("Change event for: "+this+"  with id: "+event.data.field_id);
                $("#"+event.data.field_id+"_title_link").text($(this).val() + " ("+event.data.field_id+")");
            });

            //Required Select Field
            var required_group = $("<div class='form-group'>");
            required_group.append("<label for='required'>Required</label>");
            this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
                .append(
                    $("<option value='0'>False: Is Optional</option>"),
                    $("<option value='1'> True: Is Required</option>")
                );
            required_group.append(this.elementref_required);
            parentElementRef.append(required_group);
            /*
            //Multiline Select Field
            var multiline_group = $("<div class='form-group'>");
            multiline_group.append("<label for='required'>Multiline</label>");
            this.elementref_multiline = $("<select class='form-control' name='"+this.id+"_multiline' id='"+this.id+"_multiline'>")
                .append(
                    $("<option value='0'>False: Is Single Line</option>"),
                    $("<option value='1'> True: Mutli Line</option>")
                );
            multiline_group.append(this.elementref_multiline);
            parentElementRef.append(multiline_group);
            */
            //TextType Select Field
            //Multiline Select Field
            var texttype_group = $("<div class='form-group'>");
            texttype_group.append("<label for='required'>Text Type</label>");
            this.elementref_texttype = $("<select class='form-control' name='"+this.id+"_texttype' id='"+this.id+"_texttype'>")
                .append(
                    $("<option value='any'>Any Text</option>"),
                    $("<option value='multiline'>Multiline Text Area</option>"),
                    $("<option value='num'>Number Spinner</option>"),
                    $("<option value='alpha'>Alphabetic</option>"),
                    $("<option value='email'>E-Mail Field</option>"),
                    $("<option value='phone'>Telephone Field</option>"),
                    $("<option value='date'>Date Field</option>"),
                    $("<option value='time'>Time Field</option>")
                );
            texttype_group.append(this.elementref_texttype);
            parentElementRef.append(texttype_group);



            //Min Length Input Field
            var minlength_group = $("<div class='form-group'>");
            minlength_group.append("<label for='name'>Min Length</label>");
            this.elementref_minlength = $("<input class='form-control' type='text' name='"+this.id+"_minlength' id='"+this.id+"_minlength'>").val(1);
            minlength_group.append(this.elementref_minlength);
            parentElementRef.append(minlength_group);

            //Max Length Input Field
            var maxlength_group = $("<div class='form-group'>");
            maxlength_group.append("<label for='name'>Max Length</label>");
            this.elementref_maxlength = $("<input class='form-control' type='text' name='"+this.id+"_maxlength' id='"+this.id+"_maxlength'>").val(255);
            maxlength_group.append(this.elementref_maxlength);
            parentElementRef.append(maxlength_group);

        }
        else{
            parentElementRef.append(alert("I can'helt do that yet!"));
        }
    };

    this.renderView = function(){
        return null;
    };

    this.getValuesObj = function(){
        var values = {};

        values.type = "Text";
        values.id = this.id;
        values.name = this.elementref_name.val();
        values.required = this.elementref_required.val();
        values.text_type = this.elementref_texttype.val();
        //values.multiline = this.elementref_multiline.val();
        values.maxlength = parseInt(this.elementref_maxlength.val(),10);
        values.minlength = parseInt(this.elementref_minlength.val(),10);
        return values;
    }

}

function CheckBoxField(id,name){
    this.type = "CheckBox";
    this.id = id;
    this.name = name;
    this.required = false;
    this.value_true = true;
    this.value_false = false;

    this.elementref_name = null;
    this.elementref_required = null;
    this.elementref_value_true = null;
    this.elementref_value_false = null;

    /**
     * renderOptions(parentElementRef,currentValuesObj)
     *
     * parentElementRef: Use a JQuery selector to refer to the parent element the field should be appended to
     * currentvaluesObj: An object containing key-value pairs of all the parameters needed to restore a field
     *
     * renderOptions will append to a given parent element the HTML elements needed to set the values of this field
     * you may also provide values to fill the fields from a saved state.  It will also setup the object so that you
     * can later call getValuesObj() to get the field parameters
     *
     **/
    this.renderOptions = function(parentElementRef,currentValuesObj){
        if(currentValuesObj === null){

            //Name Text Input Field//
            var name_group = $("<div class='form-group'>");
            name_group.append("<label for='name'>Name</label>");
            this.elementref_name = $("<input class='form-control' type='text' name='"+this.id+"_name' id='"+this.id+"_name'>").val(this.name);
            name_group.append(this.elementref_name);
            parentElementRef.append(name_group);
            this.elementref_name.on('keyup',{field_id:this.id},function(event){
                //console.log("Change event for: "+this+"  with id: "+event.data.field_id);
                $("#"+event.data.field_id+"_title_link").text($(this).val() + " ("+event.data.field_id+")");
            });

            //Required Select Field
            var required_group = $("<div class='form-group'>");
            required_group.append("<label for='required'>Required</label>");
            this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
                .append(
                    $("<option value='0'>False: Is Optional</option>"),
                    $("<option value='1'> True: Is Required</option>")
                );
            required_group.append(this.elementref_required);
            parentElementRef.append(required_group);

            //Checked Value Input Field
            var value_checked_group = $("<div class='form-group'>");
            value_checked_group.append("<label for='values'>Value when Checked</label>")
            this.elementref_value_true = $("<input class='form-control' type='text' name='"+this.id+"_value_true' id='"+this.id+"_value_true'>").val('True');
            value_checked_group.append(this.elementref_value_true);
            parentElementRef.append(value_checked_group);

            //Unchecked Value Input Field
            var value_unchecked_group = $("<div class='form-group'>");
            value_unchecked_group.append("<label for='values'>Value when NOT Checked</label>")
            this.elementref_value_false = $("<input class='form-control' type='text' name='"+this.id+"_value_false' id='"+this.id+"_value_false'>").val('False');
            value_unchecked_group.append(this.elementref_value_false);
            parentElementRef.append(value_unchecked_group);

        }
        else{
            parentElementRef.append(alert("I can'helt do that yet!"));
        }
    };

    this.renderView = function(){
        return null;
    };

    this.getValuesObj = function(){
        var values = {};
        values.type = "Checkbox";
        values.id = this.id;
        values.name = this.elementref_name.val();
        values.required = this.elementref_required.val();
        values.value_checked = this.elementref_value_true.val();
        values.value_unchecked = this.elementref_value_false.val();

        return values;

    }

}

function SelectField(id,name){
    this.type = "CheckBox";
    this.id = id;
    this.name = name;
    this.required = false;
    this.options =[];

    this.elementref_name = null;
    this.elementref_required = null;
    this.elementref_multipleselect = null;
    this.elementref_value_true = null;
    this.elementref_value_false = null;
    this.elementref_options_array = [];

    /**
     * renderOptions(parentElementRef,currentValuesObj)
     *
     * parentElementRef: Use a JQuery selector to refer to the parent element the field should be appended to
     * currentvaluesObj: An object containing key-value pairs of all the parameters needed to restore a field
     *
     * renderOptions will append to a given parent element the HTML elements needed to set the values of this field
     * you may also provide values to fill the fields from a saved state.  It will also setup the object so that you
     * can later call getValuesObj() to get the field parameters
     *
     **/
    this.renderOptions = function(parentElementRef,currentValuesObj){
        if(currentValuesObj === null){

            //Name Text Input Field//
            var name_group = $("<div class='form-group'>");
            name_group.append("<label for='name'>Name</label>");
            this.elementref_name = $("<input class='form-control' type='text' name='"+this.id+"_name' id='"+this.id+"_name'>").val(this.name);
            name_group.append(this.elementref_name);
            parentElementRef.append(name_group);
            this.elementref_name.on('keyup',{field_id:this.id},function(event){
                $("#"+event.data.field_id+"_title_link").text($(this).val() + " ("+event.data.field_id+")");
            });

            //Required Select Field
            var required_group = $("<div class='form-group'>");
            required_group.append("<label for='required'>Required</label>");
            this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
                .append(
                    $("<option value='0'>False: Is Optional</option>"),
                    $("<option value='1'> True: Is Required</option>")
                );
            required_group.append(this.elementref_required);
            parentElementRef.append(required_group);

            //Multiple-select Select Field
            var multipleselect_group = $("<div class='form-group'>");
            multipleselect_group.append("<label for='required'>Multiple Select</label>");
            this.elementref_multipleselect = $("<select class='form-control' name='"+this.id+"_multipleselect' id='"+this.id+"_multipleselect'>")
                .append(
                    $("<option value='0'>False: Only 1 option can be selected</option>"),
                    $("<option value='1'> True: Many options can be selected</option>")
                );
            multipleselect_group.append(this.elementref_multipleselect);
            parentElementRef.append(multipleselect_group);

            //Option Values-Labels Group
            var option_values_labels_panel = $("<div class='panel panel-default'>");
            var option_values_labels_group = $("<div class='panel-body'>");
            option_values_labels_group.append("<p >").text("The label is what will be displayed to the user, the value will be what is saved");
            this.elementref_options_display_area = $("<ul class='list-group'>");
            option_values_labels_group.append(this.elementref_options_display_area);
            option_values_labels_panel.append(option_values_labels_group);

            var option_label_group = $("<div class='form-group'>");
            option_label_group.append("<label for='label'>Option Label</label>");
            this.option_label = $("<input class='form-control' type='text' name='"+this.id+"_option_label' id='"+this.id+"_option_label'>");
            option_label_group.append(this.option_label);
            option_values_labels_group.append(option_label_group);

            var option_value_group = $("<div class='form-group'>");
            option_value_group.append("<label for='value'>Option Value</label>");
            this.option_value = $("<input class='form-control' type='text' name='"+this.id+"_option_value' id='"+this.id+"_option_value'>");
            option_value_group.append(this.option_value);
            option_values_labels_group.append(option_value_group);

            var option_add_btn = $("<button type='button' class='btn btn-default' >Add</button>")
                .on('click',{select_field:this},function(event){
                    console.log("Label: "+event.data.select_field.option_label.val() + " with Value: "+event.data.select_field.option_value.val());
                    var new_option_object= event.data.select_field.addOption(event.data.select_field.option_label.val(),event.data.select_field.option_value.val());
                    var new_option_list_item = $("<li class='list-group-item'>");
                    var new_option_div = $("<div>");
                    new_option_div.append($("<span>").text("Label: "+event.data.select_field.option_label.val() + " Value: "+event.data.select_field.option_value.val()));

                    new_option_div.append($("<a href='#remove_item' class='pull-right glyphicon glyphicon-remove'>")
                        .on('click',{select_field:event.data.select_field,option_list_item:new_option_list_item,option_object:new_option_object},function(event){
                            event.data.select_field.delOption(event.data.option_object);
                            event.data.option_list_item.remove();
                        }));
                    new_option_list_item.append(new_option_div);
                    event.data.select_field.elementref_options_display_area.append(new_option_list_item);
                });

            option_values_labels_group.append(option_add_btn);

            parentElementRef.append(option_values_labels_panel);

        }
        else{
            parentElementRef.append(alert("I can'helt do that yet!"));
        }
    };

    this.renderView = function(){
        return null;
    };

    this.getValuesObj = function(){
        var values = {};
        values.type = "Select";
        values.id = this.id;
        values.name = this.elementref_name.val();
        values.required = this.elementref_required.val();
        values.multipleselect = this.elementref_multipleselect.val();
        values.options = this.options;

        return values;

    };


    this.addOption = function(label,value){
        var option_object = {label:label,value:value};
        this.options.push(option_object);
        return option_object;
    };

    this.delOption = function(option_object){
        var index = this.options.indexOf(option_object);
        if(index == -1){
            alert("There was a problem removing the option, it seems to have already been removed.");
        }
        else{
            this.options.splice(index,1);
        }
    }


}

function RadioGroupField(id,name){
    this.type = "RadioGroup";
    this.id = id;
    this.name = name;
    this.required = false;
    this.options =[];

    this.elementref_name = null;
    this.elementref_required = null;
    this.elementref_multipleselect = null;
    this.elementref_value_true = null;
    this.elementref_value_false = null;
    this.elementref_options_array = [];

    /**
     * renderOptions(parentElementRef,currentValuesObj)
     *
     * parentElementRef: Use a JQuery selector to refer to the parent element the field should be appended to
     * currentvaluesObj: An object containing key-value pairs of all the parameters needed to restore a field
     *
     * renderOptions will append to a given parent element the HTML elements needed to set the values of this field
     * you may also provide values to fill the fields from a saved state.  It will also setup the object so that you
     * can later call getValuesObj() to get the field parameters
     *
     **/
    this.renderOptions = function(parentElementRef,currentValuesObj){
        if(currentValuesObj === null){

            //Name Text Input Field//
            var name_group = $("<div class='form-group'>");
            name_group.append("<label for='name'>Name</label>");
            this.elementref_name = $("<input class='form-control' type='text' name='"+this.id+"_name' id='"+this.id+"_name'>").val(this.name);
            name_group.append(this.elementref_name);
            parentElementRef.append(name_group);
            this.elementref_name.on('keyup',{field_id:this.id},function(event){
                $("#"+event.data.field_id+"_title_link").text($(this).val() + " ("+event.data.field_id+")");
            });

            //Required Select Field
            var required_group = $("<div class='form-group'>");
            required_group.append("<label for='required'>Required</label>");
            this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
                .append(
                    $("<option value='0'>False: Is Optional</option>"),
                    $("<option value='1'> True: Is Required</option>")
                );
            required_group.append(this.elementref_required);
            parentElementRef.append(required_group);


            //Option Values-Labels Group
            var option_values_labels_panel = $("<div class='panel panel-default'>");
            var option_values_labels_group = $("<div class='panel-body'>");
            option_values_labels_group.append("<p >").text("The label is what will be displayed to the user, the value will be what is saved");
            this.elementref_options_display_area = $("<ul class='list-group'>");
            option_values_labels_group.append(this.elementref_options_display_area);
            option_values_labels_panel.append(option_values_labels_group);

            var option_label_group = $("<div class='form-group'>");
            option_label_group.append("<label for='label'>Option Label</label>");
            this.option_label = $("<input class='form-control' type='text' name='"+this.id+"_option_label' id='"+this.id+"_option_label'>");
            option_label_group.append(this.option_label);
            option_values_labels_group.append(option_label_group);

            var option_value_group = $("<div class='form-group'>");
            option_value_group.append("<label for='value'>Option Value</label>");
            this.option_value = $("<input class='form-control' type='text' name='"+this.id+"_option_value' id='"+this.id+"_option_value'>");
            option_value_group.append(this.option_value);
            option_values_labels_group.append(option_value_group);

            var option_add_btn = $("<button type='button' class='btn btn-default' >Add</button>")
                .on('click',{select_field:this},function(event){
                    console.log("Label: "+event.data.select_field.option_label.val() + " with Value: "+event.data.select_field.option_value.val());
                    var new_option_object= event.data.select_field.addOption(event.data.select_field.option_label.val(),event.data.select_field.option_value.val());
                    var new_option_list_item = $("<li class='list-group-item'>");
                    var new_option_div = $("<div>");
                    new_option_div.append($("<span>").text("Label: "+event.data.select_field.option_label.val() + " Value: "+event.data.select_field.option_value.val()));

                    new_option_div.append($("<a href='#remove_item' class='pull-right glyphicon glyphicon-remove'>")
                        .on('click',{select_field:event.data.select_field,option_list_item:new_option_list_item,option_object:new_option_object},function(event){
                            event.data.select_field.delOption(event.data.option_object);
                            event.data.option_list_item.remove();
                        }));
                    new_option_list_item.append(new_option_div);
                    event.data.select_field.elementref_options_display_area.append(new_option_list_item);
                });

            option_values_labels_group.append(option_add_btn);

            parentElementRef.append(option_values_labels_panel);

        }
        else{
            parentElementRef.append(alert("I can'helt do that yet!"));
        }
    };

    this.renderView = function(){
        return null;
    };

    this.getValuesObj = function(){
        var values = {};
        values.type = "RadioGroup";
        values.id = this.id;
        values.name = this.elementref_name.val();
        values.required = this.elementref_required.val();
        values.options = this.options;

        return values;

    };


    this.addOption = function(label,value){
        var option_object = {label:label,value:value};
        this.options.push(option_object);
        return option_object;
    };

    this.delOption = function(option_object){
        var index = this.options.indexOf(option_object);
        if(index == -1){
            alert("There was a problem removing the option, it seems to have already been removed.");
        }
        else{
            this.options.splice(index,1);
        }
    }


}