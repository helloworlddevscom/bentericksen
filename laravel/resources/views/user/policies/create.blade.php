@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                {!! Form::open(['route' => 'user.policies.store', 'method' => 'POST', 'class' => 'form-horizontal', 'id' => 'policyForm']) !!}
                {!! Form::hidden('is_tracking', null, ['id' => "is_tracking"]) !!}
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                            <div class="form-group">
                                <label class="col-md-4 control-label">Policy Name:</label>
                                <div class="col-md-4 text-center">
                                    <input type="text" class="form-control text-uppercase" name="manual_name" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label for="category" class="col-md-4 control-label">Category:</label>
                                <div class="col-md-4">
                                    <select id="category" class="form-control select" name="category_id">
                                        <option value=""> - Select One -</option>
                                        @foreach($categories as $category)
                                            @if( $category->id !== 8)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($displayRequiredSelect)
                        <div class="row">
                            <div class="col-md-10 col-md-offset-1">
                                <div class="form-group">
                                    <label for="required" class="col-md-4 control-label">Required:</label>
                                    <div class="col-md-4">
                                        <select id="required" class="form-control select" name="required">
                                            <option value=""> - Select One -</option>
                                            <option value="required">Required</option>
                                            <option value="optional">Optional</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <br>
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group" id="policy_default">
                                <div class="col-md-12" id="editor-container">
                                    <textarea id="content_raw" class="form-control" name="content_raw" style="display:none"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="content wrap">

                            </div>
                            <div class="hidden">
                                <textarea name="content" class="content"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-center buttons">
                        <a href="/user/policies" class="btn btn-default btn-primary btn-xs ">CANCEL</a>
                        <button name="submit" class="btn btn-default btn-xs btn-primary form-btn">SAVE</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
@stop
