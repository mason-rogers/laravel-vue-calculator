<template>
    <div class="centered">
        <calculator @result="onCalculatorResult" />

        <past-calculations ref="pastCalculationsRef" />
    </div>
</template>

<style scoped lang="scss">
.centered {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);


    width: 50%;
    height: 75vh;

    border-radius: 8px;
    padding: 1rem;

    display: flex;
    justify-content: space-between;
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
                console.log('ref', pastCalculationsRef.value);

                pastCalculationsRef.value?.refresh();
            }
        }
    }
});
</script>
