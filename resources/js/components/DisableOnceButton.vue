<template>
  <!-- If hideOnClick is true and button has been clicked, nothing is rendered -->
  <button
    v-if="!hidden"
    :disabled="disabled"
    @click="handleClick"
    v-bind="$attrs"
  >
    <!-- Show the default slot content if not clicked, or a processing text if clicked -->
    <template v-if="!disabled">
      <slot />
    </template>
    <template v-else>
      {{ processingText }}
    </template>
  </button>
</template>

<script>
export default {
  name: 'DisableOnceButton',
  props: {
    processingText: {
      type: String,
      default: 'Processing...'
    },
    hideOnClick: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      disabled: false,
      hidden: false
    };
  },
  methods: {
    handleClick(event) {
      // Emit the click event so the parent component can perform its logic
      this.$emit('click', event);

      // Use a setTimeout with 0 delay to allow the native form submission to kick in.
      setTimeout(() => {
        this.disabled = true;
        if (this.hideOnClick) {
          this.hidden = true;
        }
      }, 0);
    }
  }
}
</script>
