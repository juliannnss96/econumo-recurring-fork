<template>
  <q-dialog v-model="internalValue" persistent>
    <q-card style="min-width: 350px">
      <q-card-section>
        <div class="text-h6">New Recurring Transaction</div>
      </q-card-section>

      <q-card-section class="q-pt-none">
        <q-form @submit="onSubmit" class="q-gutter-md">
          <q-btn-toggle
            v-model="form.type"
            spread
            no-caps
            toggle-color="primary"
            color="white"
            text-color="black"
            :options="[
              {label: 'Expense', value: 'expense'},
              {label: 'Income', value: 'income'},
              {label: 'Transfer', value: 'transfer'}
            ]"
          />

          <q-select
            v-model="form.accountId"
            :options="accountOptions"
            label="Account"
            emit-value
            map-options
            outlined
            :rules="[val => !!val || 'Field is required']"
          />

          <q-input
            v-model="form.amount"
            label="Amount"
            type="number"
            step="0.01"
            outlined
            prefix="$"
            :rules="[val => !!val || 'Field is required']"
          />

          <q-select
            v-if="form.type !== 'transfer'"
            v-model="form.categoryId"
            :options="categoryOptions"
            label="Category"
            emit-value
            map-options
            outlined
            clearable
          />

          <q-select
            v-if="form.type !== 'transfer'"
            v-model="form.payeeId"
            :options="payeeOptions"
            label="Payee"
            emit-value
            map-options
            outlined
            clearable
          />

          <q-input
            v-model="form.description"
            label="Description"
            outlined
            autogrow
          />

          <q-select
            v-model="form.interval"
            :options="intervalOptions"
            label="Interval"
            emit-value
            map-options
            outlined
            :rules="[val => !!val || 'Field is required']"
          />

          <q-input 
            v-model="form.startDate" 
            label="Start Date" 
            outlined 
            type="date"
            :rules="[val => !!val || 'Field is required']"
          />

          <div class="row reverse">
            <q-btn label="Create" type="submit" color="primary" :loading="loading" />
            <q-btn label="Cancel" flat color="primary" v-close-popup class="q-mr-sm" />
          </div>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useAccountsStore } from 'stores/accounts';
import { useCategoriesStore } from 'stores/categories';
import { usePayeesStore } from 'stores/payees';
import { useRecurringTransactionsStore } from 'stores/recurring-transactions';
import { v4 as uuid } from 'uuid';

const props = defineProps({
  modelValue: Boolean
});

const emit = defineEmits(['update:modelValue']);

const accountsStore = useAccountsStore();
const categoriesStore = useCategoriesStore();
const payeesStore = usePayeesStore();
const recurringStore = useRecurringTransactionsStore();

const internalValue = computed({
  get: () => props.modelValue,
  set: (val) => emit('update:modelValue', val)
});

const loading = ref(false);

const form = ref({
  type: 'expense' as 'expense' | 'income' | 'transfer',
  accountId: '',
  amount: '',
  description: '',
  categoryId: null as string | null,
  payeeId: null as string | null,
  interval: 'monthly' as 'daily' | 'weekly' | 'monthly' | 'yearly',
  startDate: new Date().toISOString().substring(0, 10)
});

const accountOptions = computed(() => accountsStore.accountsOrdered.map(a => ({
  label: a.name,
  value: a.id
})));

const categoryOptions = computed(() => categoriesStore.categoriesOrdered
  .filter(c => c.type === form.value.type)
  .map(c => ({
    label: c.name,
    value: c.id
  })));

const payeeOptions = computed(() => payeesStore.payeesOrdered.map(p => ({
  label: p.name,
  value: p.id
})));

const intervalOptions = [
  { label: 'Daily', value: 'daily' },
  { label: 'Weekly', value: 'weekly' },
  { label: 'Monthly', value: 'monthly' },
  { label: 'Yearly', value: 'yearly' }
];

async function onSubmit() {
  loading.value = true;
  try {
    await recurringStore.createItem({
      id: uuid(),
      ...form.value,
      startDate: form.value.startDate + ' 00:00:00'
    });
    internalValue.value = false;
    // Reset form
    form.value = {
      type: 'expense',
      accountId: '',
      amount: '',
      description: '',
      categoryId: null,
      payeeId: null,
      interval: 'monthly',
      startDate: new Date().toISOString().substring(0, 10)
    };
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
}
</script>
