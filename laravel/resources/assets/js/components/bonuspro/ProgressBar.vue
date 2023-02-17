<template>
  <div id="bp-progressbar">
    <ul>
      <li v-for="step in steps" :class="step.classes"><span>{{step.label}}</span></li>
    </ul>
  </div>
</template>

<script>
  import { mapState } from 'vuex'

  export default {
    name: "ProgressBar",
    data: function () {
      return {}
    },
    computed: {
      ...mapState({
        steps: store => store.ui.steps
      })
    },
    methods: {},
    mounted () {
    }
  }
</script>

<style scoped lang="scss">
  #bp-progressbar {
    width: 800px;
    display: block;
    margin: 0 auto;

    ul {
      margin: 0;
      display: flex;
      position: relative;
      padding: 40px 0 0 0;
      list-style-type: none;

      &:before {
        top: 16px;
        left: 90px;
        content: '';
        height: 3px;
        width: 610px;
        position: absolute;
        background-color: #D7D7D7;
      }

      li {
        width: 25%;
        text-align: center;
        position: relative;
        display: inline-block;

        span {
          width: 50%;
          color: #333;
          display: block;
          margin: 0 auto;
          line-height: 1.4;
          text-align: center;
        }

        &:before {
          content: '';
          top: -32px;
          width: 20px;
          height: 20px;
          position: absolute;
          border-radius: 100%;
          left: calc(50% - 10px);
          background-color: #D7D7D7;
        }

        &.active {
          span {
            font-weight: bold;
          }

          &:before {
            background-color: #1d75b3;
          }
        }

        &:first-child {
        }

        &:last-child {
          text-align: right;
        }
      }
    }
  }
</style>
