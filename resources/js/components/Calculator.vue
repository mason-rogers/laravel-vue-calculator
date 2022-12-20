<template>
    <div class="bg-neutral-900 rounded-lg w-2/5 relative overflow-y-scroll" ref="containerRef">
        <h1 class="text-center mt-4 font-semibold text-lg">Calculator</h1>

        <div class="flex flex-col">
            <alert v-if="errorAlert" class="mb-4" :type="errorAlert.type" :message="errorAlert.message" />

            <div class="rounded font-bold text-3xl text-zinc-100 p-4 w-full text-right" type="text">
                {{ expression || '0' }}
            </div>

            <div class="mt-4 p-4 h-3/4 bg-neutral-500 rounded-lg items-start grid grid-cols-4 gap-x-4 gap-y-4 overflow-y-hidden">
                <calculator-button type="clear" use-half-width @click="expression = ''">C</calculator-button>
                <calculator-button type="operand" @click="toggleNegative()">±</calculator-button>
                <calculator-button type="operand" @click="addOperator('/')">/</calculator-button>

                <calculator-button type="number" @click="addNumber(7)">7</calculator-button>
                <calculator-button type="number" @click="addNumber(8)">8</calculator-button>
                <calculator-button type="number" @click="addNumber(9)">9</calculator-button>
                <calculator-button type="operand" @click="addOperator('*')">*</calculator-button>

                <calculator-button type="number" @click="addNumber(4)">4</calculator-button>
                <calculator-button type="number" @click="addNumber(5)">5</calculator-button>
                <calculator-button type="number" @click="addNumber(6)">6</calculator-button>
                <calculator-button type="operand" @click="addOperator('-')">-</calculator-button>

                <calculator-button type="number" @click="addNumber(1)">1</calculator-button>
                <calculator-button type="number" @click="addNumber(2)">2</calculator-button>
                <calculator-button type="number" @click="addNumber(3)">3</calculator-button>
                <calculator-button type="operand" @click="addOperator('+')">+</calculator-button>

                <calculator-button type="number" use-half-width @click="addNumber(0)">0</calculator-button>
                <calculator-button type="number" @click="addDecimal()">.</calculator-button>
                <calculator-button type="equal" @click="onSubmit">=</calculator-button>

            </div>

            <div class="my-4">
                <h2 class="text-center font-semibold text-lg">Example Expressions</h2>

                <ul class="mt-4 gap-x-4 space-y-4 px-4">
                    <li
                        v-for="(example, idx) in exampleExpressions"
                        :key="idx"
                        @click="useExampleExpression(example[0])"
                        class="bg-neutral-500 rounded-lg cursor-pointer p-4 font-bold text-xl text-zinc-100 p-2 flex justify-between"
                    >
                        <p>{{ example[0] }}</p>
                        <p> = {{ example[1] }}</p>
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
import CalculatorButton from "./CalculatorButton.vue";

interface CalculationError {
    type: 'warning' | 'danger';
    message: string
}

export default defineComponent({
    components: {CalculatorButton, Alert},
    emits: ['result'],

    setup(props, { emit }) {
        const containerRef = ref<HTMLDivElement>();
        const loading = ref(false);
        const expression = ref('');
        const errorAlert = ref<CalculationError | null>(null);

        return {
            containerRef,
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

            useExampleExpression(exampleExpression: string) {
                containerRef.value.scrollTop = 0;
                expression.value = exampleExpression;
            },

            addOperator(operator: '+' | '-' | '*' | '/') {
                if (/( [+\-*\/] )$/.test(expression.value)) {
                    expression.value = expression.value.slice(0, -3) + ` ${operator} `
                } else {
                    expression.value += ` ${operator} `
                }
            },

            addNumber(number: number) {
                expression.value += number
            },

            addDecimal() {
                if (expression.value.endsWith('.')) return;
                expression.value += '.';
            },

            toggleNegative() {
                const lastNumberRegex = /(^|[-+]?\d*\.?\d*)$/;
                const match = expression.value.match(lastNumberRegex);
                if (!match[1]) return;

                const newExpression = expression.value.split(' ');
                const lastItem = newExpression[newExpression.length - 1];
                newExpression[newExpression.length - 1] = (lastItem.startsWith('-') ? lastItem.slice(1) : `-${lastItem}`);

                expression.value = newExpression.join(' ');
            },

            async onSubmit() {
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
                    expression.value = data.result.result.toString();

                    emit('result', evaluatedExpression, data.result.result);
                } catch (err: AxiosError) {
                    if (err.response.status === 422) {
                        errorAlert.value = {
                            type: 'warning',
                            message: err.response?.data?.message || 'Unprocessable entity error returned no message'
                        };
                   } else {
                        errorAlert.value = {
                            type: 'danger',
                            message: err.response?.data?.message || 'An unknown error occurred.'
                       };
                   }
                }

                loading.value = false;
           },
       };
   }
});
</script>
