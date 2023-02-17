<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	</head>
	<body style="margin:0; padding:0;">
		<h2>{{ $job->title }}</h2>
		<div>
			{!! clean($job->description) !!}
		</div>
		</div>
	</body>
</html>