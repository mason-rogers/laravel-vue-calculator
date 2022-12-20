<template>
    <div class="past-calculations">
        <h1 class="title">Previous Calculations</h1>

        <div v-if="loading">
            Loading...
        </div>

        <div v-else class="past-calculations-list">
            <div v-for="calc in sortedCalculations" :key="calc.id" class="past-calculation">
                <p class="expression">{{ calc.expression }} = </p>
                <p class="result">{{ calc.result }}</p>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
$bg-color: #e4e4e7;

.past-calculations {
    background: $bg-color;
    border-radius: 8px;
    width: 55%;
    position: relative;
    overflow: auto;
    max-height: 100%;

    display: flex;
    flex-direction: column;

    .title {
        padding: 0.5em 0;

        text-align: center;
        border-bottom: 2px solid black;
    }
}

.past-calculations-list {
    position: relative;
    overflow-y: scroll;
    flex-grow: 1;
}

.past-calculation {
    background: #18181b;
    border-radius: 8px;
    padding: 8px;

    margin: 16px;

    text-align: right;


    .expression {
        color: #a1a1aa;
        font-size: medium;
        margin-bottom: 0.25rem;
    }

    .result {
        color: #d4d4d8;
    }
}
</style>

<script lang="ts">
import {defineComponent, ref, onMounted, computed} from 'vue';
import {client, ICalculation, CalculationsResponse} from '../api';

export default defineComponent({
   setup() {
       const loading = ref(false);
       const calculations = ref<ICalculation[]>([]);

       const refresh = async () => {
           console.log('Refreshing past calculations');

           const res = await client.get('/api/calculations');
           const data = res.data as CalculationsResponse;

           calculations.value = data.data;
       }

       onMounted(() => {
           refresh();
       })

       return {
           loading,
           sortedCalculations: computed(() => calculations.value.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))),

           refresh,
       };
   }
});
</script>
