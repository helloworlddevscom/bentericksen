@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 heading">
                    <h3><a href="/admin/job-description">Job Description</a> / Add New</h3>
                </div>

                <div class="col-md-12 content">
                    {!! Form::open(['id' => 'job-descriptions-form', 'route' => 'user.job-descriptions.store', 'class' => 'form-horizontal']) !!}

                    <div class="form-group">
                        <div class="col-md-12 text-center">
                            <h3>Create New Job Description</h3>
                        </div>
                    </div>

                    @if(empty($job))
                        <div class="form-group">
                            <label for="" class="col-md-2 col-md-offset-1">Choose a Template:</label>
                            <div class="col-md-4">
                                <div class="input-field">
                                    {!! Form::select('type', $jobDescriptions, !empty($job) ? $job->name : Request::old('name'), ['id' => 'type', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="option outline"@if (!Request::old('type') && empty($job)) style="display:none"@endif>
                        <div class="form-group">
                            <label for="job_description" class="col-md-2 col-md-offset-1">Title:</label>
                            <div class="col-md-4">
                                <div class="input-field">
                                    {!! $errors->first('name', '<span class="js-errors" style="color:red;">:message</span>') !!}
                                    {!! Form::text('name', !empty($job) ? $job->name : Request::old('name'), ['id' => 'job_name', 'class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="job_description" class="col-md-2 col-md-offset-1">Employees:</label>
                            <div class="col-md-4">
                                <select name="employees[]" class="form-control" multiple>
                                    @if(isset($employees))
                                        @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <input type="hidden" name="industry" value="{{ $business->type }}">

                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="input-field">
                                    {!! $errors->first('description', '<span class="js-errors" style="color:red;">:message</span>') !!}
                                    {!! Form::textarea('description', !empty($job) ? $job->description : Request::old('description'), ['class' => 'form-control ckeditor', 'id' => 'description']) !!}
                                </div>
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
        window.ck_info.enableLite = false;

        CKEDITOR.replace('description', {
			customConfig: '/assets/js/plugins/ckeditor/admin_config.js',
            height: 400
        });

        nanospell.ckeditor('description', {
            dictionary: "en",
            server: "php"
        });


        $('#type').on('change', function () {
            var id = $(this).find('option:selected').val();
            $('.js-errors').remove();

            if (!id || id === 'default') {
                $('.outline').hide();
                return;
            }

            $('.outline').show();

            if (id == 'blank') {
                $('#job_name').val('');
                CKEDITOR.instances['description'].setData('');
            } else {
                $.get('/user/job-descriptions/' + id, function (data) {
                    $('#job_name').val(data.name);
                    CKEDITOR.instances['description'].setData(data.description);
                });
            }
        });


        $('#job-descriptions-form').on('submit', function (e) {
            e.preventDefault();
            var message;

            $('.js-errors').remove();

            if ($('#type').find(':selected').val() === 'default') {
                message = $('<div class="js-errors" style="color: red" />');
                message.text('Please select a Job Description template.');

                $('#type').parents('.input-field').after(message);

                return;
            }

            this.submit();
        });

    </script>
@stop
