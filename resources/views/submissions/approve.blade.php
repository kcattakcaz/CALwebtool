<div class="container-fluid">

    <form method="post" action="{{action('SubmissionController@approve',compact('submissions'))}}">
        {{csrf_field()}}

        @if($submissions->formdefinition()->first()->judges()->get()->contains(Auth::user()))
            <p>
                You are about to grant final approval for this submission on behalf of all the judges.
                Only one judge needs to perform this action, so be sure to take into account the scores and comments of
                all judges.
                <br>
                All other judges for this form will be notified.
                <br>
                The Team Administrator will be notified of the decision and will take any appropriate steps.  If you have
                any comments, such as partial funding or other special instructions, please include them below.
            </p>
        @else
            You are about to grant final approval of this submission on behalf of all the judges. <strong>You are not a judge.</strong>
            Are you sure the judge's are OK with you approving this submisison instead?
        @endif

        <div class="form-group">
            <label>Message:</label>
            <textarea id="message" name="message"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" value="Approve" class="btn btn-success">
        </div>
    </form>

    <script src="https://cdn.ckeditor.com/4.5.8/basic/ckeditor.js"></script>

    <script>
        var ckeditor_description = CKEDITOR.replace( 'message' );
    </script>

</div>