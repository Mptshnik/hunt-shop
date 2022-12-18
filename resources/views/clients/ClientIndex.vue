<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const isLoading = ref(true);
        const client = ref([]);
        const clients = ref([]);
        const rawClients = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const getClients = async () =>
        {
            await axios.get('client/all').then(res => {
                rawClients.value = res.data;
                clients.value = rawClients.value;
                isLoading.value = false;
            });
        }

        const handleSearch = (e) => {
            onSearch(clients, rawClients, e);
        }

        const sortTable  = (index) => {
            sortRecords(clients, rawClients, index);
        }

        onMounted(() => {
           getClients();
        });

        return{
            clients,
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
            <h1 class="card-title text-white mb-3">Клиенты</h1>
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
                    <th scope="col" v-on:click="sortTable('person_name')">ФИО</th>
                    <th scope="col" v-on:click="sortTable('organization_name')">Организация</th>
                    <th scope="col" v-on:click="sortTable('taxpayer_number')">ИНН</th>
                    <th scope="col" v-on:click="sortTable('phone_number')">Номер телефона</th>
                    <th scope="col" v-on:click="sortTable('juridical_address_name')">Юр. адрес</th>
                    <th scope="col" v-on:click="sortTable('physical_address')">Физ. адрес</th>
                    <th scope="col">Юр. статус</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="(client, i) in clients" :key="client.id">
                    <td><strong>{{i+1}}</strong></td>
                    <td>{{ client.person_name }}</td>
                    <td>{{client.organization_name}}</td>
                    <td>{{client.taxpayer_number}}</td>
                    <td>{{client.phone_number}}</td>
                    <td>{{client.juridical_address_name}}</td>
                    <td>{{client.physical_address}}</td>
                    <td>{{client.juridical_status?.name}}</td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
