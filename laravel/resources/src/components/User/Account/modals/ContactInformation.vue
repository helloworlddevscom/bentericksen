<template>

  <div v-for="(secondary, index) in business.secondary" class="modal fade" :id="`modalSecondary${secondary.index}`" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <form :action="`/user/account/secondary/${business.id}`" method="PUT">
                <input name="_method" type="hidden" value="PUT" />
                <input name="_token" type="hidden" :value="$store.config.csrf_token" />

              <div class="edit edit_status">
                  <div class="edit edit_salary">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">
                              <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                          </button>
                          <h4 class="modal-title text-center" id="modalLabel">Edit Contact Information</h4>
                      </div>
                      <div class="modal-body">
                          <div class="row">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <div class="row">
                                          <label for="prefix{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Prefix:</label>
                                          <div class="col-md-5">
                                              <input id="prefix1" class="form-control" placeholder="Prefix" name="secondary[1][prefix]" type="text"></input>
                                              {!! Form::text('secondary[' . $secondary->index . '][prefix]', $secondary->prefix, ['id' => 'prefix' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Prefix'])!!}
                                          </div>
                                      </div>
                                      <div class="row">
                                          <label for="firstName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} First Name:</label>
                                          <div class="col-md-5">
                                              {!! Form::text('secondary[' . $secondary->index . '][first_name]', $secondary->first_name, ['id' => 'firstName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'First Name'])!!}
                                          </div>
                                      </div>
                                      <div class="row">
                                          <label for="middleName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Middle Name:</label>
                                          <div class="col-md-5">
                                              {!! Form::text('secondary[' . $secondary->index . '][middle_name]', $secondary->middle_name, ['id' => 'middleName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Middle Name'])!!}
                                          </div>
                                      </div>
                                      <div class="row">
                                          <label for="lastName{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Last Name:</label>
                                          <div class="col-md-5">
                                              {!! Form::text('secondary[' . $secondary->index . '][last_name]', $secondary->first_name, ['id' => 'lastName' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Last Name'])!!}
                                          </div>
                                      </div>
                                      <div class="row">
                                          <label for="suffix{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Suffix Name:</label>
                                          <div class="col-md-5">
                                              {!! Form::text('secondary[' . $secondary->index . '][suffix]', $secondary->suffix, ['id' => 'suffix' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'Suffix'])!!}
                                          </div>
                                      </div>
                                      <div class="row">
                                          <label for="email{{ $secondary->index }}" class="col-md-4 col-md-offset-1 control-label">Secondary {{ $secondary->index }} Email:</label>
                                          <div class="col-md-5">
                                              {!! Form::text('secondary[' . $secondary->index . '][email]', $secondary->email, ['id' => 'email' . $secondary->index, 'class' => 'form-control', 'placeholder' => 'email'])!!}
                                          </div>
                                      </div>

                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="modal-footer text-center">
                          <button type="button" class="btn btn-default btn-primary" data-dismiss="modal">CANCEL</button>
                          <button type="submit" class="btn btn-default btn-primary">SAVE</button>
                      </div>
                  </div>
              </div>
              {!! Form::close() !!}
          </div>
      </div>
  </div>

                
</template>