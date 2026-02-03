<template>
  <q-page padding>
    <div class="row items-center q-mb-md">
      <div class="text-h4">{{ $t('blocks.main.recurring') }}</div>
      <q-space />
      <q-btn color="primary" :label="$t('elements.button.add.label')" @click="openCreateDialog" />
    </div>

    <q-card v-if="items.length > 0">
      <q-list separator>
        <q-item v-for="item in items" :key="item.id">
          <q-item-section avatar>
            <q-icon 
              :name="item.type === 'expense' ? 'arrow_downward' : (item.type === 'income' ? 'arrow_upward' : 'swap_horiz')" 
              :color="item.type === 'expense' ? 'negative' : (item.type === 'income' ? 'positive' : 'info')"
            />
          </q-item-section>

          <q-item-section>
            <q-item-label>{{ item.description || 'No description' }}</q-item-label>
            <q-item-label caption>
              {{ item.amount }} - {{ item.interval }}
            </q-item-label>
          </q-item-section>

          <q-item-section side>
            <q-item-label caption>Next run: {{ item.nextRunDate }}</q-item-label>
            <div class="row">
              <q-btn flat round color="negative" icon="delete" @click="deleteItem(item.id)" />
            </div>
          </q-item-section>
        </q-item>
      </q-list>
    </q-card>

    <div d-v-if="items.length === 0" class="text-center q-mt-xl">
      <q-icon name="event_repeat" size="100px" color="grey-4" />
      <div class="text-h6 text-grey-5 q-mt-md">No recurring transactions found</div>
      <q-btn color="primary" class="q-mt-lg" label="Create your first rule" @click="openCreateDialog" />
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
</script>
