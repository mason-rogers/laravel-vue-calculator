<template>
    <div class="p-2 text-zinc-200" :class="colorClass">
        <p>{{ message }}</p>
    </div>
</template>

<script lang="ts">
import {computed, defineComponent} from 'vue';

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

    setup(props) {
        return {
            colorClass: computed(() => {
                switch(props.type) {
                    case 'warning': return 'bg-orange-500';
                    case 'danger': return 'bg-red-600';
                    default: return 'bg-sky-500';
                }
            })
        };
    }
})
</script>
