<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
        <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>

        <!-- Styles -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
        {{-- <link href="{{ elixir('custom/app.custom') }}" rel="stylesheet"> --}}
        <style>
            body {
                font-family: 'Lato';
            }

            .fa-btn {
                margin-right: 6px;
            }
        </style>

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

        <h1>{{$formDef->name}}</h1>

        <p>{{$formDef->description}}</p>

        <form method="post" action="{{action('SubmissionController@store',compact('formDef'))}}">

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
                @endif
            @endforeach

            <button class="btn btn-default btn-block" id="btn_submit_form">Submit</button>
        </form>
    </div>

    </body>
</html>