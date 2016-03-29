@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Create A Group</div>

                <div class="panel-body">
                   <p> Groups represent a collection of Users, Forms and Ballots that serve a common purpose.  Examples include
                    "College of Arts and Letters", "Department of Something", and "Scholarship Committee".
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


                    <form role="form" method="post" action="{{action('GroupController@store')}}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="name">Group Name:</label>
                            <input name="name" type="text" class="form-control" id="name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" rows="3" class="form-control" id="description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="initial_group_administrator">Initial Group Administrator:</label>
                            <select name="initial_group_administrator" class="form-control" id="initial_group_administrator">
                                @foreach($users as $user)
                                    <option value="{{$user->name}}">{{$user->name}}</option>
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
