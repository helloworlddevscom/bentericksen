<template>
  <div class="container">
    <div class="policy-update-notification">
        <div class="row">
            <div class="col-xs-offset-4 col-xs-4">
                <div class="policy-notification text-center">
                    NOTIFICATION: You Have {{ policies_count }} New {{ 'Update' | pluralize(policies_count) }}
                </div>
            </div>
            <div class="col-xs-2">
                <a href="/user" class="btn policy-update-button">View and process {{ 'update' | pluralize(policies_count) }} > ></a>
            </div>
        </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    policies_count: {
      type: Number
    }
  },
  filters: {
    pluralize: (word, amount) => (amount > 1 || amount === 0) ? `${word}s` : word
  }
  
}
</script>