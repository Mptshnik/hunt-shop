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
        const application = ref([]);
        const applications = ref([]);
        const rawApplications = ref([]);
        const user = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();
        const form = reactive({
            'name': '',
            'number': '',
            'content': '',
            'employee_id': '',
        })

        const getAuthorizedUser = async () =>
        {
            await axios.get('/authorized-user').then(res => {
                user.value = res.data
                console.log(res);
            });
        }

        const clearForm = () =>
        {
            form.name = '';
            form.content = '';
            form.number = '';
        }

        errors.value = null;

        const getApplications= async () =>
        {
            await axios.get('purchase-application/all').then(res => {
                rawApplications.value = res.data
                applications.value = rawApplications.value;
                isLoading.value = false;
            });
        }

        const getApplication = (id) =>
        {
            axios.get('purchase-application/' + id).then(res => {
                application.value = res.data
            });
        }

        const deleteApplication = async (id) => {
            await axios.post('purchase-application/delete/' + id).then(() => {
                getApplications()
            });
        }

        const addApplication = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#applicationModal').modal('show');
        }

        const storeApplication = async (data) =>
        {
            errors.value = '';
            data.employee_id = user.value.employee?.id;

            await axios.post('purchase-application/create', data).then((response) => {
                getApplications();
                $('#applicationModal').modal('hide');
                clearForm();
            }).catch((error) => {
                if(error.response.data.hasOwnProperty('employee_message'))
                {
                    errors.value += error.response.data.employee_message;
                }

                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editApplication = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#applicationModal').modal('show');

            form.name = data.name;
            form.number = data.number;
            form.content = data.content;

            application.value = data;
        }

        const updateApplication = async (data) =>
        {
            errors.value = '';

            await axios.post('purchase-application/update/' + application.value.id, data).then((response) => {
                getApplications();
                $('#applicationModal').modal('hide');

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
                updateApplication({...form});
            } else {
                storeApplication({...form});
            }
        }

        const handleSearch = (e) => {
            onSearch(applications, rawApplications, e);
        }

        const sortTable  = (index) => {
            sortRecords(applications, rawApplications, index);
        }

        onMounted(() => {
            getAuthorizedUser();
            getApplications();
        });

        return{
            applications,
            addApplication,
            deleteApplication,
            editApplication,
            errors,
            form,
            editing,
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
            <h1 class="card-title text-white mb-3">Заявки на закупку товаров</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addApplication" class="btn btn-dark">Добавить</button>
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
                    <th scope="col" v-on:click="sortTable('number')">Номер</th>
                    <th scope="col" v-on:click="sortTable('content')">Содержание</th>
                    <th scope="col">Составитель</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    Загрузка...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="application in applications" :key="application.id">
                    <td>
                        {{ application.name }}
                    </td>
                    <td>
                        {{application.number}}
                    </td>
                    <td class="content">
                        {{application.content}}
                    </td>
                    <td>
                        {{
                            application.employee?.last_name + ' '
                            + application.employee?.first_name + ' '
                            + application.employee?.middle_name
                        }}
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteApplication(application.id)" class="btn btn-danger me-2">Удалить</button>
                            <button @click="editApplication(application)" class="btn btn-secondary me-2">Изменить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>





    <div class="modal" id="applicationModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">Изменение заявки</h5>
                    <h5 v-else class="modal-title">Добавление заявки</h5>
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
                            <label class="form-label">Номер</label>
                            <input v-model="form.number" type="text" class="form-control" placeholder="номер"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Содержание</label>
                            <textarea v-model="form.content" type="text" class="form-control" placeholder="содержание"/>
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
.content{
    word-wrap:break-word; /*old browsers*/
    overflow-wrap:break-word;
    word-break: break-all;
    max-width: 450px;
}
</style>
