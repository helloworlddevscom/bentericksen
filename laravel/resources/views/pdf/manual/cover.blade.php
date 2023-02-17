<!DOCTYPE html>
<html>
	<head>
		<style>
			h1 {
				font-size: 24px;
				text-align: center;
			}
			h2 {
				font-size: 19px;
				text-align: center;
			}
			h2 strong {
				font-size: 22px;
				text-align: center;
			}
			h3 {
				font-size: 16px;
				text-align: center;
			}

			
			#footer {
				position: absolute;
				bottom: 0;
			}
			.page_count { text-align: right;}
			.top-bar { width: 100%; height:2px; border-top: 4px solid #b19292; border-bottom: 1px solid #b19292; }
			.copyright { margin-bottom: 0;}
			.print-page p { width: 49.5%; display: inline-block; margin-top: 0; }
			.copyright, .print-page p, .copyright { font-size: 11px; color: #b19292; }			
		</style>
	</head>
	<body height="11.69in">
		<div style="margin-bottom: 26%;">&nbsp;</div>
		<h1>PERSONNEL POLICY MANUAL</h1>
		<br>
		<br>
		<h2>For the employees of:</h2>
		<br>
		<br>		
		<h2><strong>{{ $business->name }}</strong></h2>
		<br>
		<br>
		<h3>Effective: {{ \Carbon\Carbon::now()->format('m/d/Y') }}</h3>
		
	</body>
</html>
