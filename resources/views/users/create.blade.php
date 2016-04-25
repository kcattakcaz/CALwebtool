@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Register a User</div>

                <div class="panel-body">
                   <p>
                       Create a new user and add them to a team.  Then you can assign them roles.
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


                    <form role="form" method="post" action="{{action('UserController@store')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input name="name" type="text" class="form-control" id="name">
                        </div>

                        <div class="form-group">
                            <label for="email">E-Mail:</label>
                            <input name="email" type="email" class="form-control" id="email">
                        </div>

                        <div class="form-group">
                            <label for="initial_group">Initial Team:</label>
                            <select multiple="multiple" name="initial_group[]" class="form-control" id="initial_group">
                                @foreach($groups as $group)
                                    <option value="{{$group->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Create</button>
                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
