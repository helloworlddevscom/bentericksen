<template>
    <div class="container text-center">
        <div class="row">
            <div class="col-md-4">
                <h4>POWERED BY BENT ERICKSEN & ASSOCIATES</h4>
            </div>
            <div class="col-md-4">
                <h4>HR DIRECTOR {{ APP_VERSION }}<br>COPYRIGHT {{ today }}</h4>
            </div>
            <div class="col-md-4">
                <h4>CONTACT SUPPORT</h4>
                <p><em>800-679-2760<br><a href="mailto:info@bentericksen.com">info@bentericksen.com</a></em></p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                today: (new Date()).getFullYear(),
                APP_VERSION: '1.1.1'
            }
        }
    }
</script>