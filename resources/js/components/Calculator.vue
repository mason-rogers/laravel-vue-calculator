<template>
    <div class="bg-zinc-200 rounded-lg w-2/5 relative">
        <h1 class="text-center">Calculator</h1>

        <div class="p-4">
            <alert v-if="errorAlert" :type="errorAlert.type" :message="errorAlert.message" />

            <form @submit.prevent="onSubmit">
                <input v-model="expression" class="rounded text-lg p-2 w-full" type="text" placeholder="Enter a mathematical expression..." :disabled="loading">
            </form>

            <div class="mt-4">
                <h2>Example Expressions</h2>

                <ul class="expression-list">
                    <li
                        v-for="(example, idx) in exampleExpressions"
                        :key="idx"
                        @click="() => expression = example[0]"
                        class="cursor-pointer"
                    >
                        {{ example[0] }} => {{ example[1] }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import {defineComponent, ref} from 'vue';
import type { AxiosError } from 'axios';
import {client, CalculateRequest, CalculateResponse} from '../api';
import Alert from "./Alert.vue";

interface CalculationError {
    type: 'warning' | 'danger';
    message: string
}

export default defineComponent({
    components: {Alert},
    emits: ['result'],

    setup(props, { emit }) {
       const loading = ref(false);
       const expression = ref('');
       const errorAlert = ref<CalculationError | null>(null);

       return {
           loading,
           expression,
           errorAlert,

           exampleExpressions: [
               ["1 + 2", "3"],
               ["6 + -12", "-6"],
               ["5 - -10", "15"],
               ["12 * 12", "144"],
               ["4096 / 1024", "4"],
               ["1 / 3", "0.333"],
           ],

           async onSubmit () {
               loading.value = true;
               errorAlert.value = null;

               // Submit
               console.log(`Attempting to calculate ${expression.value}`);

               try {
                   const res = await client.post('/api/calculations', <CalculateRequest>{
                       expression: expression.value,
                   });

                   const data = res.data as CalculateResponse;

                   const evaluatedExpression = expression.value;
                   expression.value = data.result.toString();

                   emit('result', evaluatedExpression, data.result);
               } catch (err: AxiosError) {
                   if (err.response.status === 422) {
                       errorAlert.value = {
                           type: 'warning',
                           message: err.response?.data?.message || 'Unprocessable entity error returned no message'
                       }
                   } else {
                       errorAlert.value = {
                           type: 'danger',
                           message: err.response?.data?.message || 'An unknown error occurred.'
                       }
                   }
               }

               loading.value = false;
           },
       };
   }
});
</script>
