<template>
    <div class="past-calculations">
        <h1 class="title">Previous Calculations</h1>

        <div v-if="loading">
            Loading...
        </div>

        <div v-else class="past-calculations-list">
            <div v-for="calc in sortedCalculations" :key="calc.id" class="past-calculation">
                <div class="question-answer">
                    <p class="expression">{{ calc.expression }} = </p>
                    <p class="result">{{ calc.result }}</p>
                </div>

                <div class="actions">
                    <button type="submit" @click.prevent="deleteEntry(calc.id)">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="32px" height="32px"><path fill="currentColor" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="!loading" class="actions">
            <h2>
                {{ sortedCalculations.length }} previous calculation{{ sortedCalculations.length === 1 ? '' : 's' }}
            </h2>

            <button type="submit" @click.prevent="deleteAllEntries()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="32px" height="32px"><path fill="currentColor" d="M135.2 17.7C140.6 6.8 151.7 0 163.8 0H284.2c12.1 0 23.2 6.8 28.6 17.7L320 32h96c17.7 0 32 14.3 32 32s-14.3 32-32 32H32C14.3 96 0 81.7 0 64S14.3 32 32 32h96l7.2-14.3zM32 128H416V448c0 35.3-28.7 64-64 64H96c-35.3 0-64-28.7-64-64V128zm96 64c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16zm96 0c-8.8 0-16 7.2-16 16V432c0 8.8 7.2 16 16 16s16-7.2 16-16V208c0-8.8-7.2-16-16-16z"/></svg>
            </button>
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

    display: flex;
    align-items: center;
    justify-content: space-between;

    .question-answer {
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

    .actions {
        display: flex;
        justify-content: right;
        flex-grow: 1;

        button[type="submit"] {
            background: none;
            border: none;
            cursor: pointer;

            svg {
                color: #dc2626;
            }

            &:hover {
                svg {
                    color: #ef4444;
                }
            }
        }
    }

}

.past-calculations > .actions {
    padding: 1rem;
    border-top: 2px solid black;
    display: flex;
    justify-content: space-between;

    button[type="submit"] {
        background: none;
        border: none;
        cursor: pointer;

        svg {
            color: #dc2626;
        }

        &:hover {
            svg {
                color: #ef4444;
            }
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
