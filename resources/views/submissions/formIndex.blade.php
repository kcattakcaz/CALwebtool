@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{$form->name}}'s submissions:</div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead class="thead-inverse">
                            <tr>
                                <th>ID</th>
                                <th>Form Name</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($submissions as $submission)
                            <tr>
                                <td>{{$submission->id}}</td>
                                <td>{{$submission->name}}</td>
                                <td>{{$submission->email}}</td>
                                <td>{{$submission->status}}</td>
                                <td>
                                    <a href="{{action('SubmissionController@show',compact('submission'))}}">
                                        <button type="button" class="btn btn-default">
                                            <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> View
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
