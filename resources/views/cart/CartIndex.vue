<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';

export default {
    setup()
    {
        const cart = ref([]);
        const items = ref([]);

        const getCart = () =>
        {
            axios.get('cart/get').then(res => {
                if(res.data.hasOwnProperty('message'))
                {
                    console.log(res.data.message);
                }
                else
                {
                    cart.value = res.data;
                    items.value = cart.items;
                }
            });
        }

        onMounted(()=>{
            getCart();
        });

        return{
            items,
            cart
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Корзина</h1>
        </div>
        <div v-if="items.length === 0">
            <h3 class="text-white text-center">
                Корзина пустая
            </h3>
            <div class="row">
                <div class="col text-center">
                    <router-link to="/items" class="btn btn-dark">Добавить товары</router-link>
                </div>
            </div>
        </div>
        <div v-else>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Итог</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-active" v-for="item in items" :key="item.id">
                    <td>
                        {{ item.name }}
                    </td>
                    <td>
                        {{ item.items_count }}
                    </td>
                    <td>
                        {{ item.price_for_count }}
                    </td>
                    <td>
                        <div class="float-end">
                            <button class="btn btn-danger">Удалить</button>
                            <button class="ms-1 btn btn-secondary">Добавить</button>
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
