<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<script>
	function subst() {
	  var vars={};
	  var x=document.location.search.substring(1).split('&');
	  for (var i in x) {var z=x[i].split('=',2);vars[z[0]] = unescape(z[1]);}
	  var x=['frompage','topage','page','webpage','section','subsection','subsubsection'];
	  for (var i in x) {
		var y = document.getElementsByClassName(x[i]);
		for (var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]];
	  }
	  
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd='0'+dd
		} 

		if(mm<10) {
			mm='0'+mm
		} 

		today = mm+'/'+dd+'/'+yyyy;
		x=document.getElementsByClassName("print-date");
			for(var i = 0; i < x.length; i++){
			x[i].innerText=today; 
		}

	  
	}
	</script>	
	<style>
		#footer { margin-top: 10px; }
		.page_count { text-align: right;}
		.top-bar { width: 100%; height:2px; border-top: 4px solid #b19292; border-bottom: 1px solid #b19292; }
		.copyright { margin-bottom: 0;}
		.print-page p { width: 49.5%; display: inline-block; margin-top: 0; }
		.copyright, .print-page p, .copyright { font-size: 11px; color: #b19292; }
	</style>
</head>
<body style="border:0; margin: 0;" onload="subst()">
	<div id="footer">
		<div class="top-bar"></div>
		<div class="copyright">
			Copyright &copy; {{ \Carbon\Carbon::now()->format('Y') }} Bent Ericksen & Associates &bull; All Rights Reserved
		</div>
		<div class="print-page">
			<p>Printed Date: <span class="print-date"></span></p>
			<p class="page_count">Page <span class="page"></span> </p>
		</div>
	</div>
</body>
</html>