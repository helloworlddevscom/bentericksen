@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    <div class="col-md-8 col-md-offset-2 content">
                        <div class="row text-center">
                            <h3>Employee Excel Import</h3>
                            <form method="post" action="/user/employees/excel" enctype="multipart/form-data" class="form-horizontal">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2 content">
                                        <div class="col-md-12">
                                            <div class="row emp-group">
                                                <div class="col-md-7 col-md-offset-3 text-center no-padding-both padding-bottom">
                                                    <a href="/user/employees/excel/download" class="btn btn-defult btn-primary btn-xs" target="_blank">DOWNLOAD EXCEL FILE</a> {!! $help->button("u3501") !!}
                                                </div>
                                                <label class="control-label col-md-4">Upload Excel File</label>
                                                <div class="col-md-8">
                                                    <div class="row">
                                                        <div class="col-md-12 no-padding-left">
                                                            <div class="input-group">
                                                                <span class="input-group-btn">
                                                                    <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000"/>
                                                                </span>
                                                                <input type="text" class="form-control" id="file-toggle" style="border-right:0;" required>
                                                                <span class="input-group-addon">
                                                                    <span class="btn btn-sm btn-file">
                                                                        <img src="/assets/images/BEA_folder.jpeg">
                                                                        <input type="file" id="employee_setup_assistant" name="file">
                                                                        <span class="btn btn-defult btn-primary btn-xs">BROWSE</span>
                                                                    </span>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row text-center buttons content">
                                    <a href="/user" class="btn btn-defult btn-primary btn-xs" name="action" value="previous">BACK</a> &nbsp;&nbsp;
                                    <button type="submit" class="btn btn-defult btn-primary btn-xs input-toggle">IMPORT</button>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 col-md-offset-3">
                                        <input type="checkbox" name="send_activation_email" value="0"/>
                                        Send activation emails now?
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script>
        $('#employee_setup_assistant').on('change', function () {
            $('#file-toggle').val($(this).val());
        });
    </script>
@stop
