@extends('user.wrap')

@section('head')
    @parent
@stop

@section('content')
    <div id="main_body">
            <div class="container" id="main">
                <div class="row main_wrap">
                    <div class="row text-center" style="margin-left: 0.1rem;">
                        <h3>Streamdent Login</h3>
                    </div>

                    <div class="row" style="display: flex; justify-content: center;">
                        <div class="col-sm-12 col-md-6" style="margin-left: auto; margin-right: auto;">
                            <form method="POST" action="{{ empty($streamentUser->id) ? route('streamdent.users.store') :  route('streamdent.users.update', $streamentUser->id)}}">
                                @csrf
                                <div class="form-group">
                                    @if (!empty($streamentUser->id))
                                        @method('PUT')
                                        <div class="form-group">
                                            <label>Username:</label>
                                            <input type="text" class="form-control" readonly value="{{ $streamentUser->login }}" />
                                        </div>
                                        <input type="hidden" name="streamdent_id" value="{{ $streamentUser->id }}" />
                                    @endif
                                    <label for="Fname">First Name</label>
                                    {!! Form::text('Fname', $streamentUser->fname, ['id' => 'Fname', 'class' => 'form-control', 'placeholder' => 'Enter First Name', 'required' => true]) !!}
                                    <label for="Lname">Last Name</label>
                                    {!! Form::text('Lname', $streamentUser->lname, ['id' => 'Lname', 'class' => 'form-control', 'placeholder' => 'Enter Last Name', 'required' => true]) !!}
                                </div>
                                <input type="hidden" name="Phone" value="1111111111" />
                                <input type="hidden" name="Mobile" value="1111111111" />
                                <div class="form-group">
                                    <label for="Email">Email address</label>
                                    {!! Form::text('Email', $streamentUser->email, ['id' => 'Email', 'class' => 'form-control', 'placeholder' => 'Enter Email', 'required' => true, 'type' => 'email']) !!}
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    {!! Form::text('Password', $streamentUser->password, ['id' => 'password', 'class' => 'form-control', 'placeholder' => 'Password', 'required' => true, 'type' => 'text']) !!}
                                </div>

                                <input type="hidden" name="streamdent_id" value="{{ $streamentUser->id }}" />

                                <input type="hidden" name="is_active" value="1" />

                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="button" class="btn"><a href="/user">Cancel</a></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@stop



{{--@section('foot')--}}
{{--    @parent--}}
{{--@stop--}}
