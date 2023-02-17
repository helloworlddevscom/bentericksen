<div class="col-md-12 text-center">
    <h4>Paperwork</h4>
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-striped" id="table">
            <thead>
            <tr>
                <th width="1">
                    <label class="control-label">&nbsp;</label>
                </th>
                <th>
                    <label class="control-label">Document</label>
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($paperwork as $work)
                <tr>
                    <td>
                        {!! Form::checkbox('paperwork[paperwork_id][]', $work->id, in_array($work->id, $employee->paperwork->pluck('id')->toArray()), ['id' => 'paperwork_' . $work->id]) !!}
                    </td>
                    <td><label for="paperwork_{{ $work->id }}"> {{ $work->name }}</label></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group text-center">
        <button type="submit" class="btn btn-default btn-primary btn-xs" name="action" value="paperwork">SAVE</button>
    </div>
</div>
