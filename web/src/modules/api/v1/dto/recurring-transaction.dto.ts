import { Id, DateString } from '../../../types';

export interface RecurringTransactionDto {
    id: Id;
    type: 'expense' | 'income' | 'transfer';
    accountId: Id;
    amount: string;
    description: string;
    interval: 'daily' | 'weekly' | 'monthly' | 'yearly';
    nextRunDate: DateString;
    isActive: boolean;
    categoryId?: Id | null;
    payeeId?: Id | null;
    tagId?: Id | null;
}

export interface CreateRecurringTransactionRequestDto {
    id: Id;
    type: 'expense' | 'income' | 'transfer';
    accountId: Id;
    amount: string;
    description: string;
    interval: 'daily' | 'weekly' | 'monthly' | 'yearly';
    startDate: DateString;
    categoryId?: Id | null;
    payeeId?: Id | null;
    tagId?: Id | null;
}

export interface ListRecurringTransactionsResponseDto {
    data: {
        items: RecurringTransactionDto[];
    }
}

export interface CreateRecurringTransactionResponseDto {
    data: {
        item: RecurringTransactionDto;
    }
}
