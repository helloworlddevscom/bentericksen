@extends('user.wrap')

@section('head')
    @parent
@stop

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">

                <div class="col-md-12 text-center">
                    <h3>HR FAQ'S</h3>
                </div>
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group row">
                                <h3><label class="col-md-6 control-label">Category</label></h3>
                                <div class="col-md-6">
                                    <form method="post" action="/user/faqs/search">
                                        {{ csrf_field() }}
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="keywords" name="keywords" placeholder="Keyword">
                                            <span class="input-group-btn">
                                                <button type="submit" class="btn btn-default" style="height:30px;font-size:12px;">SUBMIT</button>
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-12 accordion" id="faqs">
                                    @foreach($categories as $category)
                                        @if(count($category->faqs) > 0)
                                            <h3 class="even">
                                                {{ $category->name }}
                                            </h3>

                                            <div>
                                                @foreach($category->faqs as $faq)
                                                    <div class="row accordion">
                                                        <h3 class="clearfix">
                                                            <p class="col-md-12">{{ $faq->question }}</p>
                                                        </h3>

                                                        <div>
                                                            @if( !empty($faq->long_answer))
                                                                <div class="accordion">
                                                                    <h3>
                                                                        {!! $faq->short_answer !!}
                                                                    </h3>

                                                                    <div>
                                                                        {!! $faq->long_answer !!}
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="odd" style="padding:0 0 0 31px;">
                                                                    {!! $faq->short_answer !!}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <hr>
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
        </div>
        @stop

        @section('foot')
            @parent
            <script>

                /*Accordion Settings*/
                $(function () {
                    var icons = {
                        header: "ui-icon-triangle-1-e",
                        activeHeader: "ui-icon-triangle-1-s"
                    };
                    $('.accordion').accordion({
                        collapsible: true,
                        active: false,
                        heightStyle: "content"
                    });

                });


            </script>
@stop
