
@if($active)
    <div role="tabpanel" class="tab-pane active" id="{{$group->id}}">
@else
    <div role="tabpanel" class="tab-pane" id="{{$group->id}}">
@endif
    <div class="well">

        @foreach($group->formdefinitions()->get() as $form)
            @if($form->status == 'Reviewing')
                <h4>{{$form->name}} (Ready for Scoring)</h4>
                <div class="list-group">
                    <a href="{{action('SubmissionController@unscored',compact('form'))}}" class="list-group-item">New Submissions<span class="badge pull-right">{{\CALwebtool\Http\Controllers\SubmissionController::getUnscored(Auth::user(),$form)->count()}}</span></a>
                    <a href="{{action('SubmissionController@scored',compact('form'))}}" class="list-group-item">Submissions You've Scored <span class="badge pull-right">{{\CALwebtool\Http\Controllers\SubmissionController::getScored(Auth::user(),$form)->count()}}</span></a>
                    <a href="{{action('SubmissionController@completed',compact('form'))}}" class="list-group-item">Submissions Completed <span class="badge pull-right">{{\CALwebtool\Http\Controllers\SubmissionController::getCompleted($form)->count()}}</span></a>
                </div>
            @elseif($form->status == 'Scored')
                <hr>
                <h4>{{$form->name}} (Recently Completed)</h4>
                <div class="list-group">
                    <a href="#" class="list-group-item">
                        New Submissions
                    </a>
                </div>
            @endif

        @endforeach

        @if($group->formdefinitions()->where('status','=','Reviewing')->orWhere('status','=','Scored')->get()->count() == 0)
            <h4>There's nothing to do</h4>
        @endif
    </div>

</div>