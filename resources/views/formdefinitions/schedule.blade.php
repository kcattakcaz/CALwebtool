@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <!--
                <div class="panel panel-default">


                    <div class="panel-heading">Create a Form Definition</div>

                    <div class="panel-body">

                    -->
                <h1>Schedule a Form</h1>

                <p>
                    The form will open to the public on the start date and closes on the end date, but you can extend this.
                </p>

                <p>
                    Judge's will need to submit their scores before the due date, but you can extend this as needed.
                </p>

                <p>
                    <strong>Warning:</strong> it is NOT possible to change the status of an Archived form!  Make sure you are done with the form before you change the status to Archived!
                </p>


                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif


                <form role="form">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="start_date">Submissions Start Date:</label>
                            <input name="start_date" type="text" class="form-control" value="" data-date-format="mm/dd/yy" id="start_date">
                        </div>

                    </div>

                    <div class="row">

                        <div class="form-group col-xs-6">
                            <label for="end_date">Submissions End Date:</label>
                            <input name="end_date" type="text" class="form-control" value="" data-date-format="mm/dd/yy" id="end_date" >
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="scores_date">Judge's Scores Due</label>
                            <input name="scores_date" type="text" class="form-control" value="" data-date-format="mm/dd/yy" id="scores_date" >
                        </div>

                    </div>

                    <div class="row">
                        <div class="form-group col-xs-6">
                        <button id="btn_save_formdef" type="button" class="btn btn-default">Save</button>
                        </div>
                    </div>

                </form>

                <form role="form" method="post" action="{{action('FormDefinitionController@updateStatus',compact('form'))}}">
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="form-group col-xs-6">
                            <label for="status">Status:</label>
                            <select id="status" name="status" class="form-control">
                                @if($form->status == "Scheduled" || $form->status == "Drafting")
                                <option value="Drafting">Draft</option>
                                <option value="Scheduled">Scheduled</option>
                                @endif
                                <option value="Accepting">Accepting Submissions</option>
                                <option value="Reviewing">Waiting for Judges' Scores</option>
                                <option value="Scored">Scoring Complete</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>

                    </div>


                    <button id="btn_save_formdef" type="submit" class="btn btn-default">Save</button>


                </form>


                <!--</div> -->
                <!--</div>-->
            </div>
        </div>
    </div>


    <script src="{{secure_asset('js/fieldcontroller.js')}}">

    </script>

    <link rel="stylesheet" href="{{secure_asset('css/datepicker.css')}}">

    <script src="https://cdn.ckeditor.com/4.5.8/standard/ckeditor.js">
    </script>


    <script src="{{secure_asset('js/bootstrap-datepicker.js')}}">
    </script>

    <script>


        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var start_date = $('#start_date').datepicker({
            onRender: function(date) {
                return date.valueOf() < now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > end_date.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                end_date.setValue(newDate);
            }
            start_date.hide();
            $('#end_date')[0].focus();
        }).data('datepicker');

        $('#start_date').datepicker('setValue',"{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$form->submissions_start)->toDateString()}} ");

        var end_date = $('#end_date').datepicker({
            onRender: function(date) {
                return date.valueOf() <= start_date.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() > scores_date.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate() + 1);
                scores_date.setValue(newDate);
            }
            end_date.hide();
            $('#scores_date')[0].focus();
        }).data('datepicker');

        $('#end_date').datepicker('setValue',"{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$form->submissions_end)->toDateString()}} ");

        var scores_date = $('#scores_date').datepicker({
            onRender: function(date) {
                return date.valueOf() <= end_date.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            scores_date.hide();
            $('#scores_date')[0].focus();
        }).data('datepicker');

        $('#scores_date').datepicker('setValue',"{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s",$form->scores_due)->toDateString()}} ");

        $("#status").val('{{$form->status}}');
    </script>

@endsection
