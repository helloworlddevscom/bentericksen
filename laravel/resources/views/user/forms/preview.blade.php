<!DOCTYPE html>
<html>
<body>
	<style>
		.group {
			page-break-inside:avoid;
		}
		table p {
			font-size: 12px;
		}
		.break {
			page-break-before:always;
		}
		.checkbox {
			margin: 2px 0 2px 5px;
			width: 10px;
			height: 10px;
			border: 1px solid #000;
			float:left;
		}
	</style>
	<div id="content">
		<div class="group">
			{!! clean($form->description) !!}
		</div>	
	</div>	
	
	<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#content').prepend('sdsdsdsdsdsdsd');
		});
	</script>
</body>
</html>