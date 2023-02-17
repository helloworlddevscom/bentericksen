@extends('user.wrap')

@section('content')

    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3><a href="/admin/job-description">Forms</a> / Add New</h3>
                </div>
                <div class="col-md-12 content">
                    {!! Form::open(['url' => '/user/forms/' . $form->id, 'class' => 'form-horizontal', 'method' => 'put']) !!}
                    <div class="form-group">
                        <label for="job_description" class="col-md-3 col-md-offset-2 control-label">Form Name:</label>
                        <div class="col-md-3">
                            <p class="form-control-static">{{ $form->name }}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_industry" class="col-md-3 col-md-offset-2 control-label">Category:</label>
                        <div class="col-md-3">
                            <p class="form-control-static">
                                @foreach($categories AS $category)
                                    @if($category->id == $form->category_id) {{ $category->name }} @endif
                                @endforeach
                            </p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_industry" class="col-md-3 col-md-offset-2 control-label">File Type:</label>
                        <div class="col-md-3">
                            <p class="form-control-static">{{ ucfirst($form->type) }}</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file_upload" class="col-md-5 control-label">Current File:</label>
                        <div class="col-md-5">
                            <p class="form-control-static"><a href="/user/forms/{{ $form->id }}/print" target="_blank">{{ $form->file }}</a></p>
                        </div>
                    </div>

                    <div class="col-md-12 text-center buttons">
                        <a href="/user/forms" class="btn btn-default btn-xs ">CANCEL</a>
                        {{--<button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>--}}
                        <a href="/user/forms/{{ $form->id }}/print" class="btn btn-default btn-primary btn-xs btn-edit" target="_blank">PRINT</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
@stop