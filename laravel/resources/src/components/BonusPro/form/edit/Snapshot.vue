<template>
    <div>
        <div class="row text-center">
            <bp-edit-form-snapshot-table></bp-edit-form-snapshot-table>
        </div>
    </div>
</template>

<script>
  import BpEditFormSnapshotTable from './SnapshotTable.vue'

    export default {
      name: "Snapshot",
      components: {
        BpEditFormSnapshotTable
      }
    }
</script>

<style scoped lang="scss">

</style>