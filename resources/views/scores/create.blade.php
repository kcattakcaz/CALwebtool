@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">{{Auth::user()->name}}'s Score for {{$submissions->name}}'s Submission</div>

                <div class="panel-body">
                    <form>

                        <div class="form-group">
                            <label for="numerical_score">Score:</label>
                            <select id="numerical_score" name="numerical_score" class="form-control">
                                <option value="0">0- Lowest</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10- Highest</option>
                            </select>
                        </div>

                        <div class="form-group">

                            <label for="comment">Comment:</label>

                            <textarea id="comment" name="comment">
                            </textarea>

                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.5.8/basic/ckeditor.js"></script>

<script>

    CKEDITOR.replace('comment');

</script>

@endsection
