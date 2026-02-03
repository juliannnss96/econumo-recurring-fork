import axios from 'axios';
import { setDefaultIfNotFunction } from '../helper'
import apiClient from '../apiClient';
import appConfig from '../../../modules/config';
import { Id } from '../../types';
import {
    CreateRecurringTransactionRequestDto,
    CreateRecurringTransactionResponseDto,
    ListRecurringTransactionsResponseDto
} from './dto/recurring-transaction.dto';

export default Object.assign(new apiClient(), {
    getList(onSuccess: (response: any) => void, onError: (error: any) => void) {
        onSuccess = setDefaultIfNotFunction(onSuccess);
        onError = setDefaultIfNotFunction(onError);
        return axios.get<ListRecurringTransactionsResponseDto>(`${appConfig.backendHost()}/api/v1/recurring-transaction/list`)
            .then((response) => onSuccess(response.data.data))
            .catch(onError);
    },

    create(params: CreateRecurringTransactionRequestDto, onSuccess: (response: any) => void, onError: (error: any) => void) {
        onSuccess = setDefaultIfNotFunction(onSuccess);
        onError = setDefaultIfNotFunction(onError);
        return axios.post<CreateRecurringTransactionResponseDto>(`${appConfig.backendHost()}/api/v1/recurring-transaction/create`, params)
            .then((response) => onSuccess(response.data.data))
            .catch(onError);
    },

    delete(id: Id, onSuccess: (response: any) => void, onError: (error: any) => void) {
        onSuccess = setDefaultIfNotFunction(onSuccess);
        onError = setDefaultIfNotFunction(onError);
        return axios.delete(`${appConfig.backendHost()}/api/v1/recurring-transaction/${id}`)
            .then((response) => onSuccess(response.data))
            .catch(onError);
    }
});
