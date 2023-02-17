@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="content">
                    <div class="row">
                        <p class="col-md-5"><a href="/user">Dashboard</a> > <a href="/user/employees">Employees</a> > <a href="/user/employees/time-off-requests">Time Off Requests</a></p>
                        <h4 class="col-md-2 text-center">Time Off Requests</h4>
                        <div class="col-md-1">{!! $help->button(42) !!}</div>
                    </div>
                </div>
                <div class="content">
                    <div class="col-md-3">
                        <div class="content_control">
                            <p><a class="btn"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add"> Add New Request</a></p>
                        </div>
                        <div class="col-md-12 sub_content hidden">
                            <form id="time-off-requests-form" method="post" action="/user/employees/time-off-requests">
                                {{ csrf_field() }}
                                <input type="hidden" id="request_type" name="request_type" value="timeoff">
                                <div class="row">
                                    <label for="request_start_date" class="col-md-12">Start Date:</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="input" class="form-control date-picker time-off" id="request_start_date" name="start_at" placeholder="mm/dd/yyyy">
                                            <!--  hh:mm ap time-picker -->
                                            <span class="input-group-addon">
                                                <label for="request_start_date"><i class="fa fa-calendar"></i></label>
                                            </span>
                                        </div>
                                        {!! $errors->first('start_at', '<span style="color:red;">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="request_end_date" class="col-md-12">End Date:</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input type="input" class="form-control date-picker time-off" id="request_end_date" name="end_at" placeholder="mm/dd/yyyy">
                                            <span class="input-group-addon">
                                                <label for="request_end_date"><i class="fa fa-calendar"></i></label>
                                            </span>
                                        </div>
                                        {!! $errors->first('end_at', '<span style="color:red;">:message</span>') !!}
                                    </div>
                                </div>
                                {!! $errors->first('end_at', '<span style="color:red;">:message</span>') !!}
                                <div class="row">
                                    <label for="request_employee_member_id" class="col-md-12">Select Employee:</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <select class="form-control time-off" id="request_employee_member_id" name="user_id">
                                                <option value=""> - Select One -</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->middle_name }} {{ $employee->last_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="request_pto_type" class="col-md-12">Type of Request:</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <select class="form-control" id="request_pto_type" name="type">
                                                <option value=""> - Select One -</option>
                                                @foreach($types as $key => $type)
                                                    <option value="{{ $key }}">{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="status" class="col-md-12">Status:</label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <select id="status" class="form-control" name="status">
                                                @if ($viewUser->permissions('m144'))
                                                    <option value="approved" selected>Approved</option>
                                                @endif
                                                <option value="pending">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="request_reason" class="col-md-12">Reason:</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" id="request_reason" name="reason" rows="5"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-9 text-center buttons">
                                    <a href="/user/employees/time-off-requests" class="btn btn-default btn-xs btn-primary">CANCEL</a>
                                    <button type="submit" class="btn btn-default btn-xs btn-primary submit">SUBMIT</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center" id="calendar_nav">
                            <a class="btn link-prev" data-calendar-nav="prev">&lt;&lt; Previous</a>
                            <a class="btn link-next" data-calendar-nav="next">Next &gt;&gt;</a>
                            <h3></h3>
                        </div>
                        <div id="calendar"></div>
                    </div>

                    <div class="col-md-3">
                        <div class="row text-right">
                            <div class="row">
                                <label class="col-md-2 col-md-offset-8 control-label">Key:</label>
                            </div>
                            <div class="row">
                                <label class="col-md-6 col-md-offset-2 control-label">Approved:</label>
                                <div class="col-md-2">
                                    <div class="img_key img_green"></div>
                                </div>
                                <div class="col-md-1">
                                    <i class="fa fa-check icon_green"></i>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6 col-md-offset-2 control-label">Denied:</label>
                                <div class="col-md-2">
                                    <div class="img_key img_red"></div>
                                </div>
                                <div class="col-md-1">
                                    <i class="fa fa-times icon_red"></i>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6 col-md-offset-2 control-label">Pending:</label>
                                <div class="col-md-2">
                                    <div class="img_key img_black"></div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-md-6 col-md-offset-2 control-label">Leave of Absence:</label>
                                <div class="col-md-2">
                                    <div class="img_key img_blue"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="requestModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title request-name" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="columns"></div>
                    </div>
                    <div class="modal-footer"></div>
                    <div class="modal-delete-confirmation"></div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="viewMoreModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"></h4>
                    </div>
                    <div class="modal-body">
                        <div class="column-headers">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-3 request-header">Employee</div>
                                        <div class="col-md-3 request-header">Status</div>
                                        <div class="col-md-3 request-header">Start</div>
                                        <div class="col-md-3 request-header">End</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="columns">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-md-offset-4 col-md-4">
                                <button class="btn request-close" data-dismiss="modal" type="button">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    <script src="/assets/scripts/plugins/calendar/calendar.js"></script>
    <script src="/assets/scripts/plugins/calendar/underscore-min.js"></script>
    <script type="text/javascript">
        function hereDoc(f) {
            return f.toString().replace(/^[^\/]+\/\*!?/, '').replace(/\*\/[^\/]+$/, '');
        }

        function regularizedDate(date) {
            return new Date(new Date(date).toLocaleString('en-US', {
                timeZone: 'America/Los_Angeles'
            })).getTime();
        }

        $(document).ready(function () {
          $(".date-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            yearRange: "-100:+10",
            onSelect: function (selectedDate) {
              let id = $(this).attr("id");
              if (id == "request_start_date") {
                $("#request_end_date").datepicker('option', 'minDate', selectedDate);
              }
            }
          });
        });

        /*Calendar*/
        (function ($) {
            "use strict";
            var eventData = <?php echo json_encode($timeOffRequests, true) ?>;

            eventData = Object.keys(eventData).map((key) => eventData[key])

            eventData = eventData.map(function(event) {
                return Object.assign(event, {
                    title: `Request for ${event.employee} (#${event.id})`,
                    url: `/user/employees/time-off-requests/${event.id}`,
                    class: event.status,
                    create: regularizedDate(event.created),
                    start: regularizedDate(event.start),
                    end: regularizedDate(event.end)
                });
            });
            var options = {
                events_source: eventData,
                view: 'month',
                tmpl_path: "/assets/scripts/plugins/calendar/tmpls/",
                tmpl_cache: false,
                day: '{{$startingDate}}',
                onAfterEventsLoad: function (events) {
                    if (!events) {
                        return;
                    }

                    var list = $('#eventlist');
                    list.html('');

                    $.each(events, function (key, val) {
                        $(document.createElement('li'))
                            .html('<a href="' + val.url + '">' + val.title + '</a>')
                            .appendTo(list);
                    });
                },
                onAfterViewLoad: function (view) {
                    $('#calendar_nav h3').text(this.getTitle());
                    $('.btn-group a').removeClass('active');
                    $('a[data-calendar-view="' + view + '"]').addClass('active');
                },
                classes: {
                    months: {
                        general: 'label'
                    }
                }
            };

            var calendar = $('#calendar').calendar(options);

            $('a[data-calendar-nav]').each(function () {
                var $this = $(this);
                $this.on('click', function () {
                    calendar.navigate($this.data('calendar-nav'));
                });
            });

            $('a[data-calendar-view]').each(function () {
                var $this = $(this);
                $this.on('click', function () {
                    calendar.view($this.data('calendar-view'));
                });
            });

            $('#first_day').change(function () {
                var value = $(this).val();
                value = value.length ? parseInt(value) : null;
                calendar.setOptions({first_day: value});
                calendar.view();
            });

            $('#language').change(function () {
                calendar.setLanguage($(this).val());
                calendar.view();
            });

            $('#events-in-modal').change(function () {
                var val = $(this).is(':checked') ? $(this).val() : null;
                calendar.setOptions({modal: val});
            });
        }(jQuery));

        $(document).ready(function () {
            $(".content_control a").on('click', function () {
                $(".sub_content").toggleClass("hidden");
            });

            $('.cal-month-day').each(function () {
               let calDate = $(this).parents(':eq(1)').children().attr('data-cal-date');

                @foreach($timeOffRequests as $timeOff) {
                    timeOffStart = "{{ $timeOff->start_at }}" || '';

                    timeOffStart = timeOffStart.substring(0, 10);

                    if ("{{ $timeOff->start_at }}".substring(0, 10) <= calDate && "{{ $timeOff->end_at }}".substring(0, 10) >= calDate) {
                        $(self).addClass('has-events');
                    }
                }
                @endforeach
            });

            requestOnClick();

            var $modal = $('.modal');

            $modal.on('show.bs.modal', function () {
                if ($(document).height() > $(window).height()) {
                    // no-scroll
                    $('body').addClass("modal-open-noscroll");
                } else {
                    $('body').removeClass("modal-open-noscroll");
                }
            });

            $modal.on('hide.bs.modal', function () {
                $('body').removeClass("modal-open-noscroll");
            });

            $('#calendar_nav a').on('click', function () {
                requestOnClick();
            });
        });

        function requestOnClick() {
            $('.request').on('click', function () {
                let calDate = $(this).parents(':eq(1)').children().attr('data-cal-date') || $(this).parent().parent().find('div:eq(2)').html().split(' ')[0];

                var dataID = $(this).attr('data-id');

                var modal = $('#requestModal');
                var modalDialog = modal.find('.modal-dialog');
                var modalContent = modalDialog.find('.modal-content');
                var modalHeader = modalContent.find('.modal-header');
                var modalTitle = modalHeader.find('.modal-title');
                var modalBody = modalContent.find('.modal-body');
                var modalColumnHeaders = modalBody.find('.column-headers');
                var modalColumns = modalBody.find('.columns');
                var modalFooter = modalContent.find('.modal-footer');
                let modalDeleteConfirmation = modalContent.find('.modal-delete-confirmation');

                var request;

                modalTitle.empty();
                modalColumns.empty();
                modalFooter.empty();
                modalDeleteConfirmation.empty();

                // the detail popups for the time off requests.
                // Note: these appear commented out, but they are not. The syntax below
                @foreach($timeOffRequests as $timeOff) {
                    var timeOffStart = "{{ $timeOff->start_at }}".substring(0, 10);
                    var timeOffEnd = "{{ $timeOff->end_at }}".substring(0, 10);

                    if (timeOffStart <= calDate && timeOffEnd >= calDate && dataID == "{{ $timeOff->id }}") {
                      modalTitle.text("{{ $timeOff->employee }}");
                      // NOTE: the use of hereDoc() for the "commented" multi-line string
                      modalColumns.append(hereDoc(function () {/*
<div class="row">
   <div class="col-md-4 request-header">Date Submitted</div>
   <div class="col-md-4 request-header">Start Date</div>
   <div class="col-md-4 request-header">End Date</div>
</div>
<div class="row">
   <div class="col-md-4">{{ $timeOff->created_at }}</div>
   <div class="col-md-4">{{ substr($timeOff->start_at, 0, 10) }}</div>
   <div class="col-md-4">{{ substr($timeOff->end_at, 0, 10) }}</div>
</div>
<div class="row">
   <div class="col-md-4 request-header">Status: </div>
   <div class="col-md-4 request-header">Type: </div>
   <div class="col-md-4 request-header">Reason: </div>
</div>
<div class="row">
   <div class="col-md-4"><p>{{ ucfirst($timeOff->status) }}</p></div>
   <div class="col-md-4"><p>{{ ucfirst($timeOff->type) }}</p></div>
   <div class="col-md-4"><p>{{ preg_replace( "/\r|\n/", " ", $timeOff->reason) }}</p></div>
</div>*/
                        })
                      );
                      // NOTE: the use of hereDoc() for the "commented" multi-line string
                      modalFooter.append(hereDoc(function () {/*
                        <div class="row">
                          <form accept-charset="UTF-8" action="/user/employees/timeoff/{{ $timeOff->id }}" method="POST">
                          {{ csrf_field() }}
                            <input type="hidden" value="/user/employees/time-off-requests" name="return" />
                            <div class="col-md-4">
                              <button class="btn request-approve frm-btn" @if(!$viewUser->permissions('m144')) disabled @endif value="approve" name="action" type="submit">
                                  Approve <i class="fa fa-check"></i>
                               </button>
                            </div>
                            <div class="col-md-4">
                              <button class="btn request-deny frm-btn" @if(!$viewUser->permissions('m144')) disabled @endif value="deny" name="action" type="submit">
                                  Deny <i class="fa fa-times"></i>
                              </button>
                            </div>
                          </form>
                          <div class="col-md-4">
                              <button class="btn request-delete frm-btn" @if(!$viewUser->permissions('m144')) disabled @endif onclick="requestOnDelete({{ $timeOff->id }})"
                                  data-target="#DeleteModal" data-toggle="modal" >  Delete <i class="fa fa-trash"></i>
                              </button>
                          </div>
                        </div>

                        <div class="row request-footer__vertical-margin">
                           <div class="col-md-offset-2 col-md-4">
                               <button class="btn request-edit" @if(!$viewUser->permissions('m144')) disabled @endif
                                  onclick="window.location='{{ route("editTimeOff",array($timeOff->id)) }}'" type="button"
                                  data-target="#EditModal" data-toggle="modal" > Edit <i class="fa fa-edit"></i>
                               </button>
                           </div>
                           <div class="col-md-4">
                               <button class="btn request-close" data-dismiss="modal" type="button">Close</button>
                          </div>
                        </div>*/
                        })
                      );
                      modalDeleteConfirmation.append(hereDoc(function () {/*
                      <div id="DeleteModal" class="modal fade text-danger" role="dialog">
                        <div class="modal-dialog ">
                           <form accept-charset="UTF-8" id="deleteForm" action="" method="POST">
                                <input type="hidden" value="/user/employees/time-off-requests" name="return" />
                                <input type="hidden" value="delete" name="action" />

                                 <div class="modal-content">
                                      <div class="modal-header bg-danger">
                                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">DELETE CONFIRMATION</h4>
                                    </div>
                                    <div class="modal-body">
                                              {{ csrf_field() }}
                                      <p class="text-center">Are you sure you want to permanently delete this request?</p>
                                    </div>
                                    <div class="modal-footer">
                                      <center>
                                      <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                                      <button type="submit" class="btn btn-danger" data-dismiss="modal" onclick="formSubmit()">Yes, Delete</button>
                                    </center>
                                  </div>
                                </div>
                             </form>
                        </div>
                       </div>
                      */})
                      );
                  }
                }
                @endforeach

                $('#viewMoreModal').modal('hide');
            });
        }

        /**
         * The .view-more button is dynamically generated using: httpdocs/assets/scripts/plugins/calendar/tmpls/month-day.html
         * so we attach a delegated event handler, using the #calendar ancestor which exists before any dynamic content.
         */
        $('#calendar').on('click', '.view-more', function () {
            let calDate = $(this).parents(':eq(1)').children().attr('data-cal-date');
            let [ year, month, day ] = calDate.split('-');
            let dateHuman = (new Date(`${month}/${day}/${year}`)).toDateString()
            var modal = $('#viewMoreModal');
            var modalDialog = modal.find('.modal-dialog');
            var modalContent = modalDialog.find('.modal-content');
            var modalHeader = modalContent.find('.modal-header');
            var modalTitle = modalHeader.find('.modal-title');
            var modalBody = modalContent.find('.modal-body');
            var modalColumnHeaders = modalBody.find('.column-headers');
            var modalColumns = modalBody.find('.columns');
            var modalFooter = modalContent.find('.modal-footer');

            var requests = [];

            modalTitle.empty();
            modalColumns.empty();

            @foreach($timeOffRequests as $timeOff) {
                var timeOffStart = "{{ $timeOff->start_at }}".substring(0, 10);
                var timeOffEnd = "{{ $timeOff->end_at }}".substring(0, 10);

                if (timeOffStart <= calDate && timeOffEnd >= calDate) {
                    modalTitle.text(dateHuman);
                    // NOTE: the use of hereDoc() for the "commented" multi-line string
                    modalColumns.append(hereDoc(function () {/*
<div class="row">
   <div class="col-md-3">
       <button class="btn btn-primary btn-lg calendar-modal-button calendar-name request {{ $timeOff->status }}"
               data-id="{{ $timeOff->id }}"
               data-toggle="modal"
               data-target="#requestModal"
               data-event-class="{{ $timeOff->status }}"
               title="{{ $timeOff->employee }}">
           {{ $timeOff->employee }}
       </button>
   </div>
   <div class="col-md-3">{{ $timeOff->status }}</div>
   <div class="col-md-3">{{ $timeOff->start_at }}</div>
   <div class="col-md-3">{{ $timeOff->end_at }}</div>
</div>
*/})
                    );
                }
            }
            @endforeach

            requestOnClick();
        });

        function requestOnDelete(timeOffID) {
            let url = '/user/employees/timeoff/' + timeOffID + '/deleteStatus' ;
            $("#deleteForm").attr('action', url);
        }

        function formSubmit()
        {
          $("#deleteForm").submit();
        }


    </script>
@stop
