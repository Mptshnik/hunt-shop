<script>
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const isLoading = ref(true);
        const editing = ref([]);
        const errors = ref([]);
        const provider = ref([]);
        const providers = ref([]);
        const rawProviders = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const form = reactive({
            'name': '',
            'taxpayer_number': '',
            'phone_number': '',
            'address': '',
        })

        const clearForm = () =>
        {
            form.name = '';
            form.taxpayer_number = '';
            form.phone_number = '';
            form.address = '';
        }

        errors.value = null;

        const getProviders = () =>
        {
            axios.get('provider/all').then(res => {
                rawProviders.value = res.data
                providers.value = rawProviders.value;
                isLoading.value = false;
            });
        }

        const getProvider = (id) =>
        {
            axios.get('provider/' + id).then(res => {
                provider.value = res.data
            });
        }

        const deleteProvider = async (id) => {
            await axios.post('provider/delete/' + id).then(() => {
                getProviders()
            });
        }

        const addProvider = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#providerModal').modal('show');
        }

        const storeProvider= async (data) =>
        {
            errors.value = '';

            await axios.post('provider/create', data).then((response) => {
                getProviders();
                $('#providerModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editProvider = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#providerModal').modal('show');

            form.name = data.name;
            form.address = data.address;
            form.phone_number = data.phone_number;
            form.taxpayer_number = data.taxpayer_number;

            provider.value = data;
        }

        const updateProvider = async (data) =>
        {
            errors.value = '';

            await axios.post('provider/update/' + provider.value.id, data).then((response) => {
                getProviders();
                $('#providerModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const handleSubmit = () => {
            if (editing.value) {
                updateProvider({...form});
            } else {
                storeProvider({...form});
            }
        }

        const handleSearch = (e) => {
            onSearch(providers, rawProviders, e);
        }

        const sortTable  = (index) => {
            sortRecords(providers, rawProviders, index);
        }

        onMounted(getProviders);

        return{
            form,
            errors,
            providers,
            deleteProvider,
            editing,
            addProvider,
            editProvider,
            handleSubmit,
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
            <h1 class="card-title text-white mb-3">Поставщики</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addProvider" class="btn btn-dark">Добавить</button>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="Поиск" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('name')">Наименование</th>
                    <th scope="col" v-on:click="sortTable('address')">Адрес</th>
                    <th scope="col" v-on:click="sortTable('taxpayer_number')">ИНН</th>
                    <th scope="col" v-on:click="sortTable('phone_number')">Номер телефона</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="provider in providers" :key="provider.id">
                    <td>
                        {{ provider.name }}
                    </td>
                    <td>
                        {{provider.address}}
                    </td>
                    <td>
                        {{provider.taxpayer_number}}
                    </td>
                    <td>
                        {{provider.phone_number}}
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteProvider(provider.id)" class="btn btn-danger me-2">Удалить</button>
                            <button @click="editProvider(provider)" class="btn btn-secondary me-2">Изменить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>





    <div class="modal" id="providerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">Изменение поставщика</h5>
                    <h5 v-else class="modal-title">Добавление поставщика</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Наименование</label>
                            <input v-model="form.name" type="text" class="form-control" placeholder="наименование"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Адрес</label>
                            <textarea v-model="form.address" type="text" class="form-control" placeholder="адрес"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">ИНН</label>
                            <input v-model="form.taxpayer_number" type="text" class="form-control" placeholder="инн"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Номер телефона</label>
                            <input v-model="form.phone_number" type="text" class="form-control" placeholder="номер телефона"/>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Отмена</button>
                        <button v-if="editing" type="submit" class="btn btn-outline-dark">Сохранить</button>
                        <button v-else type="submit" class="btn btn-dark">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<style scoped>

</style>
