<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import router from "../../router";

export default {
    setup()
    {
        const errors = ref(null);
        const cart = ref([]);
        const juridicalStatuses = ref([
            {'id': '1', 'name': 'Юридическое лицо'},
            {'id': '2', 'name': 'Физическое лицо'}
        ]);
        const items = ref([]);
        const form = reactive({
            'last_name': '',
            'first_name': '',
            'middle_name': '',
            'phone_number': '',
            'organisation_name': '',
            'taxpayer_number': '',
            'juridical_address': '',
            'physical_address': '',
            'juridical_status_id': '2'
        });

        const confirmOrder = () =>
        {
            errors.value = '';
            $('#orderModal').modal('show');
        }

        const navigateToItems = () =>
        {
            router.push('/items')
        }

        const storeOrder = async () =>
        {
            errors.value = '';
            let orderId = localStorage.getItem('orderId');

            await axios.post('client/create', form).then((res) => {
                axios.post('order/confirm', {'orderId': orderId, 'client_id': res.data.client.id}).
                then((res) => {
                    $('#orderModal').modal('hide');
                    $('#orderCreated').modal('show');

                    localStorage.removeItem('orderId');
                    getCart();
                });
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const removeFromCart = async (id) =>
        {
            let orderId = localStorage.getItem('orderId');
            if(orderId)
            {
                await axios.post('cart/remove-item/'+id, {'orderId': orderId}).then((res) => {
                    getCart();
                });
            }
            else {
                await axios.post('cart/remove-item/'+id).then((response) => {
                    localStorage.setItem('orderId', response.data.id)
                })
            }
        }

        const addToCart = async (id) =>
        {
            let orderId = localStorage.getItem('orderId');
            if(orderId)
            {
                await axios.post('cart/add-item/'+id, {'orderId': orderId}).then((res) => {
                    getCart();
                });
            }
            else {
                await axios.post('cart/add-item/'+id).then((response) => {
                    localStorage.setItem('orderId', response.data.id)
                })
            }
        }

        const getCart = () =>
        {
            let orderId = localStorage.getItem('orderId');
            if (orderId) {
                axios.get('cart/get' + '?orderId=' + orderId).then((res) => {
                    cart.value = res.data;
                    items.value = cart.value.items;
                })
            } else {
                items.value = [];
                cart.value = [];
                console.log('Корзина пустая');
            }
        }

        const handleSubmit = () => {
            storeOrder();
        }

        onMounted(()=>{
            getCart();
        });

        return{
            items,
            cart,
            errors,
            juridicalStatuses,
            form,
            navigateToItems,
            addToCart,
            removeFromCart,
            handleSubmit,
            confirmOrder
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
                    <th scope="col">Цена за штуку</th>
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
                        {{ item.price + ' ₽'}}
                    </td>
                    <td>
                        {{ item.items_count }}
                    </td>
                    <td>
                        {{ item.price_for_count + ' ₽'}}
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="removeFromCart(item.id)" class="btn btn-danger btn-sm">-</button>
                            <button v-if="item.count > 0" @click="addToCart(item.id)" class="ms-1 btn btn-secondary btn-sm">+</button>
                            <button v-else class="ms-1 btn btn-secondary btn-sm disabled">+</button>
                        </div>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3"><strong>Итог</strong></td>
                    <td><strong>{{cart.total_sum}} ₽</strong></td>
                    <td>
                        <div class="float-end">
                            <button @click="confirmOrder" class="btn btn-outline-light">Оформить заказ</button>
                        </div>
                    </td>
                </tr>
                </tfoot>
            </table>
            <router-link to="/items" class="btn btn-dark">К товарам</router-link>
        </div>
    </div>


    <div class="modal" id="orderCreated" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Заказ оформлен</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Заказ был успешно создан
                </div>
                <div class="modal-footer">
                    <button @click="navigateToItems" type="button" class="btn btn-outline-success" data-bs-dismiss="modal">Перейти к товарам</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ок</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="orderModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Оформление заказа</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Юридический статус клиента</label>
                            <select class="form-select" v-model="form.juridical_status_id" aria-label="Default select example">
                                <option v-for="status in juridicalStatuses" :value=status.id
                                        :selected="status.id === form.juridical_status_id">{{status.name}}</option>
                            </select>
                        </div>
                        <div v-if="form.juridical_status_id === '2'">
                            <div class="row">
                                <div class="form-group col">
                                    <label class="form-label">Фамилия</label>
                                    <input v-model="form.last_name" type="text" class="form-control" placeholder="фамилия"/>
                                </div>
                                <div class="form-group col">
                                    <label class="form-label">Имя</label>
                                    <input v-model="form.first_name" type="text" class="form-control" placeholder="имя"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Отчество</label>
                                <input v-model="form.middle_name" type="text" class="form-control" placeholder="отчество"/>
                            </div>
                        </div>
                        <div v-else>
                            <div class="form-group col">
                                <label class="form-label">Наименование организации</label>
                                <input v-model="form.organisation_name" type="text" class="form-control"
                                       placeholder="организация"/>
                            </div>
                        </div>
                        <div class="form-group col">
                            <label class="form-label">ИНН</label>
                            <input v-model="form.taxpayer_number" type="text" class="form-control"
                                   placeholder="инн"/>
                        </div>
                        <div class="form-group col">
                            <label class="form-label">Номер телефона</label>
                            <input v-model="form.phone_number" type="text" class="form-control"
                                   placeholder="организация"/>
                        </div>
                        <div class="form-group col">
                            <label class="form-label">Физический адрес</label>
                            <textarea v-model="form.physical_address" type="text" class="form-control"
                                   placeholder="физический адрес"/>
                        </div>
                        <div v-if="form.juridical_status_id === '1'" class="form-group col">
                            <label class="form-label">Юридический адрес</label>
                            <textarea v-model="form.juridical_address" type="text" class="form-control"
                                   placeholder="юридический адрес"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-dark">Подтвердить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
