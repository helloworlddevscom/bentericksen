@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 heading">
                    <h3><a href="/admin/job-description">Job Description</a> / Edit </h3>
                </div>

                <div class="col-md-12 content">
                    {!! Form::open(['url' => '/user/job-descriptions/' . $job->id, 'class' => 'form-horizontal', 'method' => 'patch']) !!}

                    <div class="option outline">
                        <div class="form-group">
                            <label for="job_description" class="col-md-2 col-md-offset-1">Title:</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="job_description" name="name" value="{{ $job->name }}" placeholder="">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_description" class="col-md-2 col-md-offset-1">Employees:</label>
                            <div class="col-md-4">
                                <select name="employees[]" class="form-control" multiple>
                                    @if(isset($employees))
                                        @foreach($employees as $employee)
                                            <option @if(in_array($employee->id, $assigned)) selected @endif value="{{$employee->id}}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <textarea class="form-control ckeditor" name="description">{!! $job->description !!}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 text-center buttons">
                        <a href="/user/job-descriptions" class="btn btn-default btn-xs ">CANCEL</a>
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
    <script>
        // snippet to disable LITE plugin on CKEditor.
        window.ck_info.enableLite = false;

        CKEDITOR.replace('description', {
			customConfig: '/assets/js/plugins/ckeditor/admin_config.js',
            height: 400
        });

        nanospell.ckeditor('description', {
            dictionary: "en",
            server: "php"
        });
    </script>
@stop
