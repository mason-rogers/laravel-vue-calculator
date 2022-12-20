<template>
    <div class="calc-button text-center text-3xl rounded-full text-zinc-200 p-4 cursor-pointer" :class="[colorClass, useHalfWidth ? 'col-span-2' : '']">
        <slot />
    </div>
</template>

<script lang="ts">
import {computed, defineComponent} from 'vue';

export default defineComponent({
    props: {
        type: {
            type: String,
            required: true,
        },
        useHalfWidth: {
            type: Boolean,
        }
    },

    setup(props) {
        return {
            colorClass: computed(() => {
                switch(props.type) {
                    case 'number': default: return 'bg-zinc-500';
                    case 'operand': return 'bg-orange-500';
                    case 'equal': return 'bg-sky-500';
                    case 'clear': return 'bg-red-500';
                }
            })
        };
    }
})
</script>
