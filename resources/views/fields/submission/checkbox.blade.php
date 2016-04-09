<div class="input-group">
    <span class="input-group-addon" id="basic-addon1">{{$checkbox_field->get('fieldDef')->name}}</span>
    @if($checkbox_field->get('submission') == true)
        <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{json_decode($checkbox_field->get('fieldDef')->options)->value_checked}}">
    @else
        <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{json_decode($checkbox_field->get('fieldDef')->options)->value_unchecked}}">
    @endif
</div>