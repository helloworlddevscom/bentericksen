<template>
	<div class="table-responsive">
	    <table class="table table-striped">
	        <thead>
	        <tr>
	            <th class="form-group ">
	                <input type="text" class="form-control" v-model="business" placeholder="Business Name">
	            </th>
	            <th class="form-group">
	                <input type="text" class="form-control" v-model="contact" placeholder="User Name">
	            </th>
	            <th class="form-group">
	                <input type="text" class="form-control" placeholder="Email Address" v-model="email">
	            </th>
	            <th class="form-group">
	                <select class="form-control" v-model="role">
	                    <option value=""> - All - </option>
	                    <option value="1">Admin</option>
	                    <option value="2">Owner</option>
	                    <option value="3">Manager</option>
	                    <option value="4">Consultant</option>
	                    <option value="5">Employee</option>
	                </select>
	            </th>
	            <th class="form-group" colspan="2">
	                <div class="d-flex flex-column justify-content-center w-50 align-items-center mr-2">
						<div class="d-flex align-items-center">
							<date-pick :input-attributes="{readonly: true}" v-model="from"></date-pick>
			                <i class="ml-1 fa fa-calendar"></i>
		            	</div>
		                <span class="d-flex">- to -</span>
		                <div class="d-flex align-items-center">
		                	<date-pick :input-attributes="{readonly: true}" v-model="to"></date-pick>
		                	<i class="ml-1 fa fa-calendar"></i>
		                </div>
					</div>
	            </th>
	        </tr>
	        <tr>
	            <th class="cursor-pointer" @click="toggleSort('business.name')">
	                <span>Business Name</span>
	            </th>
	            <th class="cursor-pointer" @click="toggleSort('users.first_name')">
	                <span>User Name</span>
	            </th>
	            <th class="cursor-pointer" @click="toggleSort('users.email')">
	                <span>Email Address</span>
	            </th>
	            <th class="cursor-pointer" @click="toggleSort('role_user.role_id')">
	                <span>Type</span>
	            </th>
	            <th class="cursor-pointer" @click="toggleSort('users.last_login')">
	                <span>Last Login Date</span>
	            </th>
	            <th class="bg_none">
	            </th>
	        </tr>
	        </thead>
	        <tbody>
	            <tr
	            v-for="(user, index) in users"
	            :key="index"
	            :class="[user.status === 'disabled' ? 'disabled' : '']">
	                <td>
	                	{{ user.name ? user.name: 'n/a' }}
	                </td>
	                <td>{{ user.first_name }} {{ user.last_name }}</td>
	                <td>{{ user.email }}</td>
	                <td>{{ user.main_role }}</td>
	                <td v-if="!user.last_login">Never logged in</td>
	                <td v-else>{{ user.last_login | date }}</td>
	                <td class="text-right">
						<a
	                        v-if="user.status === 'enabled'"
	                        @click="toggleStatus(user.id)"
	                        class="status-update btn btn-default btn-xs"
	                        :disabled="authId == user.id">DISABLE
	                    </a>
                        <a
							v-else
                        	@click="toggleStatus(user.id)"
                        	class="status-update btn btn-default btn-xs">ENABLE
                        </a>

	                    <a :href="`/admin/user/${user.id}/view-as`" class="btn btn-default btn-xs"
	                    :disabled="!user.business_id">VIEW AS</a>
	                    <a :href="`/admin/user/${user.id}/emails`" class="btn btn-default btn-xs">EMAIL LOG</a>
	                </td>
	            </tr>
	        </tbody>
	    </table>

	    <div class="d-flex flex-wrap justify-content-between">
        	<div id="business_table_info">
        		Showing {{response.from}} to {{response.to}} of {{response.total}} entries
        	</div>
        	<b-pagination
		      v-model="page"
		      :total-rows="response.total"
		      :per-page="response.per_page"
		      first-text="First"
		      prev-text="Previous"
		      next-text="Next"
		      last-text="Last"
		    ></b-pagination>
		</div>
	</div>
</template>

<script>
	import Api from '@/services/BaseService'
	import paginatedTable from '@/mixins/paginatedTable'
	
	export default {
		props: {
			authId: {
				type: Number
			}
		},
		mixins: [paginatedTable],
		data() {
			return {
				email: '',
				role: ''
			}
		},
		watch: {
			role() {
				this.resetAndGetData()
			},
			email() {
				this.resetAndGetData()
			}
		},
		computed: {
			users() {
				return this.response.data
			},
			url() {
				return `/admin/users-index?${this.params}`
			},
			params() {
				const {page, business, contact, email, role, from, to, sortBy, sortOrder} = this
      			return `page=${page}&business_name=${business}&contact_name=${contact}&email=${encodeURIComponent(email)}&role=${role}&from=${from}&to=${to}&sort=${sortBy}&sort_order=${sortOrder}`
			}
		},
		methods: {
			async toggleStatus(user_id) {
				await Api.post('/admin/updateUser', { user_id })
				this.getData()
			}
		}
	}
</script>

<style lang="scss" scoped>
	@import '~bootstrap/scss/functions';
	@import '~bootstrap/scss/variables';
	@import '~bootstrap/scss/mixins';
	@import '~bootstrap/scss/utilities';
	@import '~bootstrap/scss/pagination';
	@import '~bootstrap/scss/forms';
	@import '~vue-date-pick/dist/vueDatePick.css';
	
	.cursor-pointer {
		cursor: pointer;
	}

	.vdpWithInput{
		input {
			@extend .form-control;
		}
	}
</style>