<div class="container-fluid">

    <form method="post" action="{{action('SubmissionController@sendRejectNotify',compact('submissions'))}}">
        {{csrf_field()}}
        <div class="form-group">
            <label>From:</label>
            <input disabled name="sender" class="form-control" type="text" value="{{Auth::user()->email}}">
        </div>

        <div class="form-group">
            <label>To:</label>
            <input name="recipient" class="form-control" type="text" value="{{$submissions->email}}">
        </div>

        <div class="form-group">
            <label>Subject:</label>
            <input type="text" name="subject" class="form-control">
        </div>

        <div class="form-group">
            <label>Message:</label>
            <textarea id="message" name="message"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" value="Reject and Send" class="btn btn-danger">
        </div>
    </form>

    <script src="https://cdn.ckeditor.com/4.5.8/basic/ckeditor.js"></script>

    <script>
        var ckeditor_description = CKEDITOR.replace( 'message' );
    </script>

</div>