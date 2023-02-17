<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<style>
		.category-heading { border:1px solid #000; padding: 10px; font-size:20px; text-align:center; text-transform:uppercase; background: #dedede; margin-bottom: 30px;}
		.policy-heading { text-align: center; text-transform:uppercase; font-size: 16px; }
		.policy-body { font-size: 13px; margin-bottom: 20px; widows: 4; orphans: 4;}
		ol.arrow, ol.arrows, al { list-style: none; margin-left: 0; padding: 0 40px; display: block; }
		ol.arrow li:before, ol.arrows li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }
		al { list-style: none; margin-left: 0; padding: 0 40px; display: block; }
		al li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }		
		div.bordered { border: 3px solid #000;	padding: 5px; font-weight: 600; page-break-inside: avoid !important;}
		ol.arrow, ol.arrows, al { list-style: none; margin-left: 0; padding: 0 40px; display: block; }
		ol.arrow li:before, ol.arrows li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }	
		table { width: 100%; display: table;}
		table .row { line-height: 8px;  }
		table, tr, td, th, tbody, thead, tfoot {
			!page-break-inside: avoid !important;
		}
		#heading {
			min-height: 20px;
			width; 100%;
		}
		#heading #header {
			float:right;
			width: 40%;
			text-align: right;
		}
		table, tr, td, th, tbody, thead, tfoot {
			page-break-inside: avoid !important;
		}			
		.clearfix:before,
		.clearfix:after {
		  display: table;
		  content: " ";
		}
		.clearfix:after {
		  clear: both;
		}		
	</style>
</head>
<body>
	<div id="heading" class="clearfix">
		<div id="header">{{ $form->name }}</div>
	</div>
	<div style="border-bottom: 1px solid #000; margin-bottom: 2px;">&nbsp;</div>
</body>
</html>	
