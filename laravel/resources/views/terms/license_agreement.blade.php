<div id="license-agreement-modal in" class="modal fade bs-example-modal-lg license-agreement-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
	<div class="modal-dialog modal-lg">
		<div class="modal-content license-agreement">
			<h3>BENT ERICKSEN & ASSOCIATES</h3>
			<h3>HR DIRECTOR LICENSE AGREEMENT</h3>
			<p>
				<strong>Bent Ericksen & Associates</strong> grants to you a non-exclusive, nontransferable, revocable license to access and utilize <strong>Bent Ericksen & Associates’</strong>
				web-based software and materials, the “HR Director.” The software and materials provided, have been created, designed, manufactured by, and are the solely owned property of 
				<strong>Bent Ericksen & Associates</strong>. The software and materials are distributed exclusively through <strong>Bent Ericksen & Associates</strong> and its authorized dealers.
				THE SOFTWARE AND MATERIALS LICENSED HEREUNDER ARE SUBJECT TO THE COMMON LAW AND STATUTORY COPYRIGHT PROTECTION OF ITS CREATORS, AND YOU ACQUIRE NO INTEREST IN SUCH SOFTWARE AND/OR
				MATERIALS EXCEPT THE RIGHT TO ACCESS AND USE THE SOFTWARE AND MATERIALS FOR ITS INTENDED PURPOSES. This license does not grant to you or anyone else the right to copy, disassemble
				the licensed software or materials, or any portion thereof. Neither this license, nor access to the licensed software and materials, or any portion thereof, may be sold, leased, 
				assigned, licensed, or otherwise transferred by you, except as expressly provided by <strong>Bent Ericksen & Associates</strong>.
			</p>

			<p>
				<strong>Bent Ericksen & Associates</strong> makes every attempt to ensure that its products and information are compliant with applicable federal and state based regulations.
			</p>
			
			<p>
				As part of this license agreement, you acknowledge that the licensed software, materials, and supporting documentation are of an extremely confidential nature, and you agree to 
				receive, use, hold and maintain them as a confidential, proprietary trade secret and product of <strong>Bent Ericksen & Associates</strong>. You shall not, without the prior 
				express written consent of <strong>Bent Ericksen & Associates</strong>, cause or permit disclosure of all or any portion of the licensed software, materials, or supporting 
				documentation, in any form or component, to any person or entity. You shall take all reasonable steps to safeguard the licensed software and materials and supporting documentation
				so as to insure that no unauthorized copy, in whole or in part, in any form, shall be made or distributed or otherwise given to any other person or entity.
			</p>
			
			<p>
				As part of this license agreement, you acknowledge that your continued use of this license and access to <strong>Bent Ericksen & Associates’</strong> web-based software and 
				updated materials, is limited and conditioned upon your maintaining a current valid annual support agreement with <strong>Bent Ericksen & Associates</strong>. 
				<strong>Automatic license agreement and support/update renewal shall take place, via credit card payment, on an annual basis beginning one year from date of initial purchase.
				The annual license and support renewal fee will be <strong>Bent Ericksen & Associates’</strong> then current charge for applicable license, support and update option.</strong>
			</p>
			
			<p>
				So long as, and only during such period as, you comply with all the terms and conditions set forth herein, the term of this license shall begin on the date of execution of this 
				license by <strong>Bent Ericksen & Associates</strong>, and shall run until such time as you do not maintain a current valid annual support agreement, at which time this license
				and your access to all <strong>Bent Ericksen & Associates’</strong> online, web-based software and materials will be terminated.
			</p>
			
			<p>
				Other than as set forth herein, <strong>Bent Ericksen & Associates</strong> makes no other warranties, either expressed or implied, relating to the licensed software and materials,
				including, but not limited to any implied warranty of merchantability or fitness for a particular purpose. <strong>Bent Ericksen & Associates</strong> shall not be liable for direct,
				indirect, special or consequential damages resulting from the use of the licensed software and materials.  <strong>Bent Ericksen & Associates</strong> reserves the right to immediately
				terminate this license upon failure to comply with any of the terms, conditions or limitations described herein.
			</p>
			<div class="license-agreement-buttons-div">
				<div class="license-agreement-buttons">
					<form id="license-agreement-form" method="POST" action="{{ url('/terms') }}">
						{{ csrf_field() }}
						<button class="btn btn-default btn-md btn-primary" type="submit">Accept</button>
					</form>
					<a class="btn btn-default btn-md license-agreement-decline" href="/auth/logout">Decline</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('.license-agreement-modal').modal({
			backdrop: 'static',
			keyboard: false
		}, 'show');
	});
</script>