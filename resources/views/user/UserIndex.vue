<script>
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import roles from "/resources/helper/roles.js";
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default
{
    setup()
    {
        const editing = ref([]);
        const errors = ref([]);
        const user = ref([]);
        const rawUsers = ref([]);
        const users = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const form = reactive({
            'login': '',
            'password': '',
            'role_id': '',
            'agreement': '1'
        })

        const clearForm = () =>
        {
            form.description = '';
            form.name = '';
            form.role_id = '';
            form.agreement = '1';
        }

        errors.value = null;

        const getUsers = () =>
        {
            axios.get('user/all').then(res => {
                users.value = res.data;
                rawUsers.value = users.value;
            });
        }

        const getUser = (id) =>
        {
            axios.get('user/' + id).then(res => {
                user.value = res.data
            });
        }

        const deleteUser = async (id) =>
        {
            await axios.post('user/delete/'+id);
            await getUsers();
        }

        const addUser = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#userModal').modal('show');
        }

        const storeUser = async (data) =>
        {
            errors.value = '';

            await axios.post('user/create', data).then((response) => {
                getUsers();
                $('#userModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editUser = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#userModal').modal('show');

            form.login = data.login;
            form.password = data.password;
            form.role_id = data.role.id

            user.value = data;
        }

        const updateUser = async (data) =>
        {
            errors.value = '';

            await axios.post('user/update/' + user.value.id, data).then((response) => {
                getUsers();
                $('#userModal').modal('hide');

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
                updateUser({...form});
            } else {
                storeUser({...form});
            }
        }

        const handleSearch = (e) => {
            onSearch(users, rawUsers, e);
        }

        const sortTable  = (index) => {
            sortRecords(users, rawUsers, index);
        }

        onMounted(getUsers);

        return{
            form,
            errors,
            users,
            deleteUser,
            editing,
            addUser,
            editUser,
            handleSubmit,
            roles,
            handleSearch,
            sortTable
        }
    }
}
</script>


<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">Пользователи</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addUser" class="btn btn-dark">Добавить</button>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="Поиск" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('login')">Логин</th>
                    <th scope="col" v-on:click="sortTable('created_at')">Дата регистрации</th>
                    <th class="text-end">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-active" v-for="user in users" :key="user.id">
                    <td>
                        <div class="content">
                            {{ user.login }}
                        </div>
                    </td>
                    <td>
                        <div>
                            {{ user.created_at }}
                        </div>
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteUser(user.id)" class="btn btn-danger me-2">Удалить</button>
                            <button @click="editUser(user)" class="btn btn-secondary me-2">Изменить</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>





    <div class="modal" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">Изменение пользователя</h5>
                    <h5 v-else class="modal-title">Добавление пользователя</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">Логин</label>
                            <input v-model="form.login" type="text" class="form-control" placeholder="логин"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Пароль</label>
                            <input v-model="form.password" type="text" class="form-control" placeholder="пароль"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Выберите роль</label>
                            <select class="form-select" v-model="form.role_id" aria-label="Default select example">
                                <option :value=roles.PURCHASING_MANAGER>Менеджер по закупкам</option>
                                <option :value=roles.SALES_MANAGER>Менеджер по продажам</option>
                                <option :value=roles.ACCOUNTANT>Бухгалтер</option>
                                <option :value=roles.MARKETING_MANAGER>Менеджер по маркетингу</option>
                                <option :value=roles.HR>Менеджер по персоналу</option>
                                <option :value=roles.ADMIN>Администратор</option>
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
.content{
    word-wrap:break-word; /*old browsers*/
    overflow-wrap:break-word;
    word-break: break-all;
    max-width: 400px;
}
</style>
