<template v-if="objectToAct">
  <div
    class="modal fade show"
    tabindex="-1"
    role="dialog"
    aria-modal="true"
    aria-labelledby="delete-contact-title"
    style="display: block"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 id="delete-contact-title" class="modal-title">{{ props.boxTitle }}</h5>
          <button
            type="button"
            class="btn-close"
            aria-label="Close"
            :disabled="isActionDoing"
            @click="handleCancelAction"
          ></button>
        </div>
        <div class="modal-body">
          <p v-if="objectToAct" class="mb-0">
            Are you sure you want to delete {{ objectToAct.name }}?
          </p>
          <p v-if="actionError" class="text-danger mt-3 mb-0" role="alert">
            {{ actionError }}
          </p>
        </div>
        <div class="modal-footer">
          <button
            type="button"
            class="btn btn-secondary"
            :disabled="isActionDoing"
            @click="cancelAction"
          >
            Cancel
          </button>
          <button
            type="button"
            class="btn btn-danger"
            :disabled="isActionDoing"
            @click="confirmAction"
          >
            {{ isActionDoing ? '...' : actionName }}
          </button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-backdrop fade show"></div>
</template>

<script setup>
const cancelAction = () => {
  props.handleCancelAction()
}

const confirmAction = () => {
  props.handleConfirmAction()
}

const props = defineProps({
  objectToAct: { type: Object, default: null },
  isActionDoing: { type: Boolean, required: true },
  actionError: { type: String, default: '' },
  boxTitle: { type: String, default: '' },
  actionName: { type: String, default: '' },
  handleCancelAction: { type: Function, default: () => {} },
  handleConfirmAction: { type: Function, default: () => {} },
})
</script>

<style scoped></style>
