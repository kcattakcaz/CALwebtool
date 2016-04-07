<div class="form-group">
    <label for="{{$text_field->get('id')}}">{{$text_field->get('name')}}</label>

    @if(json_decode($text_field->get('options'))->text_type == 'any')
        <input class="form-control" name="{{$text_field->get('id')}}" type="text" id="{{$text_field->get('id')}}">
    @elseif(json_decode($text_field->get('options'))->text_type == 'multiline')
        <textarea class="form-control" name="{{$text_field->get('id')}}" id="{{$text_field->get('id')}}"></textarea>
    @elseif(json_decode($text_field->get('options'))->text_type == 'alpha')
        <input class="form-control" name="{{$text_field->get('id')}}" type="text" id="{{$text_field->get('id')}}">
        <span class="help-block">Only letters and numbers</span>
    @elseif(json_decode($text_field->get('options'))->text_type == 'num')
        <input class="form-control" name="{{$text_field->get('id')}}" type="text" id="{{$text_field->get('id')}}">
        <span class="help-block">Numbers only</span>
    @elseif(json_decode($text_field->get('options'))->text_type == 'email')
        <input class="form-control" name="{{$text_field->get('id')}}" type="text" id="{{$text_field->get('id')}}">
        <span class="help-block">E-mail address only</span>
    @else
        <input class="form-control" name="{{$text_field->get('id')}}" type="text" id="{{$text_field->get('id')}}">
    @endif
</div>