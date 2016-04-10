
@if(json_decode($select_field->get('fieldDef')->options)->multipleselect == false)
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">{{$select_field->get('fieldDef')->name}}</span>
        <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{$select_field->get('submission')}}">
    </div>
@else
    <div class="input-group">
        <span class="input-group-addon" id="basic-addon1">{{$select_field->get('fieldDef')->name}}</span>
        <select disabled multiple class="form-control" aria-describedby="basic-addon1">
@foreach($select_field->get('submission') as $opt)
<option>{{$opt}}</option>
@endforeach
            </select>


    </div>
@endif
