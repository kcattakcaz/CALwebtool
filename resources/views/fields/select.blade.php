<div class="form-group">
    <label for="{{$select_field->get('id')}}">{{$select_field->get('name')}}</label>
    <select class="form-control" name="{{$select_field->get('id')}}" id="{{$select_field->get('id')}}">
        @foreach(json_decode($select_field->get('options'))->options as $option)
            <option value="{{$option->value}}">{{$option->label}}</option>
        @endforeach
    </select>
</div>