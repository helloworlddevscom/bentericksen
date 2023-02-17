<template>
    <div id="header_wrap">
        <div class="container">
            <div class="row">
                <div class="col-md-8 pull-left">
                    <div class="row">
                        <div class="col-md-2">
                            <a href="/"><img src="/assets/images/bea-logo.png" alt="Bent Ericken & Associates Logo" height="83" width="83"></a>
                        </div>
                        <div class="col-md-10">
                            <h2 class="header_wrap_h2">HR Director</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 pull-right" id="user_log_in_out">
                    <div class="row">
                        <div v-if="user" class="col-md-7 text-right">
                            <h4><em>Hello,
                                    <a href="#">{{ user.prefix }} {{ user.first_name | ucfirst }} {{ user.last_name | ucfirst }}</a></em>
                            </h4>
                        </div>
                        <div class="col-md-5 pull-right">
                            <h4>
                                <a href="/auth/logout">LOGOUT</a>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue'

export default {
    components: {
        Link
    },
    props: {
        user: {
            type: Object
        }
    }
}
</script>