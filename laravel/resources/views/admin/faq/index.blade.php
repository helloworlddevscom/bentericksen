@extends('admin.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 heading">
                    <h3>FAQ</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-2 policies_sidebar">
                            <p>
                                <a href="/admin/faqs/create" class="btn"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add New FAQ"> Add New FAQ</a>
                            </p>
                            <p>
                                <a class="btn modal-button" data-toggle="modal" data-target="#modalCategory" id="modal-category"><img src="/assets/images/add.png" class="add" height="15" width="15" alt="Add New Category"> Add New Category</a>
                            </p>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group row">
                                <h3><label class="col-md-6 control-label">Category</label></h3>
                                <div class="col-md-6">
                                    <form method="post" action="/admin/faqs/search">
                                        {!! csrf_field() !!}
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Keyword">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-default" style="height:30px;font-size:12px;">SUBMIT</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 accordion" id="faqs">
                                    @foreach($categories as $category)
                                        @if(count($category['faqs']) > 0)
                                            <h3>{{ $category['name'] }}</h3>
                                            <div>
                                                @foreach($category['faqs'] as $faq)
                                                    <div class="row accordion">
                                                        <h3 class="clearfix">
                                                            <p class="col-md-8" style="margin: 0; padding:0;">{{ $faq->question }}</p>
                                                            <div class="col-md-3 faq_buttons text-right">
                                                                <a href="/admin/faqs/{{$faq->id}}/edit" class="btn btn-default btn-xs btn-edit">EDIT</a>
                                                                <a class="btn btn-xs btn-delete modal-button-delete" data-target="/admin/faqs/{{ $faq->id }}/delete">DELETE</a>
                                                            </div>
                                                        </h3>
                                                        <div>
                                                            @if( !is_null($faq->long_answer) &&  $faq->long_answer !== "")
                                                                @if(!is_null($faq->short_answer) && $faq->short_answer !== "")
                                                                    <div class="accordion">
                                                                        <h3>{{ mb_strimwidth(strip_tags($faq->short_answer), 0, 50, "...") }}</h3>
                                                                        <div>
                                                                            {!! $faq->long_answer !!}
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <div>
                                                                        {!! $faq->long_answer !!}
                                                                    </div>
                                                                @endif
                                                            @elseif( !is_null($faq->short_answer) && $faq->short_answer !== "")
                                                                <div class="odd" style="padding:0 0 0 31px;">
                                                                    {!! $faq->short_answer !!}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalCategory" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="edit modal-category">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title text-center" id="modalLabel">New Category</h4>
                            </div>
                            <div class="modal-body">
                                {!! Form::open(['url' => '/admin/faqs/category/create', 'method' => 'post']) !!}
                                <div class="form-group row">
                                    <div class="col-md-4 col-md-offset-2">
                                        <input type="text" class="form-control" name="name" placeholder="New Category"/>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="submit" class="btn btn-default btn-sm btn-primary" value="Add Category"/>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                                <div class="form-group row">
                                    <div class="col-md-8 col-md-offset-2">
                                        @foreach($categories as $category)
                                            {{ $category['name'] }}<br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="edit modal-delete">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                </button>
                                <h4 class="modal-title" id="modalLabel">Confirm Deletion</h4>
                            </div>
                            <div class="modal-body">
                                <!--Update this into low variables or the like-->
                                Are you sure you want to delete this FAQ?
                            </div>
                            <div class="modal-footer">
                                <form method="post">
                                    <input type="hidden" name="_method" value="DELETE">
                                    {!! csrf_field() !!}
                                    <button type="button" class="btn btn-default" data-dismiss="modal">CANCEL</button>
                                    <button type="submit" class="btn btn-delete">DELETE</button>
                                </form>
                            </div>
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
    <script>
      window.PAGE_INIT.push(() => {
        $('.modal-button-delete').on('click', function () {
            var target = $(this).attr('data-target');
            $('#modalDelete').modal('show');
            $('#modalDelete form').attr('action', target);
            return false;
        });

        $('.btn-edit').on('click', function () {
            var href = $(this).attr('href');
            window.location = href;
            return false;
        });

        /*Accordion Settings*/
        $(function () {
            $('.accordion').accordion({
                collapsible: true,
                active: false,
                heightStyle: "content"
            });
        });
      })
    </script>
@stop
