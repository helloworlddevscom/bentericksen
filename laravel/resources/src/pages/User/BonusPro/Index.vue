<template>
  <div id="main_body">
    <div class="container" id="main">
      <div class="row main_wrap">
        <div class="col-md-12 content">
          <div class="col-md-8 col-md-offset-2 content">
            <div v-if="success" class="alert alert-success"><strong>{{success}}</strong></div>

            <div class="row text-center">
                <h3>BonusPro - Plans List</h3>
                <Link href="/bonuspro/create" class="btn btn-default btn-xs btn-primary">ADD NEW PLAN</Link>
            </div>

            <div class="table-responsive">
              <table class="table table-striped" id="active_table">
                <thead>
                  <tr>
                      <th><span>Plan Name</span></th>
                      <th><span>Start Date</span></th>
                      <th class="bg_none"></th>
                  </tr>
                </thead>
                <tbody>
                    <tr v-for="(plan, index) in plans" :key="index">
                      <td>{{ plan.plan_id }} - {{ plan.plan_name }}</td>
                      <td>{{ plan.start_month }} / {{ plan.start_year }}</td>
                      <td class="text-right">
                          <a v-if="plan.completed == 0" :href="`/bonuspro/${plan.id}/resume`" class="btn btn-default btn-xs btn-wd btn-primary">RESUME</a>
                          <a v-else :href="`/bonuspro/${plan.id}/edit`" class="btn btn-default btn-xs btn-wd btn-primary">VIEW</a>
                          <a href="#" class="delete-plan modal-button btn btn-danger btn-xs btn-wd" :data-name="plan.name" :data-plan="plan.id">DELETE</a>
                      </td>
                    </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Layout from '@/layouts/user'
  import { Link } from '@inertiajs/inertia-vue'

	export default {
		components: {
      Link
    },
    props: {
      impersonated: {
        type: Object
      },
      plans: {
        type: Array
      },
      success: {
        type: String
      }
    },
    layout: Layout
	}
</script>