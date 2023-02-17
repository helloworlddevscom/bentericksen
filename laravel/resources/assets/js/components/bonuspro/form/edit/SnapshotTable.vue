<template>
    <div>
        <ul class="nav nav-tabs" role="tablist" id="plan_tab" v-if="hasHygiene">
            <li class="active">
                <a href="#staff" role="tab" data-toggle="tab">Staff</a>
            </li>
            <li>
                <a href="#hygiene" role="tab" data-toggle="tab">Hygiene</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade in active" id="staff">
                    <table>
                        <thead>
                        <tr>
                            <th class="column column--title"></th>
                            <th v-for="month in activeMonths">
                                {{ getMonth(month.month) }}/{{ month.year }}
                            </th>
                        </tr>
                        </thead>
                        <tbody id="snapshot-staff">
                        <tr>
                            <td class="column column--title">Net Production</td>
                            <td v-for="(month, key) in activeMonths">
                                <input class="net-production dollar" type="text"
                                        :name="'months.' + key + '.production_amount'"
                                        :value="month.production_amount | bp-dollar"
                                        disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="column column--title">Net Collection</td>
                            <td v-for="(month, key) in activeMonths">
                                <input class="net-collection dollar" type="text"
                                        :name="'months.' + key + '.collection_amount'"
                                        :value="month.collection_amount | bp-dollar"
                                        disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="column column--title">Production & Collection Avg.</td>
                            <td v-for="(month, key) in activeMonths">
                                <input class="production-collection-avg dollar" type="text"
                                        v-if="key > 0"
                                        :value='month.productionCollectionAverage | bp-dollar' disabled>
                            </td>
                        </tr>
                        <tr >
                            <td class="column column--title">Salary + Bonus Totals</td>
                            <td v-for="(month, key) in activeMonths">
                                <input class="salary-bonus-total" type="text" v-if="key > 0"
                                        :value='month.staffSalaryBonusTotal | bp-dollar' disabled>
                            </td>
                        </tr>
                        <tr>
                            <td class="column column--title">Salary % of P + C Average</td>
                            <td v-for="(month, key) in activeMonths">
                                <input class="salary-p-and-c-average" type="text" v-if="key > 0"
                                        :value='month.staffSalaryPercentageOfPandC | percentage' disabled>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            <div class="tab-pane fade in" id="hygiene" v-if="hasHygiene">
                <table>
                    <thead>
                    <tr>
                        <th class="column column--title"></th>
                        <th v-for="month in activeMonths">
                            {{ getMonth(month.month) }}/{{ month.year }}
                        </th>
                    </tr>
                    </thead>
                    <tbody id="snapshot-hygiene">
                    <tr>
                      <td class="column column--title ">Net Production</td>
                        <td v-for="(month, key) in activeMonths">
                            <input class="net-production dollar" type="text"
                                    :name="'months.' + key + '.hygiene_production_amount'"
                                    :value="month.hygiene_production_amount | bp-dollar"
                                    disabled>
                        </td>
                    </tr>
                    <tr>
                        <td class="column column--title ">Production Average</td>
                        <td v-for="(month, key) in activeMonths">
                            <input class="production-avg dollar" type="text"
                                    v-if="key > 0"
                                    :value='month.productionAverage | bp-dollar' disabled>
                        </td>
                    </tr>
                    <tr>
                        <td class="column column--title ">Salary + Bonus Totals</td>
                        <td v-for="(month, key) in activeMonths">
                            <input class="salary-bonus-total" type="text" v-if="key > 0"
                                    :value='month.hygienistsSalaryBonusTotal | bp-dollar' disabled>
                        </td>
                    </tr>
                    <tr>
                        <td class="column column--title salary-p-and-c-average">Salary % of Production Avg.</td>
                        <td v-for="(month, key) in activeMonths">
                          <input class="salary-p-and-c-average" type="text" v-if="key > 0"
                                    :value='month.hygienistsSalaryPercentageOfProd | percentage' disabled>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import { mapState, mapGetters } from 'vuex';
    import moment from 'moment';

    export default {
      name: "SnapshotTable",
      data: function () {
        return {
          editable: false
        }
      },
      methods: {
        getMonth: (month) => {
          if (month) {
            return moment().month(month - 1).format("MMM");
          }
        }
      },
      computed: {
        ...mapState({
          hasHygiene: store => store.initialSetup.hygiene_plan
        }),
        ...mapGetters({
          activeMonths: 'planData/activeMonths'
        })
      }
    }
</script>

<style scoped lang="scss">
    .nav-tabs {
        margin-top: 50px;
    }
    table {
        width: 100%;
        table-layout: fixed;
        margin: 20px 0;

        td, tr, th {
            text-align: center;
        }

        td, th {
            width: 8%;
            padding: 1px;
        }

        th {
            padding: 10px 0;
            font-weight: bold;
        }
    }

    input[ type=text ] {
        width: 100%;
        text-align: center;
        padding: 5px 0;

        &:disabled {
            padding: 6px 0;
            border: 1px solid #DDDDDD;
            background-color: #DDDDDD;
        }
    }

    .column {
        font-weight: bold;
        padding-right: 10px;

        &--title {
            text-align: right;
            width: 15%;
        }
    }
</style>
