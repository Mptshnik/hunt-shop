<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import router from "../../router";

export default {
    setup()
    {
        let itemIndex = 0;
        let itemHasErrors = false;
        const editing = ref(false);
        const errors = ref(null);
        const itemErrors = ref(null);
        const providers = ref([]);
        const items = ref([]);
        const itemCategories = ref([]);
        const organization = ref([]);
        const itemInvoiceForm = reactive({
            'number': '',
            'date': '',
            'seller_id': '',
            'provider_id': '',
            'items': []
        });
        const itemForm = reactive({
            'name': '',
            'number': '',
            'count': '',
            'price': '',
            'item_category_id': '',
            'item_invoice_id': '0'
        });

        const clearItemForm = () => {
            itemForm.price = '';
            itemForm.name = '';
            itemForm.count = '';
            itemForm.number = '';
            itemForm.item_category_id = '';
            itemForm.item_invoice_id = '0';
        };

        const getItemCategories = () =>
        {
            axios.get('item-category/all').then(res => {
                itemCategories.value = res.data;
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

        const editItemInList = async () =>
        {
            itemErrors.value = '';
            itemHasErrors = false;
            if (items.value.some(item => item.number === itemForm.number &&
                items.value.indexOf(item) !== itemIndex)) {
                itemErrors.value += 'Товар с таким артикулом уже добавлен в список. ';
                itemHasErrors = true;
            }
            if (items.value.some(item => item.name === itemForm.name &&
                items.value.indexOf(item) !== itemIndex)) {
                itemErrors.value += 'Товар с таким названием уже добавлен в список. ';
                itemHasErrors = true;
            }
            if (itemHasErrors) {
                return;
            }

            await axios.post('item/validate', itemForm).then((response) => {
                items.value[itemIndex] = response.data.item;
                $('#itemModal').modal('hide');
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        itemErrors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const removeItem = async (index) =>
        {
            items.value.splice(index, 1);
        }

        const addItemToList = async () =>
        {
            itemErrors.value = '';
            itemHasErrors = false;
            if (items.value.some(item => item.number === itemForm.number)) {
                itemErrors.value += 'Товар с таким артикулом уже добавлен в список. ';
                itemHasErrors = true;
            }
            if (items.value.some(item => item.name === itemForm.name)) {
                itemErrors.value += 'Товар с таким названием уже добавлен в список. ';
                itemHasErrors = true;
            }
            if (itemHasErrors) {
                return;
            }

            await axios.post('item/validate', itemForm).then((response) => {
                items.value.unshift(response.data.item);
                $('#itemModal').modal('hide');
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        itemErrors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const storeItemInvoice = async () =>
        {
            errors.value = '';
            if(items.value.length === 0)
            {
                errors.value += 'В товарной накладной должен быть как минимум один товар. ';
            }

            itemInvoiceForm.items = items.value;
            await axios.post('item-invoice/create-with', itemInvoiceForm).then((response) => {
                router.push('/item-invoices');
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editItem = (index) =>{
            itemIndex = index;
            clearItemForm();
            let data = items.value[index];
            editing.value = true;

            itemErrors.value = '';

            itemForm.name = data.name;
            itemForm.count = data.count;
            itemForm.number = data.number;
            itemForm.price = data.price;
            itemForm.item_category_id = data.item_category_id;
            itemForm.item_invoice_id = '0';

            $('#itemModal').modal('show');
        }

        const addItem = () =>{
            editing.value = false;
            clearItemForm();
            itemErrors.value = '';
            $('#itemModal').modal('show');
        }

        const handleSubmit = () => {
            if(editing.value) {
                editItemInList();
            }
            else {
                addItemToList();
            }
        }

        onMounted(() => {
            getProviders();
            getOrganization();
            getItemCategories();
        })

        return{
            errors,
            itemErrors,
            providers,
            organization,
            itemForm,
            itemInvoiceForm,
            storeItemInvoice,
            handleSubmit,
            addItem,
            itemCategories,
            items,
            editing,
            editItem,
            removeItem,
        }
    }
}
</script>

<template>
    <div class="container">
        <div class="row g-2">
            <div class="col-sm-9">
                <div >
                    <div class="col">
                        <div class="col-auto">
                            <h1 class="card-title text-white mb-3">Добавление товарной накладной</h1>
                        </div>
                        <div class="col-auto">
                            <button type="button" @click="addItem" class="btn btn-dark mb-3 btn-sm">Добавить товар +</button>
                        </div>
                    </div>
                    <table class="table table-dark table-hover table-sm">
                        <thead>
                        <tr>
                            <th scope="col">Название</th>
                            <th scope="col">Артикул</th>
                            <th scope="col">Количество</th>
                            <th scope="col">Стоимость</th>
                            <th class="text-end">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="table-active" v-for="(item, i) in items" :key="i">
                            <td>{{ item.name }}</td>
                            <td>
                                {{item.number}}
                            </td>
                            <td>
                                {{item.count}}
                            </td>
                            <td>
                                {{item.price + ' ₽'}}
                            </td>
                            <td>
                                <div class="float-end">
                                    <button @click="removeItem(i)" class="btn btn-danger">Удалить</button>
                                    <button @click="editItem(i)" class="ms-1 btn btn-secondary">Изменить</button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm ms-4" style="margin-top: 100px">
                <form @submit.prevent="storeItemInvoice">
                    <div v-if="errors" class="text-white bg-danger" style="border-radius: 5px">
                        <div class="m-2">
                            {{errors}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-white">Номер товарной накладной</label>
                        <input type="text" v-model="itemInvoiceForm.number" class="form-control" placeholder="номер"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-white">Дата</label>
                        <input type="text" v-model="itemInvoiceForm.date" class="form-control" placeholder="дата (ДД-ММ-ГГГГ)"/>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-white">Выберите поставщика</label>
                        <select class="form-select" v-model="itemInvoiceForm.provider_id" aria-label="Default select example">
                            <option v-for="provider in providers" :value=provider.id>{{provider.name}}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label text-white">Укажите данные организации</label>
                        <select class="form-select" v-model="itemInvoiceForm.seller_id" aria-label="Default select example">
                            <option :value=organization.id>{{organization.name}}</option>
                        </select>
                    </div>
                    <button type="submit" class="form-control btn btn-outline-light mt-5">Добавить</button>
                </form>
            </div>
        </div>
    </div>




    <div class="modal" id="itemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">Изменение товара в товарной накладной</h5>
                    <h5 v-else class="modal-title">Добавление товара к товарной накладной</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="itemErrors" class="text-danger">
                            {{itemErrors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Наименование товара</label>
                            <input v-model="itemForm.name" type="text" class="form-control" placeholder="название"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Артикул</label>
                            <input v-model="itemForm.number" type="text" class="form-control" placeholder="артикул"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Количество</label>
                            <input v-model="itemForm.count" type="text" class="form-control" placeholder="количество"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Стоимость</label>
                            <input v-model="itemForm.price" type="text" class="form-control" placeholder="стоимость ₽"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Категория товара</label>
                            <select v-if="editing" class="form-select" v-model="itemForm.item_category_id" aria-label="Default select example">
                                <option v-for="category in itemCategories" :value=category.id
                                        :selected="category.id === itemForm.post_id">{{category.name}}</option>
                            </select>
                            <select v-else class="form-select" v-model="itemForm.item_category_id" aria-label="Default select example">
                                <option v-for="category in itemCategories" :value=category.id>{{category.name}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button v-if="editing" type="submit" class="btn btn-dark">Сохранить</button>
                        <button v-else type="submit" class="btn btn-dark">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
