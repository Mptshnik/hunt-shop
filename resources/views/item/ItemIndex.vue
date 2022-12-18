<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";
import router from "../../router";

export default {
    setup() {
        const categoryIdFilter = ref([]);
        const isLoading = ref(true);
        const item = ref([]);
        const promotions = ref([]);
        const errors = ref(null);
        const items = ref([]);
        const rawItems = ref([]);
        const itemCategories = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();
        const form = reactive({
            'name': '',
            'number': '',
            'count': '',
            'price': '',
            'description': '',
            'promotion_id': '',
            'sale_price': '',
            'item_category_id': '',
            'item_invoice_id': ''
        });

        const clearForm = () => {
            form.price = '';
            form.name = '';
            form.count = '';
            form.number = '';
            form.description = '';
            form.promotion_id = '';
            form.sale_price = ''
            form.item_category_id = '';
            form.item_invoice_id = '';
        };

        const addToCart = async (id) =>
        {
            let orderId = localStorage.getItem('orderId');
            if(orderId)
            {
                await axios.post('cart/add-item/'+id, {'orderId': orderId})
            }
            else {
                await axios.post('cart/add-item/'+id).then((response) => {
                    localStorage.setItem('orderId', response.data.id)
                })
            }

            $('#itemDetailsModal').modal('hide');
            $('#cartModal').modal('show');
        }

        const getItems = () =>
        {
            isLoading.value = true;

            axios.get('item/all').then(res => {
                rawItems.value = res.data;
                items.value = rawItems.value;
                isLoading.value = false;
            });
        }

        const getPromotions = () =>
        {
            axios.get('promotion/all').then(res => {
                promotions.value = res.data
            });
        }

        const getItemCategories = () =>
        {
            axios.get('item-category/all').then(res => {
                itemCategories.value = res.data;
            });
        }

        const itemDetails = (data) =>
        {
            item.value = data;
            $('#itemDetailsModal').modal('show');
        }

        const editItem = (data) =>{

            errors.value = '';

            form.name = data.name;
            form.count = data.count;
            form.number = data.number;
            form.price = data.price;
            form.description = data.description;
            form.sale_price = data.sale_price;
            form.promotion_id = data.promotion?.id;
            form.item_category_id = data.item_category?.id;
            form.item_invoice_id = data.item_invoice?.id;

            item.value = data;
            $('#itemModal').modal('show');
        }


        const filterByCategory = () =>
        {
            isLoading.value = true;

            axios.get('item/filterByCategory/' + categoryIdFilter.value).then(res => {
                rawItems.value = res.data;
                items.value = rawItems.value;
                isLoading.value = false;
            });
        }

        const updateItem = async () =>
        {
            errors.value = '';

            await axios.post('item/update/' + item.value.id, form).then((response) => {
                getItems();
                $('#itemModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const deleteItem = async (id) =>
        {
            await axios.post('item/delete/'+id).then((response) => {
                getItems();
            });
        }

        const handleSubmit = () => {
            updateItem();
        }

        const handleSearch = (e) => {
            onSearch(items, rawItems, e);
        }

        const sortTable  = (index) => {
            sortRecords(items, rawItems, index);
        }

        const navigateToCart = () => {
            router.push('/cart');
        }

        onMounted(() => {
            getItemCategories();
            getItems();
            getPromotions();
        });

        return{
            isLoading,
            errors,
            items,
            itemCategories,
            form,
            promotions,
            editItem,
            handleSearch,
            handleSubmit,
            sortTable,
            deleteItem,
            itemDetails,
            addToCart,
            navigateToCart,
            getItems,
            filterByCategory,
            item,
            categoryIdFilter
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Товары</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="Поиск" @input="handleSearch">
            </div>
            <div class="col-auto">
                <select class="form-select form-control-sm" v-model="categoryIdFilter" aria-label="Default select example">
                    <option v-for="category in itemCategories" :value=category.id>{{ category.name }}</option>
                </select>
            </div>
            <div class="col-auto">
                <button @click="filterByCategory" class="btn btn-dark">Фильтровать</button>
            </div>
            <div class="col-auto">
                <button @click="getItems" class="btn btn-dark">Сбросить фильтр</button>
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('name')">Название</th>
                    <th scope="col" v-on:click="sortTable('number')">Артикул</th>
                    <th scope="col" v-on:click="sortTable('count')">Количество</th>
                    <th scope="col" @click="sortTable('price')">Стоимость</th>
                    <th scope="col">Категория</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="item in items" :key="item.id">
                    <td>{{ item.name }}</td>
                    <td>{{item.number}}</td>
                    <td>{{item.count}}</td>
                    <td>{{item.price }}₽</td>
                    <td>{{item.item_category?.name}}</td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteItem(item.id)" class="btn btn-danger">Удалить</button>
                            <button @click="editItem(item)" class="ms-1 btn btn-secondary">Изменить</button>
                            <button @click="itemDetails(item)" class="ms-1 btn btn-dark">Детали</button>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>

    <div class="modal" id="cartModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Товар добавлен</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Товар был добавлен в корзину
                </div>
                <div class="modal-footer">
                    <button @click="navigateToCart" type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Перейти к корзине</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="itemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Изменение товра</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Наименование товара</label>
                            <input v-model="form.name" type="text" class="form-control" placeholder="название"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Описание</label>
                            <textarea v-model="form.description" type="text" class="form-control" placeholder="описание"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Артикул</label>
                            <input v-model="form.number" type="text" class="form-control" placeholder="артикул"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Количество</label>
                            <input v-model="form.count" type="text" class="form-control" placeholder="количество"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Стоимость</label>
                            <input v-model="form.price" type="text" class="form-control" placeholder="стоимость ₽"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Стоимость по акции</label>
                            <input v-model="form.sale_price" type="text" class="form-control" placeholder="стоимость ₽"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Категория товара</label>
                            <select class="form-select" v-model="form.item_category_id" aria-label="Default select example">
                                <option v-for="category in itemCategories" :value=category.id
                                        :selected="category.id === form.post_id">{{category.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Акция</label>
                            <select class="form-select" v-model="form.promotion_id" aria-label="Default select example">
                                <option v-for="promotion in promotions" :value=promotion.id
                                        :selected="promotion.id === form.promotion_id">{{promotion.name}}</option>
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


    <div class="modal" id="itemDetailsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Информация о товаре</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div>
                            <div class="card-body">
                                <h3 class="card-title">{{ item.name }}</h3>
                                <p class="card-text">{{item.description}}</p>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Стоимость: {{item.price}} ₽</li>
                                <li class="list-group-item">Количество: {{item.count}}</li>
                                <li v-if="item.promotion" class="list-group-item">Стомость по акции: {{item.sale_price}} ₽</li>
                                <li v-else class="list-group-item">Стоимость по акции: Товар не участвует в акциях</li>
                                <li v-if="item.promotion" class="list-group-item">Акция: {{item.promotion?.name}}</li>
                                <li class="list-group-item">Категория: {{item.item_category?.name}}</li>
                                <li class="list-group-item">Товарная накладная: № {{item.item_invoice?.number}}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <form @submit.prevent="addToCart(item.id)">
                            <button v-if="item.count > 0" type="submit" class="btn btn-dark">В корзину</button>
                            <button v-else type="submit" class="btn btn-dark disabled">В корзину</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
