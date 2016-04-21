<div class="input-group">
    <span class="input-group-addon" id="basic-addon1">{{$address_field->get('fieldDef')->name}}</span>
    <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{json_encode($address_field->get('submission'))}}">
</div>