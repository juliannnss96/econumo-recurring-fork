<template>
  <q-page padding>
    <div class="row items-center q-mb-md">
      <div class="text-h4">{{ $t('blocks.main.recurring') }}</div>
      <q-space />
      <q-btn class="bg-econumo-purple text-white" :label="$t('elements.button.add.label')" @click="openCreateDialog" />
    </div>

    <q-card v-if="items.length > 0" flat bordered>
      <q-list separator>
        <q-item v-for="item in items" :key="item.id" class="q-py-md">
          <q-item-section avatar>
            <q-icon 
              :name="item.type === 'expense' ? 'arrow_downward' : (item.type === 'income' ? 'arrow_upward' : 'swap_horiz')" 
              :color="item.type === 'expense' ? 'negative' : (item.type === 'income' ? 'positive' : 'info')"
              size="24px"
            />
          </q-item-section>

          <q-item-section>
            <q-item-label class="text-weight-medium">{{ item.description || 'No description' }}</q-item-label>
            <q-item-label caption class="text-grey-7">
              {{ formatAmount(item.amount, item.type) }} · {{ item.interval }}
            </q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label caption class="text-grey-6">Next run: {{ formatDate(item.nextRunDate) }}</q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-btn flat round color="negative" icon="delete" size="sm" @click="deleteItem(item.id)" />
          </q-item-section>
        </q-item>
      </q-list>
    </q-card>

    <div v-if="items.length === 0" class="text-center q-mt-xl">
      <q-icon name="event_repeat" size="100px" color="grey-4" />
      <div class="text-h6 text-grey-5 q-mt-md">No recurring transactions found</div>
      <q-btn class="bg-econumo-purple text-white q-mt-lg" label="Create your first rule" @click="openCreateDialog" />
    </div>

    <create-recurring-transaction-dialog v-model="showCreateDialog" />
  </q-page>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRecurringTransactionsStore } from 'stores/recurring-transactions';
import CreateRecurringTransactionDialog from './CreateRecurringTransactionDialog.vue';

const recurringStore = useRecurringTransactionsStore();
const showCreateDialog = ref(false);

const items = computed(() => recurringStore.items);

onMounted(() => {
    recurringStore.fetchItems();
});

function openCreateDialog() {
    showCreateDialog.value = true;
}

function deleteItem(id: string) {
    if (confirm('Are you sure you want to delete this recurring transaction?')) {
        recurringStore.deleteItem(id);
    }
}

function formatAmount(amount: number | string, type: string): string {
    const value = typeof amount === 'string' ? parseFloat(amount) : amount;
    const formatted = value.toLocaleString('es-AR', { minimumFractionDigits: 2 });
    return type === 'expense' ? `-${formatted}` : `+${formatted}`;
}

function formatDate(dateStr: string): string {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('es-AR', { day: '2-digit', month: '2-digit', year: 'numeric' });
}
</script>
