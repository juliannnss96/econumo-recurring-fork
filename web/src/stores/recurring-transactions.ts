import { defineStore } from 'pinia';
import { date } from 'quasar';
import RecurringTransactionAPIv1 from '../modules/api/v1/recurring-transaction';
import { v4 as uuid } from 'uuid';
import _ from 'lodash';
import { useLocalStorage } from '@vueuse/core';
import { StorageKeys } from '../modules/storage';
import { RemovableRef } from '@vueuse/shared';
import {
    CreateRecurringTransactionRequestDto,
    RecurringTransactionDto,
    ListRecurringTransactionsResponseDto,
    CreateRecurringTransactionResponseDto
} from '../modules/api/v1/dto/recurring-transaction.dto';
import { Id, DateString } from '../modules/types';
import { computed, ref } from 'vue';
import { DATE_TIME_FORMAT } from '../modules/constants';

export const useRecurringTransactionsStore = defineStore('recurringTransactions', () => {
    const items = useLocalStorage(StorageKeys.RECURRING_TRANSACTIONS, []) as RemovableRef<RecurringTransactionDto[]>;
    const itemsLoadedAt = useLocalStorage(StorageKeys.RECURRING_TRANSACTIONS + '_loaded_at', null) as RemovableRef<DateString | null>;
    const isLoaded = computed(() => !!itemsLoadedAt.value);

    function fetchItems() {
        return RecurringTransactionAPIv1.getList((response: ListRecurringTransactionsResponseDto['data']) => {
            items.value = response.items;
            itemsLoadedAt.value = date.formatDate(new Date(), DATE_TIME_FORMAT);
        }, (error: any) => {
            console.log('Recurring transactions error', error);
            itemsLoadedAt.value = 'error';
            return error;
        });
    }

    function createItem(params: CreateRecurringTransactionRequestDto) {
        if (!params.id) {
            params.id = uuid();
        }
        return RecurringTransactionAPIv1.create(params, (response: CreateRecurringTransactionResponseDto['data']) => {
            items.value.push(response.item);
            return response.item;
        }, (error: any) => {
            console.log('Recurring transactions error', error);
            return error;
        });
    }

    function deleteItem(id: Id) {
        return RecurringTransactionAPIv1.delete(id, () => {
            _.remove(items.value, (item) => item.id === id);
            return true;
        }, (error: any) => {
            console.log('Recurring transactions error', error);
            itemsLoadedAt.value = null; // Set itemsLoadedAt on error to unblock app
            return error;
        });
    }

    return {
        items,
        itemsLoadedAt,
        isLoaded,
        fetchItems,
        createItem,
        deleteItem
    };
});
