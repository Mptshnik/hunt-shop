<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const isLoading = ref(true);
        const invoice = ref([]);
        const invoices = ref([]);
        const rawInvoices = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const getInvoices = async () =>
        {
            await axios.get('invoice/all').then(res => {
                rawInvoices.value = res.data;
                invoices.value = rawInvoices.value;
                isLoading.value = false;
            });
        }

        const handleSearch = (e) => {
            onSearch(invoices, rawInvoices, e);
        }

        const sortTable  = (index) => {
            sortRecords(invoices, rawInvoices, index);
        }

        onMounted(() => {
            getInvoices();
        });

        return{
            invoices,
            handleSearch,
            sortTable,
            isLoading
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Счет-фактуры</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="Поиск" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col" v-on:click="sortTable('number')">Номер</th>
                    <th scope="col" v-on:click="sortTable('date')">Дата</th>
                    <th scope="col">Продавец</th>
                    <th scope="col">Покупатель</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="(invoice, i) in invoices" :key="invoice.id">
                    <td><strong>{{i+1}}</strong></td>
                    <td>{{ invoice.number }}</td>
                    <td>{{invoice.date}}</td>
                    <td>{{invoice.seller?.name}}</td>
                    <td>
                        <div v-if="invoice.order?.client?.person_name.length > 0">
                            {{invoice.order?.client?.person_name }}
                        </div>
                        <div v-else>
                            {{invoice.order?.client?.organization_name }}
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
