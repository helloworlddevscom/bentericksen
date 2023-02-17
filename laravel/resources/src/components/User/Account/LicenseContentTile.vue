<template>
  <div>
    <div class="mini-content account_box even">
      test
    </div>
  </div>
</template>
<script>

export default {
  props: {
    title: String,
    data: Object
  }
}
</script>
<style lang="scss">
h4 {
  font-size: 18px;
  margin-top: 10px;
  margin-bottom: 10px;
}

.mini-heading {
  text-align: center;
  background: var(--main-primary);
  border-radius: 5px 5px 0 0;
  color: #fff;
  padding: 1px;
  margin-top: 12px !important;

  .account_box {
    min-height: 293px;
  }

  .even {
    background: #f4f9ff !important;
  }
}
</style>
