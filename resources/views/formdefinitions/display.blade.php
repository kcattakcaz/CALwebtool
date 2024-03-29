<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{$formDef->name}}</title>

        <!-- Fonts -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

        <!-- Styles -->


        @if($formDef->use_custom_css)
            <link rel="stylesheet" href="{{$formDef->custom_cs_url}}">
        @else
            <link rel='stylesheet' href="{{secure_asset('css/forms.css')}}">
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        @endif

        <!-- JavaScripts -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        {{-- <script src="{{ elixir('js/app.js') }}"></script> --}}

    </head>

    <body id="app-layout">

    @if (Session::has('flash_notification.message'))
        @include('flash::message')
    @endif


    <div class="container">
        <div>

        <h1>{{$formDef->name}}</h1>
            
            <div class="form-group">
                {!! $formDef->description!!}
            </div>

            @if(count(session('field_validation_errors')))
                <div class="alert alert-danger">
                    <ul>
                        @foreach(session('field_validation_errors') as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                </div>
            @else
                No errors
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" enctype="multipart/form-data" action="{{action('SubmissionController@store',compact('formDef'))}}">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input name="name" type="text" class="form-control" id="name">
                </div>

                <div class="form-group">
                    <label for="email">E-Mail:</label>
                    <input name="email" type="text" class="form-control" id="email">
                </div>


                @foreach($fields as $field)
                    @if($field->get('type') =='Text')
                        @include('fields.display.text',['text_field' => $field])
                    @elseif($field->get('type') == 'Select')
                        @include('fields.display.select',['select_field'=>$field])
                    @elseif($field->get('type') == 'Checkbox')
                        @include('fields.display.checkbox',['checkbox_field'=>$field])
                    @elseif($field->get('type') == 'RadioGroup')
                        @include('fields.display.radiogroup',['radiogroup_field'=>$field])
                    @elseif($field->get('type') == 'Address')
                        @include('fields.display.address',['address_field'=>$field])
                    @elseif($field->get('type') == "File")
                        @include('fields.display.file',['file_field'=>$field])
                    @endif
                @endforeach

                <button class="btn btn-default btn-block" id="btn_submit_form">Submit</button>
            </form>
        </div>
    </div>

    </body>
</html>