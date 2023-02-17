@extends('admin.wrap')

@section('content')
	<div id="main_body">
		<div class="container" id="main">
			<div class="row main_wrap">		
			
				<!-- -->
				<div class="col-md-12 heading">
					<h3>FAQ / Add New</h3>
				</div>
				<div class="col-md-12 content">
					{!! Form::open(['route' => 'admin.faqs.store', 'class' => 'form-horizontal', 'method' => 'post']) !!}
						<div class="form-group">
							<label for="faq_question" class="col-md-3 control-label">Question:</label>
							<div class="col-md-6">
								<input type="text" class="form-control" id="faq_question" name="question" placeholder="Question">
							</div>
						</div>
						
						<div class="form-group">
							<label for="faq_short_answer" class="col-md-3 control-label">Short Answer:</label>
							<div class="col-md-8 col-md-offset-2">
								<textarea class="ckeditor" name="short_answer"></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label for="faq_long_answer" class="col-md-3 control-label">Long Answer:</label>
							<div class="col-md-8 col-md-offset-2">
								<textarea class="ckeditor" name="long_answer"></textarea>
							</div>
						</div>
						
						<div class="form-group">
							<label for="categories" class="col-md-3 control-label">Category:</label>
							<div class="col-md-3">
								<select class="form-control" id="categories" name="category">
									<option value=""> - Select One - </option>
									@foreach($categories as $category)
										<option value="{{ $category->id }}">{{ $category->name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="faq_state" class="col-md-3 control-label">State:</label>
							<div class="col-md-3">
								<select class="form-control" id="faq_state" name="state">
									<option value=""> - Select One - </option>
									@foreach($states AS $key => $state)
										<option value="{{ $key }}">{{ $state }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="faq_business_type" class="col-md-3 control-label">Business Type:</label>
							<div class="col-md-3">
								<select class="form-control" id="faq_business_type" name="business_type">
									<option value=""> - Select One - </option>
									@foreach($industries AS $key => $industry)
										<option value="{{ $key }}">{{ $industry['title'] }}</option>
									@endforeach
								</select>
							</div>
						</div>
						
						<div class="col-md-12 text-center buttons">
							<a href="/admin/faqs" class="btn btn-default btn-xs ">CANCEL</a>
							<button type="submit" class="btn btn-default btn-xs btn-primary">SAVE</button>
						</div>
					{!! Form::close() !!}
				</div>
				<!-- -->				

			</div>
		</div>
@stop
