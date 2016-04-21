<div class="form-group">
    <label for="{{$select_field->get('id')}}">{{$select_field->get('name')}}</label>
    @if(json_decode($select_field->get('options'))->multipleselect == true)
        <select multiple class="form-control" name="{{$select_field->get('id')}}[]" id="{{$select_field->get('id')}}">
    @else
        <select class="form-control" name="{{$select_field->get('id')}}" id="{{$select_field->get('id')}}">
    @endif
        <option value="">Choose one...</option>
        @foreach(json_decode($select_field->get('options'))->options as $option)
            <option value="{{$option->value}}">{{$option->label}}</option>
        @endforeach
    </select>
</div>