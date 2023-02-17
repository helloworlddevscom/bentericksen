<template>
    <div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h3>Exit Confirmation</h3>
                </div>
                <div class="modal-body text-center">
                    <p>Are you sure you want to exit?</p>
                </div>
                <div class="modal-footer text-center">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      name: "Confirmation"
    }
</script>

<style scoped>

</style>