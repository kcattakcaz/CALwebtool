/**
 * Created by Zach Jaghory on 3/25/16.
 */


function FieldController(parentElement, previousValues)  {
    this.field_objects = {};
    this.current_field_count = 1;
    this.supported_types = {};
    this.errorList = [];
    var newField = null;
    if (previousValues !== null){
        console.log(previousValues);
        for (var i = 0; i < previousValues.length; i++){
            newField = null;
            console.log(previousValues[i]);
            if(previousValues[i].type == "Text"){
                newField = new TextField(previousValues[i].id,previousValues[i].name,previousValues[i]);
            }
            else if(previousValues[i].type == "Checkbox"){
                newField = new CheckBoxField(previousValues[i].id,previousValues[i].name,previousValues[i]);
            }
            else if(previousValues[i].type == "Select"){
                newField = new SelectField(previousValues[i].id,previousValues[i].name,previousValues[i]);
            }
            else if(previousValues[i].type == "RadioGroup"){
                newField = new RadioGroupField(previousValues[i].id,previousValues[i].name,previousValues[i]);
            }
            else if(previousValues[i].type == "Address"){
                newField = new AddressField(previousValues[i].id,previousValues[i].name,previousValues[i]);
            }
            else if(previousValues[i].type == "File"){
                newField = new FileField(previousValues[i].id,previousValues[i].name,previousValues[i]);
            }

            if(newField === null){
                console.log("Failed to create field of given type "+type+" (the attempt to create the object returned null)");
                alert("There was a problem creating a field of the type you selected, please sign out, close all browser windows, then try again.  If the problem persists, please refer to the documentation.");
                return;
            }

            var field_id = previousValues[i].id;
            this.field_objects[field_id] =  newField;
            var name = previousValues[i].name;
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
            var collapse_link = $("<a href='#"+field_id+"'>").attr('data-toggle','collapse').attr('id',field_id+"_collapse_link").text("Collapse");
            panel_footer.append(collapse_link);


            newField.renderOptions(panel_body,null);
            panel_collapse.append(panel_body);
            panel_collapse.append(panel_footer);
            panel.append(panel_collapse);
            panel_group.append(panel);
            $('#formdef_viewer').append(panel_group);
        }
    }

    this.newField = function(type,name){

        var field_id = this.getUniqueFieldId(type);
        console.log("New Field created with ID: "+field_id);

        var newField = null;

        if(type == "Text"){
            newField = new TextField(field_id,name,null);
        }
        else if(type == "Checkbox"){
            newField = new CheckBoxField(field_id,name,null);
        }
        else if(type == "Select"){
            newField = new SelectField(field_id,name,null);
        }
        else if(type == "Radio"){
            newField = new RadioGroupField(field_id,name,null);
        }
        else if(type == "Address"){
            newField = new AddressField(field_id,name,null);
        }
        else if(type == "File"){
            newField = new FileField(field_id,name,null);
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
        var collapse_link = $("<a href='#"+field_id+"'>").attr('data-toggle','collapse').attr('id',field_id+"_collapse_link").text("Collapse");
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

function TextField(id,name,currentValuesObj){
    if(currentValuesObj === null) {
        this.type = "Text";
        this.id = id;
        this.name = name;
        this.required = false;
        //this.is_multi_line = false;
        this.text_type = "Plain";
    }else{
        var field_options = JSON.parse(currentValuesObj.options);
        this.type = currentValuesObj.type;
        this.id = currentValuesObj.id;
        this.name = currentValuesObj.name;
        this.required = field_options.required;
        this.text_type = field_options.text_type;
    }

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
    this.renderOptions = function(parentElementRef){
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
        required_group.append("<label for='required'>Is this required to submit the form?</label>");
        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
            .append(
                $("<option value='1'> Required</option>"),
                $("<option value='0'> Not required</option>")
            );
        this.elementref_required.val(this.required);
        required_group.append(this.elementref_required);
        parentElementRef.append(required_group);

        //TextType Select Field
        var texttype_group = $("<div class='form-group'>");
        texttype_group.append("<label for='required'>Text Type</label>");
        this.elementref_texttype = $("<select class='form-control' name='"+this.id+"_texttype' id='"+this.id+"_texttype'>")
            .append(
                $("<option value='any'>Any Text</option>"),
                $("<option value='multiline'>Multiline Text Area</option>"),
                $("<option value='num'>Numbers Only</option>"),
                $("<option value='alpha'>Alphabetic Only</option>"),
                $("<option value='email'>E-Mail Field</option>"),
                $("<option value='phone'>Telephone Field</option>")
            );
        texttype_group.append(this.elementref_texttype);
        parentElementRef.append(texttype_group);
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
        //values.maxlength = parseInt(,10);
        //values.minlength = parseInt(this.elementref_minlength.val(),10);
        return values;
    }

}

function CheckBoxField(id,name,currentValuesObj){
    if (currentValuesObj === null){
        this.type = "CheckBox";
        this.id = id;
        this.name = name;
        this.required = false;
        this.value_true = true;
        this.value_false = false;
    } else {
        var field_options = JSON.parse(currentValuesObj.options);
        this.type = currentValuesObj.type;
        this.id = currentValuesObj.id;
        this.name = currentValuesObj.name;
        console.log("checkbox field options");
        console.log(field_options);
        this.required = field_options.required;
        console.log("set value as "+this.required);
        this.value_true = field_options.value_true;
        this.value_false = field_options.value_false;
    }

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
    this.renderOptions = function(parentElementRef){
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
        required_group.append("<label for='required'>Is this required to submit the form?</label>");
        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
            .append(
                $("<option value='1'> Required</option>"),
                $("<option value='0'> Not required</option>")
            );
        this.elementref_required.val(this.required);
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

function SelectField(id,name,currentValuesObj){
    if (currentValuesObj === null){
        this.type = "Select";
        this.id = id;
        this.name = name;
        this.required = false;
        this.options =[];
    } else {
        var field_options = JSON.parse(currentValuesObj.options);
        this.type = currentValuesObj.type;
        this.id = currentValuesObj.id;
        this.name = currentValuesObj.name;
        this.required = field_options.required;
        this.options = [];
        //this.options = currentValuesObj.options;
    }

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
    this.renderOptions = function(parentElementRef){
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
        required_group.append("<label for='required'>Is this required to submit the form?</label>");
        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
            .append(
                $("<option value='1'> Required</option>"),
                $("<option value='0'> Not required</option>")
            );
        this.elementref_required.val(this.required);
        required_group.append(this.elementref_required);
        parentElementRef.append(required_group);

        //Multiple-select Select Field
        var multipleselect_group = $("<div class='form-group'>");
        multipleselect_group.append("<label for='required'>Can the user select more than one option?</label>");
        this.elementref_multipleselect = $("<select class='form-control' name='"+this.id+"_multipleselect' id='"+this.id+"_multipleselect'>")
            .append(
                $("<option value='0'>Only allow one option</option>"),
                $("<option value='1'>Allow many options</option>")
            );
        multipleselect_group.append(this.elementref_multipleselect);
        parentElementRef.append(multipleselect_group);

        //Option Values-Labels Group
        var option_values_labels_panel = $("<div class='panel panel-default'>");
        var option_values_labels_group = $("<div class='panel-body'>");
        option_values_labels_group.append("<p >").text("Enter what you want to be seen in the Label field. Value field is for what is saved on the server.");
        this.elementref_options_display_area = $("<ul class='list-group'>");
        option_values_labels_group.append(this.elementref_options_display_area);
        option_values_labels_panel.append(option_values_labels_group);

        var option_label_group = $("<div class='form-group'>");
        option_label_group.append("<label for='label'>Label</label>");
        this.option_label = $("<input class='form-control' type='text' name='"+this.id+"_option_label' id='"+this.id+"_option_label'>");
        option_label_group.append(this.option_label);
        option_values_labels_group.append(option_label_group);

        this.option_label.on('keyup',{field_id:this.id},function(event){
            $("#"+event.data.field_id+"_option_value").val($(this).val());
        });

        var option_value_group = $("<div class='form-group'>");
        option_value_group.append("<label for='value'>Value</label>");
        this.option_value = $("<input class='form-control' type='text' name='"+this.id+"_option_value' id='"+this.id+"_option_value'>");
        option_value_group.append(this.option_value);

        //Load prior values if they exist

        //console.log("selectopts: "+currentValuesObj.options);
       // var field_options = JSON.parse(currentValuesObj.options);
        //console.log(field_options.options);

        if(currentValuesObj !== null){
            var field_options = JSON.parse(currentValuesObj.options);
        }
        else{
            var field_options = {options:null};
        }

        if (field_options.options !== null){
            console.log("Previous values exist and are loading!");
            for (var i=0; i< field_options.options.length; i++ ) {
                console.log("Label: " + field_options.options[i].label + " with Value: " + field_options.options[i].value);
                var new_option_object= this.addOption(field_options.options[i].label,field_options.options[i].value);
                var new_option_list_item = $("<li class='list-group-item'>'");
                var new_option_div = $("<div>");
                new_option_div.append("<span>").text("Label: "+field_options.options[i].label + " Value:" + field_options.options[i].value);
                new_option_div.append($("<a href='#remove_item' class='pull-right glyphicon glyphicon-remove'>"))
                    .on('click',{select_field:this,option_list_item:new_option_list_item,option_object:new_option_object},function(event){
                        event.data.select_field.delOption(event.data.option_object);
                        event.data.option_list_item.remove();
                    });
                new_option_list_item.append(new_option_div);
                this.elementref_options_display_area.append(new_option_list_item);
            }
        }

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
        console.log(option_object);
        console.log(this.options);
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

function RadioGroupField(id,name,currentValuesObj){
    if (currentValuesObj === null){
        this.type = "RadioGroup";
        this.id = id;
        this.name = name;
        this.required = false;
        this.options =[];
    } else {
        this.type = currentValuesObj.type;
        this.id = currentValuesObj.id;
        this.name = currentValuesObj.name;
        this.required = currentValuesObj.required;
        this.options = [];
       // this.options = currentValuesObj.options;
    }

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
    this.renderOptions = function(parentElementRef){
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
        required_group.append("<label for='required'>Is this required to submit the form?</label>");
        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
            .append(
                $("<option value='1'> Required</option>"),
                $("<option value='0'> Not required</option>")
            );
        this.elementref_required.val(this.required);
        required_group.append(this.elementref_required);
        parentElementRef.append(required_group);


        //Option Values-Labels Group
        var option_values_labels_panel = $("<div class='panel panel-default'>");
        var option_values_labels_group = $("<div class='panel-body'>");
        option_values_labels_group.append("<p >").text("Enter what you want to be seen in the Label field. Value field is for what is saved on the server.");
        this.elementref_options_display_area = $("<ul class='list-group'>");
        option_values_labels_group.append(this.elementref_options_display_area);
        option_values_labels_panel.append(option_values_labels_group);

        var option_label_group = $("<div class='form-group'>");
        option_label_group.append("<label for='label'>Label</label>");
        this.option_label = $("<input class='form-control' type='text' name='"+this.id+"_option_label' id='"+this.id+"_option_label'>");
        this.option_label.on('keyup',{field_id:this.id},function(event){
            $("#"+event.data.field_id+"_option_value").val($(this).val());
        });
        option_label_group.append(this.option_label);
        option_values_labels_group.append(option_label_group);

        var option_value_group = $("<div class='form-group'>");
        option_value_group.append("<label for='value'>Value</label>");
        this.option_value = $("<input class='form-control' type='text' name='"+this.id+"_option_value' id='"+this.id+"_option_value'>");
        option_value_group.append(this.option_value);
        option_values_labels_group.append(option_value_group);


        //Load prior values if they exist

        //console.log("selectopts: "+currentValuesObj.options);
        if(currentValuesObj !== null){
            var field_options = JSON.parse(currentValuesObj.options);
        }
        else{
            var field_options = {options:null};
        }

        //console.log(field_options.options);


        if (field_options.options !== null){
            console.log("Previous values exist and are loading!");
            for (var i=0; i< field_options.options.length; i++ ) {
                console.log("Label: " + field_options.options[i].label + " with Value: " + field_options.options[i].value);
                var new_option_object= this.addOption(field_options.options[i].label,field_options.options[i].value);
                var new_option_list_item = $("<li class='list-group-item'>'");
                var new_option_div = $("<div>");
                new_option_div.append("<span>").text("Label: "+field_options.options[i].label + " Value:" + field_options.options[i].value);
                new_option_div.append($("<a href='#remove_item' class='pull-right glyphicon glyphicon-remove'>"))
                    .on('click',{select_field:this,option_list_item:new_option_list_item,option_object:new_option_object},function(event){
                        event.data.select_field.delOption(event.data.option_object);
                        event.data.option_list_item.remove();
                    });
                new_option_list_item.append(new_option_div);
                this.elementref_options_display_area.append(new_option_list_item);
            }
        }


        var option_add_btn = $("<button type='button' class='btn btn-default' >Add</button>")
            .on('click',{select_field:this},function(event){
                console.log("Label: "+event.data.select_field.option_label.val() + " with Value: "+event.data.select_field.option_value.val());
                var new_option_object= event.data.select_field.addOption(event.data.select_field.option_label.val(),event.data.select_field.option_value.val());
                var new_option_list_item = $("<li class='list-group-item'>");
                var new_option_div = $("<div>");
                new_option_div.append($("<span>").text("Label: "+event.data.select_field.option_label.val()));
                new_option_div.append($("<br>"));
                new_option_div.append($("<span>").text("Value: "+event.data.select_field.option_value.val()));

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

function AddressField(id,name,currentValuesObj){
    if (currentValuesObj === null){
        this.type = "Address";
        this.id = id;
        this.name = name;
        this.required = false;
        this.text_type = "Plain";
    } else {
        this.type = currentValuesObj.type;
        this.id = currentValuesObj.id;
        this.name = currentValuesObj.name;
        this.required = currentValuesObj.required;
        this.text_type = currentValuesObj.text_type;
    }

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
    this.renderOptions = function(parentElementRef){
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
        required_group.append("<label for='required'>Is this required to submit the form?</label>");
        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
            .append(
                $("<option value='1'> Required</option>"),
                $("<option value='0'> Not required</option>")
            );
        required_group.append(this.elementref_required);
        parentElementRef.append(required_group);
    };

    this.renderView = function(){
        return null;
    };

    this.getValuesObj = function(){
        var values = {};

        values.type = "Address";
        values.id = this.id;
        values.name = this.elementref_name.val();
        values.required = this.elementref_required.val();
        return values;
    }

}


function FileField(id,name,currentValuesObj){
    if(currentValuesObj === null) {
        this.type = "File";
        this.id = id;
        this.name = name;
        this.required = false;
    }else{
        var field_options = JSON.parse(currentValuesObj.options);
        this.type = currentValuesObj.type;
        this.id = currentValuesObj.id;
        this.name = currentValuesObj.name;
        this.required = field_options.required;
    }

    this.elementref_name = null;
    this.elementref_required = null;
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
    this.renderOptions = function(parentElementRef){
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
        required_group.append("<label for='required'>Is this required to submit the form?</label>");
        this.elementref_required = $("<select class='form-control' name='"+this.id+"_required' id='"+this.id+"_required'>")
            .append(
                $("<option value='1'> Required</option>"),
                $("<option value='0'> Not required</option>")
            );
        this.elementref_required.val(this.required);
        this.elementref_required.val("1");
        required_group.append(this.elementref_required);
        parentElementRef.append(required_group);
    };

    this.getValuesObj = function(){
        var values = {};

        values.type = "File";
        values.id = this.id;
        values.name = this.elementref_name.val();
        values.required = this.elementref_required.val();
        return values;
    }

}