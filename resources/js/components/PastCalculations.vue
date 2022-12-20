<template>
    <div class="bg-neutral-900 rounded-lg w-3/5 relative flex flex-col">
        <h1 class="text-center my-4 font-semibold text-lg">Previous Calculations</h1>

        <div v-if="loading" class="text-center">
            <p>Loading previous calculations...</p>
        </div>

        <div v-else class="p-4 relative overflow-y-scroll flex-grow space-y-4">
            <div v-for="calc in sortedCalculations" :key="calc.id" class="bg-neutral-500 rounded-lg px-4 py-2 flex items-center justify-between">
                <div class="text-right">
                    <p class="text-medium text-zinc-300">{{ calc.expression }} = </p>
                    <p class="text-zinc-100 font-bold">{{ calc.result }}</p>
                </div>

                <div class="flex-grow flex justify-end">
                    <button class="trash-button cursor-pointer p-2 rounded-lg hover:bg-neutral-700/75" type="submit" @click.prevent="deleteEntry(calc.id)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="32px" height="32px"><path fill="currentColor" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="!loading" class="p-4 flex items-center justify-between">
            <h2 class="text-lg">
                <span class="font-bold">{{ sortedCalculations.length }}</span> previous calculation{{ sortedCalculations.length === 1 ? '' : 's' }}
            </h2>

            <button class="trash-button cursor-pointer p-2 rounded-lg hover:bg-neutral-700/75" type="submit" @click.prevent="deleteAllEntries()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="32px" height="32px"><path fill="currentColor" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
            </button>
        </div>
    </div>
</template>

<style scoped lang="scss">
.trash-button {
    svg {
        color: #dc2626;
    }

    &:hover {
        svg {
            color: #ef4444;
        }
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
           loading.value = true;

           console.log('Refreshing past calculations');

           const res = await client.get('/api/calculations');
           const data = res.data as CalculationsResponse;

           calculations.value = data.data;

           loading.value = false;
       }

       onMounted(() => {
           refresh();
       })

       return {
           loading,
           sortedCalculations: computed(() => calculations.value.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))),

           refresh,
           async deleteEntry(id: number) {
               calculations.value = calculations.value.filter(c => c.id !== id);

               await client.delete(`/api/calculations/${id}`);
           },
           async deleteAllEntries() {
               calculations.value = [];

               await client.delete(`/api/calculations`);
           },
       };
   }
});
</script>
