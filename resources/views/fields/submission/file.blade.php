<div class="input-group">
    <span class="input-group-addon" id="basic-addon1">{{$file_field->get('fieldDef')->name}}</span>
    <input disabled type="text" class="form-control" aria-describedby="basic-addon1" value="{{$file_field->get('submission')}}">
      <span class="input-group-btn">
          <a href="{{action('SubmissionController@retrieveFile',["submissions"=>$submissions,"file"=>$file_field->get('submission')])}}">
            <button class="btn btn-default" type="button">Download</button>
          </a>
      </span>
</div>