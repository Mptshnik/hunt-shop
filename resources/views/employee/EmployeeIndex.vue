
<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';

export default {
    setup()
    {
        const errors = ref([]);
        const employee = ref([]);
        const posts = ref([]);
        const employees = ref([]);
        const editing = ref(false);
        const form = reactive({
            'last_name': '',
            'first_name': '',
            'middle_name': '',
            'passport_series': '',
            'passport_number': '',
            'birthday_date': '',
            'post_id': ''
        })

        const clearForm = () =>
        {
            form.last_name = '';
            form.first_name = '';
            form.middle_name = '';
            form.passport_series = '';
            form.passport_number = '';
            form.birthday_date = '';
            form.post_id = '';
        }

        errors.value = null;

        const getEmployee = (id) =>
        {
            axios.get('employee/' + id).then(res => {
                employee.value = res.data;
            });
        }

        const getPosts = async () =>
        {
            await axios.get('post/all').then(res => {
                posts.value = res.data;
            });
        }

        const deleteEmployee = async (id) =>
        {
            await axios.post('employee/delete/'+id);
            await getEmployees();
        }

        const getEmployees = async () =>
        {
            await axios.get('employee/all').then(res => {
                employees.value = res.data;
            });
        }

        const addEmployee = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#employeeModal').modal('show');
        }

        const storeEmployee = async (data) =>
        {
            errors.value = '';

            await axios.post('employee/create', data).then((response) => {
                getEmployees();
                $('#employeeModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editEmployee = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#employeeModal').modal('show');

            form.first_name = data.first_name;
            form.last_name = data.last_name;
            form.middle_name = data.middle_name;
            form.passport_series = data.passport_series;
            form.passport_number = data.passport_number;
            form.birthday_date = data.birthday_date;
            form.post_id = data.post?.id;

            employee.value = data;
        }

        const updateEmployee = async (data) =>
        {
            errors.value = '';

            await axios.post('employee/update/' + employee.value.id, data).then((response) => {
                getEmployees();
                $('#employeeModal').modal('hide');

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
                updateEmployee({...form});
            } else {
                storeEmployee({...form});
            }
        }

        onMounted(()=> {
            getEmployees();
            getPosts()
        });

        return{
            employees,
            editing,
            handleSubmit,
            addEmployee,
            form,
            errors,
            editEmployee,
            deleteEmployee,
            posts
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Сотрудники</h1>
        </div>
        <button type="button" class="btn btn-dark mb-1" @click="addEmployee">
            Добавить
        </button>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col">ФИО</th>
                    <th scope="col">Серия и номер паспорта</th>
                    <th scope="col">Должность</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-active" v-for="employee in employees" :key="employee.id">
                    <td>
                        {{employee.last_name + ' ' + employee.first_name + ' ' + employee.middle_name}}
                    </td>
                    <td>
                        {{employee.passport_series + ' ' + employee.passport_number}}
                    </td>
                    <td>
                        {{employee.post?.name}}
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteEmployee(employee.id)" class="btn btn-danger">Удалить</button>
                            <button @click="editEmployee(employee)" class="ms-1 btn btn-secondary">Изменить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>




    <div class="modal" id="employeeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">Изменение сотрудника</h5>
                    <h5 v-else class="modal-title">Добавление сотрудника</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Фамилия</label>
                            <input v-model="form.last_name" type="text" class="form-control" placeholder="фамилия"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Имя</label>
                            <input v-model="form.first_name" type="text" class="form-control" placeholder="имя"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Отчество</label>
                            <input v-model="form.middle_name" type="text" class="form-control" placeholder="отчество"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Серия паспорта</label>
                            <input v-model="form.passport_series" type="text" class="form-control" placeholder="серия паспорта"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Номер паспорта</label>
                            <input v-model="form.passport_number" type="text" class="form-control" placeholder="номер паспорта"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Дата рождения</label>
                            <input v-model="form.birthday_date" type="text" class="form-control" placeholder="дата рождения"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Выберите роль</label>
                            <select v-if="editing" class="form-select" v-model="form.post_id" aria-label="Default select example">
                                <option v-for="post in posts" :value=post.id
                                        :selected="post.id === form.post_id">{{post.name}}</option>
                            </select>
                            <select v-else class="form-select" v-model="form.post_id" aria-label="Default select example">
                                <option v-for="post in posts" :value=post.id>{{post.name}}</option>
                            </select>
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
