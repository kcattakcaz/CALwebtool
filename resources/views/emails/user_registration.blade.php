<!DOCTYPE html>

<html lang="en-US">

    <head>
        <meta charset="utf-8">
    </head>

    <body>

        <h4>Activate Your Account</h4>

        <p>Hi {{$user->name}},</p>

        <p>Your account for CAL Awards has been created, but you need to set your password.</p>

        <a href="{{action('UserController@register',compact('user','token'))}}">Click here to activate your account</a>

        <br>
        <br>
        <hr>

        <p>This is an automatic notification from CAL Awards.  If you no longer wish to receive these messages, <a href="#">click here</a> to update your user preferences</p>

    </body>


</html>
