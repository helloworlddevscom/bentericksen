<div class="container">
    <div class="policy-update-notification">
        <div class="row">
            <div class="col-xs-offset-4 col-xs-4">
                <div class="policy-notification text-center">
                    NOTIFICATION: You Have {{ $policies_count }} New @if ($policies_count > 1) Updates @else Update @endif
                </div>
            </div>
            <div class="col-xs-2">
                <a href="#" class="btn policy-update-button">View and process @if ($policies_count > 1) updates @else update @endif > ></a>
            </div>
        </div>
    </div>
</div>