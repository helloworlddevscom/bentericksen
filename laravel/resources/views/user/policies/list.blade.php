@extends('user.wrap')

@section('content')
    @include('shared.errors')
    <style>
        .disabled {
            background: #dedede !important;
        }
    </style>
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 text-center">
                    <h3>Policy List</h3>
                    <a href="/user/policies/create" class="btn btn-default btn-primary btn-xs">ADD NEW POLICY</a>
                </div>

                <div class="col-md-12 content">
                    <div class="row sub_content">
                        <div class="col-md-12">
                            <input type="text" class="form-control date-box pull-right" id="global_search" placeholder="KEYWORDS">
                        </div>
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped" id="policy_list">
                                <thead>
                                <tr>
                                    <th>
                                        <span>Policy</span>
                                    </th>
                                    <th>
                                        <span>Category</span>
                                    </th>
                                    <th>
                                        <span>Category Sort</span>
                                    </th>
                                    <th>
                                        <span>Policy Sort Order</span>
                                    </th>
                                    <th>
                                        <span>Optional / Required</span>
                                        {!! $help->button(5) !!}
                                    </th>
                                    <th>
                                        <span>Modifed?</span>
                                    </th>
                                    <th class="bg_none"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($policies as $policy)
                                    <tr id="policy_{{$policy->id}}"
                                        class=" @if($policy->edited === "yes")edited @endif {{ $policy->status }}">
                                        <td>
                                            @if($policy->edited === "yes")
                                                <span class="designator"
                                                      title="File editted on {{ date('m/d/Y', strtotime($policy->updated_at)) }}"><i
                                                            class="fa fa-file"></i></span>
                                            @else
                                                <span class="designator"><i class="fa fa-file-o"></i></span>
                                            @endif
                                            {{ strtoupper($policy->manual_name) }}
                                        </td>
                                        <td>
                                            <h4>{{ $policy->category_name }}</h4>
                                        </td>
                                        <td>
                                            <h4>{{ $policy->category_sort }}</h4>
                                        </td>
                                        <td>{{ $policy->order }}</td>
                                        <td class="text-left">
                                            {{ ucfirst($policy->requirement) }}
                                        </td>
                                        <td>
                                            {{ $policy->is_modified == 1 ? 'Yes' : '' }}
                                        </td>
                                        <td class="text-right">

                                            @if($policy->special == "stub")
                                                <a href="/user/policies/{{ $policy->id }}/select"
                                                   class="btn btn-default btn-primary btn-xs btn-edit">SELECT</a>
                                            @else
                                                <a href="/user/policies/{{ $policy->id }}/edit"
                                                   class="btn btn-default btn-primary btn-xs btn-edit">
                                                    @if($policy->userCanEdit($viewUser))
                                                        EDIT
                                                    @else
                                                        VIEW
                                                    @endif
                                                </a>
                                            @endif

                                            @if( $policy->status !== 'pending' )
                                                <a href="/user/policies/{{ $policy->id }}/toggleStatus"
                                                   @if( !$policy->userCanEdit($viewUser) ) disabled
                                                   @endif
                                                   class="btn btn-default btn-xs toggleStatus @if( !$policy->userCanEdit($viewUser) ) disabled @endif">
                                                    @if($policy->status == "enabled")
                                                        DISABLE
                                                    @else
                                                        ENABLE
                                                    @endif
                                                </a>
                                            @endif
                                            @if( $viewUser->hasRole('admin') )
                                                @if (!empty($policy->template))
                                                <a href="/user/policies/{{ $policy->id }}/compare" target="_blank"
                                                    class="btn btn-default btn-xs">
                                                    COMPARE
                                                </a>
                                                @else
                                                <a href="/user/policies/{{ $policy->id }}/compare" target="_blank"
                                                    class="btn btn-default btn-xs disabled" disabled>
                                                    COMPARE
                                                </a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <a href="/user/policies/sort" class="btn btn-primary btn-xs">SORT POLICIES</a>
                    <a href="/user/policies/sortRestore" class="btn btn-primary btn-xs">RESTORE DEFAULT SORT</a>
                    @if ($policy_updates_run)
                        <a href="#" class="btn btn-primary btn-xs js-force-modal" data-toggle='modal'
                           data-dismiss='modal'
                           data-target='#modalPolicyUpdatesReminder'>UPDATE POLICY MANUAL</a>
                    @elseif ($policies_pending)
                        <a href="#" class="btn btn-primary btn-xs" data-toggle='modal' data-dismiss='modal'
                           data-target='#modalPoliciesPending'>UPDATE POLICY MANUAL</a>
                    @else
                        <a href="/user/policies/createManual" class="btn btn-primary btn-xs createModal">UPDATE POLICY
                            MANUAL</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="modal fade text-left" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                                    class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="modalLabel">ADD NEW CATEGORY</h4>
                    </div>
                    {!! Form::open(['route'	=> 'user.policies.addCategory', 'method' => 'post']) !!}
                    <div class="modal-body">
                        <div class="col-md-12">
                            <label for="" class="col-md-10 control-label">Add Category:</label>
                            <input type="text" class="form-control" name="categoryName" placeholder="Category Name">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                        <button type="submit" class="btn btn-default">SAVE</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div><!-- /#main_body -->
@endsection

@section('foot')
    @parent
    <script>
        $(document).ready(function () {

            $('#add').click(function () {
                return !$('.select1 option:selected').remove().appendTo('.select2');
            });

            $('#remove').click(function () {
                return !$('.select2 option:selected').remove().appendTo('.select1');
            });

            $('.toggleStatus').on('click', function () {
                var path = $(this).attr('href');
                var $element = $(this);
                $.get(path, function (data) {
                    if (data === "reload") {
                        window.location.reload();
                    } else {
                        $element.html(data === "enabled" ? 'DISABLE' : 'ENABLE');
                        $element.parents('tr').toggleClass('enabled disabled');
                    }
                });

                return false;
            });
        });

        var table = $('#policy_list').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": [1, 2, 3]
            }],
            "order": [
                [
                    2, 'asc'
                ],
                [
                    1, 'asc'
                ]
            ],
            "iDisplayLength": 9999,
            "bPaginate": false,
            "displayLength": 9999,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({page: 'current'}).nodes();
                var last = null;

                api.column(1, {
                    page: 'current'
                })
                    .data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="5">' + group + '</td></tr>'
                        );

                        last = group;
                    }
                });
            }
        });

        /*Order by the grouping*/
        $('#policy_list tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([1, 'desc']).draw();
            } else {
                table.order([1, 'asc']).draw();
            }
        });
        $("#global_search").on("keyup", function () {
            table
                .search(this.value)
                .draw();
            console.log(this.value);
        });
    </script>

@endsection
