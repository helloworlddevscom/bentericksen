<template>
	<div class="table-responsive">
		<div class="d-flex align-items-center w-75">
			<input type="text" class="form-control w-25 mr-2" placeholder="Business Name" v-model="business">
			<div class="d-flex flex-column justify-content-center w-25 align-items-center mr-2">
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
			<input type="text" class="form-control w-25"  placeholder="Contact Name" v-model="contact">
		</div>
        <table class="table table-striped" id="business_table">
            <thead>
	            <tr>
	                <th class="cursor-pointer" @click="toggleSort('business.name')">Business Name</th>
	                <th class="cursor-pointer" @click="toggleSort('business_asas.expiration')">ASA Exp Date</th>
	                <th class="cursor-pointer" @click="toggleSort('users.first_name')">Contact Name</th>
	                <th class="cursor-pointer" @click="toggleSort('business.state')">State</th>
	                <th @click="toggleSort('business.hrdirector_enabled')" class="text-center cursor-pointer" width="8%">HR Director</th>
	                <th class="cursor-pointer text-center" @click="toggleSort('business.bonuspro_enabled')"  width="8%">BonusPro</th>
	                <th class="bg_none"></th>
	            </tr>
            </thead>
            <tbody>
            
                <tr v-for="(business, index) in businesses" :key="index">
                    <td>{{ business.name }}</td>
                    <td v-if="business.expiration && business.expiration != '0000-00-00 00:00:00'">
                        {{ business.expiration | date }}
                    </td>
                    <td v-else></td>
                    <td>
                    	{{ business.first_name }} {{ business.last_name }}
                    </td>
                    <td>{{ business.state }}</td>
                    <td class="text-center" width="8%">
                        <i  v-if="business.hrdirector_enabled" class="fa fa-check" aria-hidden="true"></i>
                    </td>
                    <td class="text-center" width="8%">
                        <i v-if="business.bonuspro_enabled" class="fa fa-check" aria-hidden="true"></i>
                    </td>
                    <td class="text-right">
                        <a :href="`/admin/business/${business.id }/edit`" class="btn btn-default btn-xs ">EDIT</a>
                        <a :href="`/admin/business/${business.id}/view-as`" class="btn btn-default btn-xs">VIEW AS CLIENT</a>
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
	import paginatedTable from '../mixins/paginatedTable'
	
	export default {
		mixins: [paginatedTable],
		computed: {
			businesses() {
				return this.response.data
			},
			url() {
				return `/admin/business-index?${this.params}`
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