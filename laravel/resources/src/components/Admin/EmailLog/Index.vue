<template>
    <div class="table-responsive">
        <table class="table table-striped" id="email_table">
            <thead>
            <tr>
                <th class="form-group">
                    <input type="text" class="form-control m-2"  placeholder="Contact Name" v-model="contact">
                </th>
                <th class="form-group">
                    <input type="text" class="form-control m-2"  placeholder="Email Address" v-model="email">
                </th>
                <!--            This is the Email Type group -->
                <th class="form-group m-2">
                    <select class="form-control" v-model="subject">
                        <option value=""> - All - </option>
                        <option value="Account Activation Email">Account Activation Email</option>
                        <option value="Pending Policy Review">Pending Policy Review</option>
                        <option value="Pending Compliance Policy Changes">Pending Compliance Policy Changes</option>
                        <option value="Policy Submitted for Review.">Policy Submitted for Review</option>
                        <option value="Policy Modification - Rejected">Policy Modification - Rejected</option>
                        <option value="Policy Modification - Approved">Policy Modification - Approved</option>
                        <option value="Bent Ericksen HR Director Compliance Updates">Bent Ericksen HR Director Compliance Updates</option>
                        <option value="HR Director Password Reset">HR Director Password Reset</option>
                        <option value="BonusPro Password Reset">BonusPro Password Reset</option>
                        <option value="Update Process Testing - Please Ignore">Update Process Testing - Please Ignore</option>

                    </select>
                </th>
                <th class="form-group">
                    <input type="text" class="form-control m-2" placeholder="Business Name" v-model="business">
                </th>
                <th class="form-group" colspan="3">
                    <!--            This is the searchable sent_date range-->
                    <div class="d-flex flex-column justify-content-center align-items-center m-2">
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
        <!--        These are searchable fields at the top-->
            <tr>
                <th class="cursor-pointer" @click="toggleSort('users.first_name')">
                    <span class="m-2">Contact Name</span>
                </th>
                <th class="cursor-pointer" @click="toggleSort('outgoing_emails.to_address')">
                    <span class="m-2">Email Address</span>
                </th>
                <th class="cursor-pointer" @click="toggleSort('outgoing_emails.subject')">
                    <span class="m-2">Email Subject</span>
                </th>
                <th class="cursor-pointer" @click="toggleSort('business.name')">
                    <span class="m-2">Business Name</span>
                </th>
                <th class="cursor-pointer text-center" @click="toggleSort('outgoing_emails.sent_at')">
                    <span class="m-2">Sent Date</span>
                </th>
                <th class="cursor-pointer" @click="toggleSort('outgoing_emails.sent_at')">
                    <span class="m-2">Time Sent</span>
                </th>

            </tr>
            </thead>
            <tbody>

            <tr
                    v-for="(email, index) in emails"
                    :key="index">
                <td>{{ email.first_name }} {{ email.last_name }}</td>
                <td>{{ email.to_address }}</td>
                <td>{{ email.subject }}</td>
                <td>{{ email.name }}</td>

                <td class="text-center" v-if="email.sent_at && email.sent_at != '0000-00-00 00:00:00'">
                    {{ email.sent_at | date }}
                </td>
                <td  v-if="email.sent_at && email.sent_at != '0000-00-00 00:00:00'">
                    {{ email.sent_at | time }}
                </td>
            </tr>

            </tbody>
        </table>
        <div class="d-flex flex-wrap justify-content-between">
            <div id="email_table_info">
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
  import paginatedTable from '@/mixins/paginatedTable'
  import DatePick from 'vue-date-pick'
  
  export default {
    props: {
      authId: {
        type: Number
      }
    },
    components: {
      DatePick
    },
    mixins: [paginatedTable],
    data () {
      return {
        email: '',
        subject: '',
        contact: '',
        to: '',
        from: '',
        sortBy: 'outgoing_emails.sent_at',
        sortOrder: 'desc'
      }
    },
    watch: {
      contact () {
        this.resetAndGetData()
      },
      subject () {
        this.resetAndGetData()
      },
      email () {
        this.resetAndGetData()
      },
      to () {
        this.resetAndGetData()
      },
      from () {
        this.resetAndGetData()
      }
    },
    computed: {
      emails () {
        return this.response.data
      },
      url () {
        return `/admin/email-index?${this.params}`
      },
      params () {
        const { page, business, contact, email, subject, from, to, sortBy, sortOrder } = this
        return `page=${page}&business_name=${business}&contact_name=${contact}&email=${encodeURIComponent(email)}&subject=${subject}&from=${from}&to=${to}&sort=${sortBy}&sort_order=${sortOrder}`
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