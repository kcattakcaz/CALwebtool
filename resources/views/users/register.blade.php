@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <h1>Register <small>Set a password</small></h1>

            <form method="post" action="{{action('UserController@activate',compact('user','token'))}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label>Password: </label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="form-group">
                    <label>Confirm Password:</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <input type="submit" value="Get Started" class="btn btn-lg btn-success">
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
