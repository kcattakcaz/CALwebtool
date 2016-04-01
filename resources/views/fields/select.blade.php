<div class="form-group">
    <label for="{{$field->get('id')}}">{{$field->get('name')}}</label>
    <select class="form-control" name="{{$field->get('id')}}" id="{{$field->get('id')}}">
        @foreach(json_decode($field->get('options'))->options as $option)
            <option value="{{$option->value}}">{{$option->label}}</option>
        @endforeach
    </select>
</div>