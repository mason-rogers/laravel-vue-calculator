<template>
    <div class="alert" :class="type">
        <p>{{ message }}</p>
    </div>
</template>

<style scoped lang="scss">
.alert {
    padding: 0.5rem;
    color: #d4d4d8;
    margin-bottom: 0.5rem;
    border-radius: 8px;

    &.danger {
        background: #dc2626;
    }

    &.warning {
        background: #f97316;
    }
}
</style>

<script lang="ts">
import {defineComponent} from 'vue';

export default defineComponent({
    props: {
        type: {
            type: String,
            required: true,
            validator: value => ['warning', 'danger'].includes(value),
        },
        message: {
            type: String,
            required: true
        }
    },

    setup() {
        return {};
    }
})
</script>
