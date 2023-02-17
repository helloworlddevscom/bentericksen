@extends('admin.wrap')

@section('content')

    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3><a href="/admin/job-description">Forms</a> / Add New</h3>
                </div>
                <div class="col-md-12 content">
                    {!! Form::open(['url' => '/admin/forms/' . $form->id, 'class' => 'form-horizontal', 'method' => 'put', 'files' => true]) !!}
                    <div class="form-group">
                        <label for="job_description" class="col-md-3 col-md-offset-2 control-label">Form Name:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="job_description" name="name" placeholder="Form Name" value="{{ $form->name }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_industry" class="col-md-3 col-md-offset-2 control-label">Category:</label>
                        <div class="col-md-3">
                            <select name="category_id" class="form-control industry_type" id="job_industry">
                                <option value=""> - Select One -</option>
                                @foreach($categories AS $category)
                                    <option @if( $category->id == $form->category_id ) selected @endif value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="employees" class="col-md-3 col-md-offset-2 control-label">Employees:</label>
                        <div class="col-md-4">
                            <input type="text" size="4" class="form-control date-box" name="min_employee" value="{{ $form->min_employee }}"> - to -
                            <input type="text" size="4" class="form-control date-box" name="max_employee" value="{{ $form->max_employee }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_industry" class="col-md-3 col-md-offset-2 control-label">File Type:</label>
                        <div class="col-md-3">
                            <select name="type" class="form-control industry_type" id="job_industry">
                                <option @if( $form->type == "regular" ) selected @endif value="regular">Regular File</option>
                                <option @if( $form->type == "confidential" ) selected @endif value="confidential">Confidential File</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="policy_benefit_state" class="col-md-5 control-label">State:</label>
                        <div class="col-md-3">
                            {!! Form::select('state[]', $states, $form->state, ['multiple'=>'multiple', 'id' => 'policy_benefit_state', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="policy_benefit_state" class="col-md-5 control-label">Business Type:</label>
                        <div class="col-md-3">
                            {!! Form::select('industries[]', $industries, $form->industries, ['multiple'=>'multiple', 'id' => 'industries', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file_upload" class="col-md-5 control-label">Current File:</label>
                        <div class="col-md-5">
                            <p class="form-control-static"><a href="/admin/forms/{{ $form->id }}/preview" target="_blank">{{ $form->file_name }}</a></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="file_upload" class="col-md-5 control-label">Upload New File:</label>
                        <div class="col-md-5">
                            {!! Form::file('file_upload') !!}
                            <p class="form-control-static"><small><b style="color: red">IMPORTANT:</b> Current file will be removed. This operation can't be undone.</small></p>
                        </div>
                    </div>
                    {!! $errors->first('error', '<div style="color:red; text-align: center;">:message</div>') !!}

                    <div class="col-md-12 text-center buttons">
                        <a href="/admin/forms/{{ $form->id }}/preview" target="_blank" class="btn btn-default btn-xs">PREVIEW</a>
                        <a href="/admin/forms" class="btn btn-default btn-xs ">CANCEL</a>
                        <button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
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