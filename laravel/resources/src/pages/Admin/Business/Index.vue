<template>
	<div v-if="user.status == 'disabled'" style="width: 100%; height: 710px; background-color: #fff;">
        <div class="container" id="main">
            <div class="row main_wrap">
                <div class="col-md-12 content">
                    Your account has beeen disabled, please contact your administrator for more information.
                </div>
            </div>
        </div>
    </div>
    <div v-else id="main_body">
            <div class="container" id="main">
                <div class="row main_wrap">
                    <div class="col-md-12 heading">
                        <h3>Business list</h3>
                    </div>
                    <div class="col-md-12 content">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <p>
                                    <a class="export btn btn-default btn-xs" href="/admin/business/export">EXPORT ALL CLIENTS</a>
                                </p>
                                <p>
                                    <a href="/admin/business/create">
                                        <img class="add" width="15" height="15" alt="Add" src="/assets/images/add.png">
                                        Add New Business
                                    </a>
                                </p>
                            </div>
                        </div>

                        <business-list :_response="_response" />
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
	import BusinessList from '@/components/Admin/Business/Index'
    import Layout from '@/layouts/admin'

	export default {
		components: {
            BusinessList
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