
@if($active)
    <div role="tabpanel" class="tab-pane active" id="{{$group->id}}">
        @else
            <div role="tabpanel" class="tab-pane" id="{{$group->id}}">
                @endif
                <div class="well">

                    <div class="list-group">
                        @foreach($group->formdefinitions()->where('status','!=','Archived')->get() as $form)
                            @if($form->status == "Reviewing")
                                <a href="{{action('FormDefinitionController@show',compact('form'))}}" class="list-group-item">{{$form->name}} <em class="pull-right">Waiting for Judges' Scores</em></a>
                            @elseif($form->status == "Drafting")
                                <a href="{{action('FormDefinitionController@show',compact('form'))}}" class="list-group-item">{{$form->name}} <em class="pull-right">Draft</em></a>
                            @else
                                <a href="{{action('FormDefinitionController@show',compact('form'))}}" class="list-group-item">{{$form->name}} <em class="pull-right">{{$form->status}}</em></a>
                            @endif
                        @endforeach
                    </div>
                    @if($group->formdefinitions()->where('status','!=','Archived')->get()->count() == 0)
                        <h4>There's nothing to do</h4>
                    @endif
                </div>

            </div>