@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 text-center">
                    <h3>Forms List</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="row sub_content">
                        <div class="col-md-12">
                            <input type="text" class="form-control date-box pull-right" id="global_search" placeholder="KEYWORDS">
                        </div>
                        <div class="col-md-12 table-responsive">
                            <table class="table" id="policy_list">
                                <thead>
                                <tr>
                                    <th>
                                        <span>Form</span>
                                    </th>
                                    <th>
                                        <span>Category</span>
                                    </th>
                                    <th>
                                        <span>Category Order</span>
                                    </th>
                                    <th>
                                        <span>Template ID</span>
                                    </th>
                                    <th>
                                        <span>Regular File / Confidential File</span>
                                        {!! $help->button(5) !!}
                                    </th>
                                    <th class="bg_none"></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($business->forms as $form)
                                    <tr class="@if($form->edited === 'yes') edited @endif {{ $form->status }}">
                                        <td>
                                            @if($form->edited === "yes")
                                                <span class="designator" title="File editted on {{ date('m/d/Y', strtotime($form->updated_at)) }}"><i class="fa fa-file"></i></span>
                                            @else
                                                <span class="designator"><i class="fa fa-file-o"></i></span>
                                            @endif
                                            {!! strtoupper($form->template->name) !!}
                                        </td>
                                        <td>
                                            <h4>{{ $form->template->category->name }}</h4>
                                        </td>
                                        <td>
                                            <h4>{{ $order[$form->template->category->name] }}</h4>
                                        </td>
                                        <td>
                                            <h4>{{ $form->template->id }}</h4>
                                        </td>
                                        <td>
                                            {{ ucfirst($form->template->type) }}
                                        </td>
                                        <td class="text-right">
                                            <a href="/user/forms/{{ $form->id }}/print" class="btn btn-default btn-primary btn-xs btn-edit" target="_blank">OPEN</a>
                                            <a href="/user/forms/{{ $form->id }}/print" class="btn btn-default btn-primary btn-xs btn-edit" onClick="window.open(this.href).print(); return false;">PRINT</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stop

        @section('foot')
            @parent
            <script>
                var invisibleColumns = [
                    1, // Category
                    2, // Category Order
                    3 // Template ID
                ];

                var headerDividerLength = invisibleColumns.length > 0 ? 6 - invisibleColumns.length : 6;

                var table = $('#policy_list').DataTable({
                    "columnDefs": [{
                        "visible": false,
                        "targets": invisibleColumns
                    }],
                    "orderFixed": [
                        [2, 'asc']          // Category Order
                    ],
                    "order": [[
                        0, 'asc'            // Form
                    ]],
                    "iDisplayLength": 25,
                    "displayLength": 9999,
                    "drawCallback": function (settings) {
                        var api = this.api();
                        var rows = api.rows({page: 'current'}).nodes();
                        var last = null;

                        api.column(1, {page: 'current'}).data().each(function (group, i) {
                            if (last !== group) {
                                $(rows).eq(i).before(
                                    '<tr class="group"><td colspan="' + headerDividerLength + '">' + group + '</td></tr>'
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
                });
            </script>

@stop
