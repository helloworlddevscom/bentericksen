<template>
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap" id="app">

                <div class="col-md-12 heading">
                    <h3>Email Log</h3>
                </div>

                <div class="col-md-12 content">
                    <div class="row">
                    </div>
                    <div class="row">
                        <email-list :auth-id="user.id" :_response="_response"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import EmailList from '@/components/Admin/EmailLog/Index'
    import Layout from '@/layouts/admin'

	export default {
		components: {
            EmailList
        },
        props: {
            user: {
                type: Object
            },
            _response: {
                type: Object
            }
        },
        layout: Layout
	}
</script>