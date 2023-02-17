@extends('user.wrap')

@section('content')
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <span>There were some problems with your import file.</span>
                        <br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success">
                        @if (is_array(session('success')))
                            @foreach(session('success') as $msg)
                                <p>{{ $msg }}</p>
                            @endforeach
                        @else
                            <p>{{ session('success') }}</p>
                        @endif
                    </div>
                @endif
                <div class="col-md-12 content">
                    <div class="col-md-8 col-md-offset-2 content">
                        <div class="row text-center">
                            <h3>Employee</h3>
                            <a href="/user/employees/create" class="btn btn-default btn-xs btn-primary">ADD NEW EMPLOYEE</a>
                        </div>
                        <div class="table-responsive">

                            <table class="table table-striped" id="active_table">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="text" class="form-control" id="col_1_search" placeholder="Name">
                                    </th>
                                    <th>
                                        <select class="form-control" id="col_2_emp_type_select_search">
                                            <option value="All">All</option>
                                            <option value="Owner">Owner</option>
                                            <option value="Manager">Manager</option>
                                            <option value="Employee">Employee</option>
                                        </select>
                                    </th>
                                    <th>
                                        <select class="form-control" id="col_3_emp_status_select_search">
                                            <option value="All">All</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th>
                                        <span>Name</span>
                                    </th>
                                    <th>
                                        <span>Permission</span>
                                    </th>
                                    <th>
                                        <span>Status</span> {!! $help->button("u3001") !!}
                                    </th>
                                    <th class="bg_none">
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $user)
                                @if ($user->roles[0]->id >= $viewUser->roles[0]->pivot->role_id)
{{--                                    Consultant and Manager--}}
                                    @if ($user->roles[0]->id == '4' && $viewUser->roles[0]->pivot->role_id == '3')
                                        <tr>
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td class="role">
                                                {{ ucfirst($user->getRole()) }}
                                            </td>
                                            <td class="status">
                                                @if ($user->status == "enabled")
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="/user/employees/{{ $user->id }}/edit" class="btn btn-default btn-xs btn-wd btn-primary">VIEW</a>
                                            </td>
                                        </tr>
{{--                                        Administrator Path--}}
                                    @else
                                        <tr>
                                            <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                            <td class="role">
                                                {{ ucfirst($user->getRole()) }}
                                            </td>
                                            <td class="status">
                                                @if ($user->status == "enabled")
                                                    Active
                                                @else
                                                    Inactive
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="/user/employees/{{ $user->id }}/edit" class="btn btn-default btn-xs btn-wd btn-primary">EDIT</a>
                                            </td>
                                        </tr>
                                    @endif
{{--                                    Consultant path--}}
                                @elseif ($viewUser->roles[0]->pivot->role_id == '4')
                                    <tr>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td class="role">
                                            {{ ucfirst($user->getRole()) }}
                                        </td>
                                        <td class="status">
                                            @if ($user->status == "enabled")
                                                Active
                                            @else
                                                Inactive
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="/user/employees/{{ $user->id }}/edit" class="btn btn-default btn-xs btn-wd btn-primary">EDIT</a>
                                        </td>
                                    </tr>
{{--                                    Manager Path--}}
                                @else
                                    <tr>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td class="role">
                                            {{ ucfirst($user->getRole()) }}
                                        </td>
                                        <td class="status">
                                            @if ($user->status == "enabled")
                                                Active
                                            @else
                                                Inactive
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="/user/employees/{{ $user->id }}/edit" class="btn btn-default btn-xs btn-wd btn-primary">VIEW</a>
                                        </td>
                                    </tr>
                                @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('foot')
    @parent
    
@stop
