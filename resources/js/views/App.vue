<template>
    <div class="centered w-1/2 h-3/4 rounded p-4 flex justify-between gap-x-8 fixed top-1/2 left-1/2 ">
        <calculator @result="onCalculatorResult" />

        <past-calculations ref="pastCalculationsRef" />
    </div>
</template>

<style scoped lang="scss">
.centered {
    transform: translate(-50%, -50%);
}
</style>

<script>
import {defineComponent, ref} from 'vue';
import Calculator from '../components/Calculator.vue';
import PastCalculations from '../components/PastCalculations.vue';

export default defineComponent({
    components: {PastCalculations, Calculator},

    setup() {
        const pastCalculationsRef = ref(null);

        return {
            pastCalculationsRef,

            onCalculatorResult() {
                pastCalculationsRef.value?.refresh();
            }
        }
    }
});
</script>
