<template>
    <div class="calculator">
        <h1 class="title bottom-border">Calculator</h1>

        <div class="calculator-box">
            <alert v-if="errorAlert" :type="errorAlert.type" :message="errorAlert.message" />

            <form @submit.prevent="onSubmit">
                <input v-model="expression" class="expression-input" type="text" placeholder="Enter a mathematical expression..." :disabled="loading">

                <button class="expression-submit" type="submit" :disabled="loading">Submit</button>
            </form>

            <div class="example-expressions">
                <h2>Example Expressions</h2>

                <ul class="expression-list">
                    <li v-for="(example, idx) in exampleExpressions" :key="idx" @click="() => expression = example[0]">{{ example[0] }} => {{ example[1] }}</li>
                </ul>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">
$bg-color: #e4e4e7;
$fg-color: #cbd5e1;

.calculator {
    background: $bg-color;
    border-radius: 8px;
    width: 40%;
    position: relative;

    .title {
        padding: 0.5em 0;
        text-align: center;

        &.bottom-border {
            border-bottom: 2px solid black;
        }
    }
}

.calculator-box {
    padding: 8px;

    form {
        display: flex;

        input {
            border-radius: 6px;
            font-size: 18px;
            padding: 0.5rem;
        }

        button[type="submit"] {
            margin-left: 8px;
        }
    }
}

.expression-input {
    background: $fg-color;
    border-radius: 8px;
    width: 100%;
}

.expression-submit {
    padding: 0 0.5rem;
    cursor: pointer;
}

.example-expressions {
    margin-top: 1rem;
}

.expression-list {
    list-style-type: none;

    li {
        margin: 0.5rem 0;
        cursor: pointer;
    }
}
</style>

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
