<template>
    <div id="main_body">
        <div class="container" id="main">
            <div class="row main_wrap" id="app">

                <div class="col-md-12 heading">
                    <h3>User Login List</h3>
                </div>

                <div class="col-md-12 content">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <a href="/admin/login-list/export" class="btn btn-default btn-xs">EXPORT ALL CLIENTS</a>
                        </div>
                    </div>
                    <div class="row">
                        <login-list :auth-id="user.id" :_response="_response" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
	import LoginList from '@/components/Admin/LoginList/Index'
    import Layout from '@/layouts/admin'

	export default {
		components: {
            LoginList
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