@extends('admin.wrap')

@section('content')

    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3><a href="/admin/job-description">Forms</a> / Add New</h3>
                </div>
                <div class="col-md-12 content">
                    {!! Form::open(['url' => '/admin/forms', 'class' => 'form-horizontal', 'files' => true]) !!}
                    <div class="form-group">
                        <label for="job_description" class="col-md-3 col-md-offset-2 control-label">Form Name:</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="job_description" name="name" placeholder="Form Name">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_industry" class="col-md-3 col-md-offset-2 control-label">Category:</label>
                        <div class="col-md-3">
                            <select name="category_id" class="form-control industry_type" id="job_industry">
                                <option value=""> - Select One -</option>
                                @foreach($categories AS $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="employees" class="col-md-3 col-md-offset-2 control-label">Employees:</label>
                        <div class="col-md-4">
                            <input type="text" size="4" class="form-control date-box" name="min_employee"> - to -
                            <input type="text" size="4" class="form-control date-box" name="max_employee">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="job_industry" class="col-md-3 col-md-offset-2 control-label">File Type:</label>
                        <div class="col-md-3">
                            <select name="type" class="form-control industry_type" id="job_industry">
                                <option value="regular">Regular File</option>
                                <option value="confidential">Confidential File</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="policy_benefit_state" class="col-md-5 control-label">State:</label>
                        <div class="col-md-3">
                            {!! Form::select('state[]', $states, null, ['multiple'=>'multiple', 'id' => 'policy_benefit_state', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="policy_benefit_state" class="col-md-5 control-label">Business Type:</label>
                        <div class="col-md-3">
                            {!! Form::select('industries[]', $industries, null, ['multiple'=>'multiple', 'id' => 'industries', 'class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="file_upload" class="col-md-5 control-label" style="padding-top: 0">Select a file:</label>
                        <div class="col-md-5">
                            {!! Form::file('file_upload', ['class' => 'control-file']) !!}
                        </div>
                    </div>

                    <div class="col-md-12 text-center buttons">
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