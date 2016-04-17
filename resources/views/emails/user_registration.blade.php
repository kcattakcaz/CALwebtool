<html>

    <head>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    </head>

    <body>

        <h4>Activate Your Account</h4>

        <p>Hi {{$user->name}},</p>

        <p>Your account for CAL Awards has been created, but you need to set your password.</p>

        <a href="{{action('UserController@activate',compact('user','register_token'))}}">Click here to activate your account</a>

        <br>
        <br>
        <hr>

        <p>This is an automatic notification from CAL Awards.  If you no longer wish to receive these messages, <a href="#">click here</a> to update your user preferences</p>

    </body>


</html>
