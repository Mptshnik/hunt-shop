<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const isLoading = ref(true);
        const errors = ref(null);
        const itemInvoice = ref([]);
        const itemInvoices = ref([]);
        const providers = ref([]);
        const organization = ref([]);
        const rawItemInvoices = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();
        const form = reactive({
            'number': '',
            'date': '',
            'seller_id': '',
            'provider_id': '',
        });

        const getItemInvoices = async () =>
        {
            await axios.get('item-invoice/all').then(res => {
                rawItemInvoices.value = res.data;
                itemInvoices.value = rawItemInvoices.value;
                isLoading.value = false;
            });
        }

        const editItemInvoice = (data) =>
        {
            errors.value = '';

            itemInvoice.value = data;

            form.number = data.number;
            form.date = data.date;
            form.seller_id = data.seller?.id;
            form.provider_id = data.provider?.id;

            $('#itemInvoiceModal').modal('show');
        }

        const clearForm = () => {
            form.number = '';
            form.date = '';
            form.seller_id = '';
            form.provider_id = '';
        }

        const updateItemInvoice = async () =>
        {
            errors.value = '';

            await axios.post('item-invoice/update/' + itemInvoice.value.id, form).then((response) => {
                getItemInvoices();
                $('#itemInvoiceModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const getProviders = () =>
        {
            axios.get('provider/all').then(res => {
                providers.value = res.data;
            });
        }

        const getOrganization = async () =>
        {
            await axios.get('seller/1').then(res => {
                organization.value = res.data;
            });
        }

        const deleteItemInvoice = async (id) =>
        {
            await axios.post('item-invoice/delete/'+id).then((response) => {
                getItemInvoices();
            });
        }

        const handleSubmit = () => {
            updateItemInvoice();
        }

        const handleSearch = (e) => {
            onSearch(itemInvoices, rawItemInvoices, e);
        }

        const sortTable  = (index) => {
            sortRecords(itemInvoices, rawItemInvoices, index);
        }

        onMounted(() => {
            getItemInvoices();
            getProviders();
            getOrganization();
        })

        return{
            isLoading,
            form,
            errors,
            itemInvoices,
            providers,
            organization,
            editItemInvoice,
            handleSubmit,
            deleteItemInvoice,
            handleSearch,
            sortTable
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Товарные накладные</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <router-link class="btn btn-dark" to="/item-invoice/create">Добавить</router-link>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="Поиск" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('number')">Номер</th>
                    <th scope="col" v-on:click="sortTable('date')">Дата</th>
                    <th scope="col">Покупатель</th>
                    <th scope="col">Поставщик</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="itemInvoice in itemInvoices" :key="itemInvoice.id">
                    <td>{{ itemInvoice.number }}</td>
                    <td>
                        {{itemInvoice.date}}
                    </td>
                    <td>
                        {{itemInvoice.seller?.name}}
                    </td>
                    <td>
                        {{itemInvoice.provider?.name}}
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteItemInvoice(itemInvoice.id)" class="btn btn-danger">Удалить</button>
                            <button @click="editItemInvoice(itemInvoice)" class="ms-1 btn btn-secondary">Изменить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>


    <div class="modal" id="itemInvoiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Изменение товарной накладной</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Номер товарной накладной</label>
                            <input type="text" v-model="form.number" class="form-control" placeholder="номер"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Дата</label>
                            <input type="text" v-model="form.date" class="form-control" placeholder="дата (ДД-ММ-ГГГГ)"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Выберите поставщика</label>
                            <select class="form-select" v-model="form.provider_id" aria-label="Default select example">
                                <option v-for="provider in providers" :value=provider.id
                                        :selected="provider.id === form.provider_id">
                                    {{provider.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Укажите данные организации</label>
                            <select class="form-select" v-model="form.seller_id" aria-label="Default select example">
                                <option v-if="organization" selected :value=organization.id>{{organization.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-dark">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
