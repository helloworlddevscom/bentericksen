<template>
    <div>
        <div class="col-md-10 table-wrap">
            <div v-if="message" class="alert alert-success">
                <strong>{{ message }}</strong>
            </div>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Month</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="months.length > 0" v-for="month in monthsSorted">
                    <td>{{ getMonth(month.month, month.year) }}/{{ month.year }}</td>
                    <td>{{ month.finalized === 1 ? 'Closed' : 'Open' }}</td>
                    <td>
                        <button type="button" class="btn btn-primary btn-xs" v-if="month.finalized === 1" @click="setMonth(month.id, true)">VIEW</button>
                        <button type="button" class="btn btn-primary btn-xs"
                                @click="setMonth(month.id, false)"
                                v-else>EDIT
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <form-modal-month @close="toggleModal" id="month-data" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" :not-active="notActive"></form-modal-month>
        </div>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex';
    import moment from 'moment';

    export default {
      name: "MonthlyData",
      data: function () {
        return {
          modalState: false,
          message: null,
          notActive: false
        };
      },
      methods: {
        ...mapActions({
          setActiveMonth: 'planData/setActiveMonth',
          clearActiveMonth: 'planData/clearActiveMonth'
        }),
        getMonth: (month, year) => {
          return moment().month(month - 1).year(year).format("MMMM");
        },
        setMonth: function (id, notActive = false) {
          const self = this;
          this.notActive = notActive;
          if (id) {
            this.setActiveMonth(id);
          }
          setTimeout(() => {
            self.toggleModal();
          }, 0);
        },
        closeModal: () => {
          this.clearActiveMonth();
        },
        toggleModal: function (message) {
          this.modalState = !this.modalState;

          if (message) {
            this.message = message;
          }

          setTimeout(() => {
            $('#month-data').modal(this.modalState ? 'show' : 'hide');
          }, 0);

          setTimeout(() => {
            this.message = null;
          }, 5000);
        }
      },
      computed: {
        ...mapState({
          months: state => state.planData.months
        }),
        monthsSorted () {
          const sorted = this.months.slice(0).sort((a, b) => {
            return new Date(`${b.month}/01/${b.year}`) - new Date(`${a.month}/01/${a.year}`)
          });
          return sorted.slice(0, -6);
        }
      }

    }
</script>

<style scoped lang="scss">
    .content {
        overflow: hidden;
    }

    .table-wrap {
        margin: 0 auto;
        float: none !important;
    }
</style>
