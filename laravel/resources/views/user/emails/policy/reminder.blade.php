@extends('emails.wrap')
@section('content')
	Dear Bent Ericksen & Associates’ Client:
	<br/>
	<p>
		We recently sent you an alert about a compliance update that affects your business. Our records indicate that you have not accepted the update(s) and therefore your policy manual may be out of compliance.
	</p>
	<p>
		Please complete the following steps to update your policy manual:
	</p>
	<ol>
		<li>
			Please log in to your Bent Ericksen & Associates account at <a href="{{ url('/') }}">www.bentericksen.com</a>
		</li>
		<li>
			You will be notified that a policy update is available.
		</li>
		<li>
			You must click “Update” in order to access the update process. This will trigger a step-by-step process that will guide you through the updating of your policy manual.
		</li>
		<li>
			Once you have accepted the updates, you can create a new policy manual right away, make additional policy changes, or you can close and return later to create the new policy manual.
		</li>
		<li>
			Once the new policy manual is created, if you have a hard copy version you will need to print the newly created policy manual and replace the old copy with the new version.
		</li>
		<li>
			Inform the staff that a modification has been done, ask them to review the policy manual (either online or using the hard copy version), and then sign the Updated Policy Manual Acknowledgement Form.
		</li>
	</ol>
	<p>
		Note: if you choose not to implement the update at this time you can still access other resources, such as Forms and Job Descriptions. However, your current policy manual will not be accessible until you implement the update.
	</p>
	<p>
		As always, we strive to keep you informed and up-to-date with the ever-changing and complex world of employment compliance.
	</p>
	<p>
		Thank you for your continued choice to have Bent Ericksen & Associates as your Human Resources Department. Please feel free to contact our office if you have additional questions.
	</p>
@stop