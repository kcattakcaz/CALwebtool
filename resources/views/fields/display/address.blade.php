<div class="form-group">

    <input type="hidden" name="{{$address_field->get('id')}}[]"/>

    <div class="form-group">
        <label for="{{$address_field->get('id')}}_line1">Address Line 1</label>
        <input class="form-control" name="{{$address_field->get('id')}}_line1" type="text" id="{{$address_field->get('id')}}_line1">
    </div>

    <div class="form-group">
        <label for="{{$address_field->get('id')}}_line2">Address Line 2</label>
        <input class="form-control" name="{{$address_field->get('id')}}_line2" type="text" id="{{$address_field->get('id')}}_line2">
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="{{$address_field->get('id')}}_state">State</label>
            <input class="form-control" name="{{$address_field->get('id')}}_state" type="text" id="{{$address_field->get('id')}}_state">
        </div>

        <div class="form-group col-md-4">
            <label for="{{$address_field->get('id')}}_country">Country</label>
            <input class="form-control" name="{{$address_field->get('id')}}_country" type="text" id="{{$address_field->get('id')}}_country">
        </div>

        <div class="form-group col-md-4">
            <label for="{{$address_field->get('id')}}_zip">Zip Code</label>
            <input class="form-control" name="{{$address_field->get('id')}}_zip" type="text" id="{{$address_field->get('id')}}_zip">
        </div>
    </div>

    <script>
        $("#{{$address_field->get('id')}}_line1").on('keyup',function(){
            updateAddress();
        });
        $("#{{$address_field->get('id')}}_line2").on('keyup',function(){
            updateAddress();
        });
        $("#{{$address_field->get('id')}}_state").on('keyup',function(){
            updateAddress();
        });
        $("#{{$address_field->get('id')}}_country").on('keyup',function(){
            updateAddress();
        });
        $("#{{$address_field->get('id')}}_zip").on('keyup',function(){
            updateAddress();
        });

        function updateAddress(){
            var addressObject = {};
            addressObject.line1 = $("{{$address_field->get('id')}}_line1").val();
            addressObject.line1 = $("{{$address_field->get('id')}}_line2").val();
            addressObject.line1 = $("{{$address_field->get('id')}}_state").val();
            addressObject.line1 = $("{{$address_field->get('id')}}_country").val();
            addressObject.line1 = $("{{$address_field->get('id')}}_zip").val();
            $("#{{$address_field->get('id')}}").val(JSON.stringify(addressObject));
        }

    </script>
</div>