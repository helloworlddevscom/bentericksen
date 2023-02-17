@extends('admin.wrap')

@section('content')
    @php
        $filter = ucwords(str_replace("-", " ", $type));
        $title = $filter . " Filter";
    @endphp
    <style>
        #states {
            height: 180px;
        }

        #status {
            height: 75px;
        }

        .employee_num {
            width: 100%;
        }
    </style>
    <!-- testing staging deployment -->
    <div class="container" id="main">
        <div class="row main_wrap">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                                <button type="button" class="close" data-dismiss="alert"
                                        aria-label="Dismiss Alert Button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-12 heading">
                <h3>{{ $title }}</h3>
            </div>
            <div class="col-md-12 content">
                <div class="col-md-12 sub_content">
                    <div class="row text-center">
                        <h3>{{ $title }}</h3>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ Form::open(array('url' => 'admin/business-export/'.$type.'/list')) }}
                                <div class="row">
                                    <div class="col-xs-12 col-md-4">
                                        <div class="form-group">
                                            {{ Form::label('states', 'Business State(s)', ['class' => 'control-label']) }}
                                            <select name="states[]" id="states" class="form-control" multiple required>
                                                <option value="all" selected>All States</option>
                                                <?php
                                                foreach($states as $abbrev => $state) { ?>
                                                <option value="{{ $abbrev }}">{{ $state }}</option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-md-3">
                                        <div class="form-group">
                                            {{ Form::label('status', 'Business Status', ['class' => 'control-label']) }}
                                            <select name="status[]" id="status" class="form-control" multiple required>
                                                <option value="all" selected>All Status</option>
                                                <option value="active">Active</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="expired">Expired</option>
                                                <option value="renewed">Renewed</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('business_type[]', 'Business Type', ['class' => 'control-label']) }}
                                            <select name="business_type[]" id="business_type" class="form-control" multiple required>
                                                <option value="all" selected>All Types</option>
                                                @foreach($industries as $key => $industry)
                                                    <option value="{{ $key }}">{{ $industry['title'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            {{ Form::label('business_sub_type[]', 'Business Sub-Type', ['class' => 'control-label']) }}
                                            <select name="business_sub_type[]" id="business_sub_type" class="form-control" multiple required>
                                                <option value="all" selected>All Sub-Types</option>
                                                @foreach($industries as $key => $industry)
                                                    @foreach($industry['subtype'] as $ke => $subtype)
                                                        <option class="hidden {{ $key }}" value="{{ $ke }}">{{ $subtype }}</option>
                                                    @endforeach
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
  
                                    <div class="col-xs-12 col-md-5">
                                        @if ($type === "email-list")
                                        <div id="export-date-picker">
                                            <label>ASA Expiration</label><br>
                                            <div class="col-md-12">
                                                <span>From:</span><br />
                                                <div class="input-group">
                                                    <input type="text" class="form-control date-picker asa-date-from" id="asa-date-from" name="asa-date-from" placeholder="mm/dd/yyyy" required>
                                                    <label for="asa-date-from" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <span>To:</span><br />
                                                <div class="input-group">
                                                    <input type="text" class="form-control date-picker asa-date-to" id="asa-date-to" name="asa-date-to" placeholder="mm/dd/yyyy" required>
                                                    <label for="asa-date-to" class="input-group-addon"><i class="fa fa-calendar"></i></label>
                                                </div>
                                            </div>
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <div class="form-group">
                                                <label>Employee Count</label><br>
                                                <div class="col-xs-6">
                                                    {{ Form::label('employee-num-from', 'From', ['class' => 'control-label']) }}
                                                    <br>
                                                    <input type="number" name="employee-num-from"
                                                          class="control-label employee_num" min="0" max="10000" required/>
                                                </div>
                                                <div class="col-xs-6">
                                                    {{ Form::label('employee-num-to', 'To', ['class' => 'control-label']) }}
                                                    <br>
                                                    <input type="number" name="employee-num-to"
                                                          class="control-label employee_num" min="0" max="10000" required/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <button id="export-filter-list-submit" type="submit"
                                                    class="btn btn-xs btn-primary pull-right">
                                                Export {{ $filter }}</button>
                                        </div>
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
        $(document).ready(function () {
            $('#business_type').bind('change',(e) => {
              $('#business_sub_type option[value!="all"]').addClass('hidden')
              $('#business_sub_type option[value="all"]').prop('selected', true)
              $('#business_type option:selected').each((index, el) => {
                $(`#business_sub_type option.${$(el).val()}`).removeClass('hidden')
              })
            })

            /** dismiss alert bar **/
            const alert = $('.alert');
            alert.find('.close').on('click', function () {
                alert.hide();
            });


            $(".date-picker").datepicker({
                changeMonth: true,
                changeYear: true,
                showButtonPanel: true,
                yearRange: "-100:+10",
                onSelect: function (selectedDate) {
                    let id = $(this).attr("id");
                    if (id === "asa-date-from") {
                        $("#asa-date-to").datepicker('option', 'minDate', selectedDate);
                    }
                }
            });
        });
    </script>
@stop
