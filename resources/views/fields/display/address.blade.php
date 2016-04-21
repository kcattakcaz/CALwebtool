<div class="form-group">

    <label for="{{$address_field->get('id')}}">{{$address_field->get('name')}}</label>
    <input type="hidden" name="{{$address_field->get('id')}}[]"/>

    <div class="form-group">
        <label for="{{$address_field->get('id')}}_line1">Address Line 1</label>
        <input class="form-control" name="{{$address_field->get('id')}}_line1" type="text" id="{{$address_field->get('id')}}_line1">
    </div>

    <div class="form-group">
        <label for="{{$address_field->get('id')}}_line2">Address Line 2</label>
        <input class="form-control" name="{{$address_field->get('id')}}_line2" type="text" id="{{$address_field->get('id')}}_line2">
    </div>

    <div class="form-group col-md-4">
        <label for="{{$address_field->get('id')}}_city">City</label>
        <input class="form-control" name="{{$address_field->get('id')}}_city" type="text" id="{{$address_field->get('id')}}_city">
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="{{$address_field->get('id')}}_state">State</label>
            <select class="form-control" name="{{$address_field->get('id')}}_state" id="{{$address_field->get('id')}}_state">
                    <option value="AL">Alabama</option>
                    <option value="AK">Alaska</option>
                    <option value="AZ">Arizona</option>
                    <option value="AR">Arkansas</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="DC">District Of Columbia</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="IA">Iowa</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="ME">Maine</option>
                    <option value="MD">Maryland</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MS">Mississippi</option>
                    <option value="MO">Missouri</option>
                    <option value="MT">Montana</option>
                    <option value="NE">Nebraska</option>
                    <option value="NV">Nevada</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NY">New York</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VT">Vermont</option>
                    <option value="VA">Virginia</option>
                    <option value="WA">Washington</option>
                    <option value="WV">West Virginia</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WY">Wyoming</option>
            </select>
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