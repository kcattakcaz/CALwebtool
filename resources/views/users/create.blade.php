@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Register a User</div>

                <div class="panel-body">
                   <p>
                       A user
                   </p>

                    <p>
                        Each group is managed by a Group Administrator, who can add/remove users from the group, as well
                        as reset their passwords.  The Group Administrator can also recover some deleted objects, unlock
                        locked objects, and view detailed audit information.  They can also promote additional users to
                        be Group Administrators, however most users should not have this level of permissions.
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
                            <label for="name">User Name:</label>
                            <input name="name" type="text" class="form-control" id="name">
                        </div>

                        <div class="form-group">
                            <label for="email">E-Mail:</label>
                            <input name="email" type="email" class="form-control" id="email">
                        </div>

                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input name="password" type="password" class="form-control" id="password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password:</label>
                            <input name="password_confirmation" type="password" class="form-control" id="password_confirmation">
                        </div>

                        <div class="form-group">
                            <label for="initial_group">Initial Group:</label>
                            <select name="initial_group" class="form-control" id="initial_group">
                                @foreach($groups as $group)
                                    <option value="{{$user->id}}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
