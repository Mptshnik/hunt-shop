<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const isLoading = ref(true);
        const order = ref([]);
        const orders = ref([]);
        const rawOrders= ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const getOrders= async () =>
        {
            await axios.get('order/all').then(res => {
                rawOrders.value = res.data;
                orders.value = rawOrders.value;
                isLoading.value = false;
            });
        }

        const handleSearch = (e) => {
            onSearch(orders, rawOrders, e);
        }

        const sortTable  = (index) => {
            sortRecords(orders, rawOrders, index);
        }

        onMounted(() => {
            getOrders();
        })

        return{
            isLoading,
            orders,
            sortTable,
            handleSearch
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Заказы</h1>
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
                    <th scope="col" @click="sortTable('number')">Название</th>
                    <th scope="col" @click="sortTable('number')">Номер</th>
                    <th scope="col">Статус</th>
                    <th scope="col" @click="sortTable('order_time')">Время оформления</th>
                    <th scope="col" @click="sortTable('total_sum')">Сумма заказа</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="order in orders" :key="order.id">
                    <td>{{'Заказ № ' + order.number}}</td>
                    <td>{{order.number}}</td>
                    <td>
                        <div v-if="order.status === 0">
                            Заказ формируется
                        </div>
                        <div v-else>
                            Оформлен
                        </div>
                    </td>
                    <td>{{order.order_time}}</td>
                    <td>{{order.total_sum + ' ₽'}}</td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>
</template>

<style scoped>

</style>
