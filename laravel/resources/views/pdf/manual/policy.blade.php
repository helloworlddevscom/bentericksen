<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<style>
		.category-heading { border:1px solid #000; padding: 10px; font-size:20px; text-align:center; text-transform:uppercase; background: #dedede; margin-bottom: 30px;}
		.page-break { page-break-after: always; }
		.page-break-before { page-break-before: always; }
		.policy-heading { text-align: center; text-transform: uppercase; font-size: 13pt; }
		.policy-body { font-size: 11pt; margin-bottom: 20px; widows: 4; orphans: 4; line-height: 1.4; }
		sup, sub { vertical-align: baseline; position: relative; }
		sup { top: -0.5em; }
		sub { top: 0.5em; }
		ol.arrow, ol.arrows, al { list-style: none; margin-left: 0; padding: 0 40px; display: block;}
		ol.arrow li:before, ol.arrows li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }
		al { list-style: none; margin-left: 0; padding: 0 40px; display: block; }
		al li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }		
		div.bordered { border: 3px solid #000;	padding: 5px; font-weight: 600; page-break-inside: avoid !important;}
		div.toc { margin-bottom: 20px; }
		div.toc p.top-level { padding: 0; margin: 0; }
		div.toc p.sub-level { margin: 0; padding: 0; padding-left: 20px;}
		ol.arrow, ol.arrows, al { list-style: none; margin-left: 0; padding: 0 40px; display: block; }
		ol.arrow li:before, ol.arrows li:before { font-family: DejaVu Sans; content: "\27A2" !important; margin: 0 5px 0 -15px !important; }	
		
		#footer { position: fixed; left: 0px; bottom: -1.25in; right: 0px; height: 1.25in; }
		#footer .page_count:after { content: counter(page, null); }
		.page_count { text-align: right;}
		.top-bar { width: 100%; height:2px; border-top: 4px solid #b19292; border-bottom: 1px solid #b19292; }
		.copyright { margin-bottom: 0;}
		.print-page p { width: 49.5%; display: inline-block; margin-top: 0; }
		.copyright, .print-page p, .copyright { font-size: 11px; color: #b19292; }
		table { width: 100%; display: table;}
		table .row { line-height: 8px;  }
		table, tr, td, th, tbody, thead, tfoot {
			page-break-inside: avoid !important;
		}
		#toc ol, #toc ol li { page-break-inside: auto; }
	</style>
	<script   src="https://code.jquery.com/jquery-2.2.3.min.js"   integrity="sha256-a23g1Nt4dtEYOj7bR+vTu7+T8VP13humZFBJNIYoEJo="   crossorigin="anonymous"></script>
</head>
<body>
	
	@foreach( $policyInformation AS $information )
		<h1 class="category-heading page-break-before">{{ $information['category']->name }}</h1>
		@foreach( $information['policies'] AS $policy )
			@if( $policy->category_id === $information['category']->id )
				<h2 class="policy-heading {{$policy->new_page ? 'page-break-before' : '' }}">{{ $policy->manual_name }}</h2>
				<div class="policy-body">
					{!! $policy->content !!}
				</div>
			@endif
		@endforeach
	@endforeach
	
</body>
</html>
