<div class="container-fluid">

    <form method="post" action="{{action('SubmissionController@deny',compact('submissions'))}}">
        {{csrf_field()}}

        @if($submissions->formdefinition()->first()->judges()->get()->contains(Auth::user()))
            <p>
                You are about to deny this submission on behalf of all the judges.
                Only one judge needs to perform this action, so be sure to take into account the scores and comments of
                all judges.
                <br>
                The other judges will not be notified.
                <br>
                The submission will be moved to the Team Administrator's rejection queue, and they will handle informing
                the
            </p>
        @else
            You are about to reject this submission on behalf of all the judges. <strong>You are not a judge.</strong>
            Are you sure the judge's are OK with you approving this submisison instead?
        @endif

        <div class="form-group">
            <label>Message:</label>
            <textarea id="message" name="message"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" value="Reject" class="btn btn-danger">
        </div>
    </form>

    <script src="https://cdn.ckeditor.com/4.5.8/basic/ckeditor.js"></script>

    <script>
        var ckeditor_description = CKEDITOR.replace( 'message' );
    </script>

</div>