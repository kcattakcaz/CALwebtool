<html>


<body>

<p>
Hi {{$admin->name}},
</p>

<p>
The judges of {{$form->name}} have rejected the submission of {{$submission->name}} ({{$submission->email}}), with the following message:
</p>
{!! $content !!}

<br>
<br>
<hr>

<p>
    You can view the submission <a href="{{action('SubmissionController@show',compact('submission'))}}">here</a>.

</p>

<br>
<br>
<hr>

<p>This is an automatic notification from CAL Awards.  If you no longer wish to receive these messages, <a href="{{action("UserController@show",compact('admin'))}}">click here</a> to update your user preferences</p>


</body>

</html>