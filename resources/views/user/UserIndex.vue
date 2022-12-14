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
        const isLoading = ref(true);
        const editing = ref([]);
        const errors = ref([]);
        const rawEmployees = ref([]);
        const employees = ref([]);
        const user = ref([]);
        const rawUsers = ref([]);
        const authorizedUser = ref(null);
        const users = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const form = reactive({
            'login': '',
            'password': '',
            'role_id': '',
            'employee_id': '',
            'agreement': '1'
        })

        const clearForm = () =>
        {
            form.login = '';
            form.password = '';
            form.role_id = '';
            form.employee_id = '';
            form.agreement = '1';
        }

        errors.value = null;

        const getAuthorizedUser = async () =>
        {
            await axios.get('/authorized-user').then(res => {
                authorizedUser.value = res.data
            });
        }

        const getEmployees = async () =>
        {
            await axios.get('employee/all').then(res => {
                rawEmployees.value = res.data;
                employees.value = rawEmployees.value;
            });
        }

        const getUsers = () =>
        {
            axios.get('user/all').then(res => {
                users.value = res.data;
                rawUsers.value = users.value;
                isLoading.value = false;
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
            form.role_id = data.role.id;
            form.employee_id = data.employee.id;

            user.value = data;
        }

        const updateUser = async (data) =>
        {
            errors.value = '';

            console.log(data.login)

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

        onMounted(() => {
            getUsers();
            getEmployees();
            getAuthorizedUser();
        });

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
            sortTable,
            isLoading,
            employees,
            authorizedUser
        }
    }
}
</script>


<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">????????????????????????</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addUser" class="btn btn-dark">????????????????</button>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="??????????" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('login')">??????????</th>
                    <th scope="col" v-on:click="sortTable('created_at')">???????? ??????????????????????</th>
                    <th scope="col">??????????????????</th>
                    <th class="text-end">????????????????</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white">
                    ????????????????...
                </h1>
                </tbody>
                <tbody v-else>
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
                        <div v-if="user.employee">
                            {{
                                user.employee.last_name + ' '
                                + user.employee.first_name + ' '
                                + user.employee.middle_name
                            }}
                        </div>
                    </td>
                    <td>
                        <div class="float-end">
                            <button v-if="user.id !== authorizedUser.id" @click="deleteUser(user.id)" class="btn btn-danger me-2">??????????????</button>
                            <button v-else class="btn btn-danger me-2 disabled">??????????????</button>
                            <button v-if="user.id !== authorizedUser.id" @click="editUser(user)" class="btn btn-secondary me-2">????????????????</button>
                            <button v-else class="btn btn-secondary me-2 disabled">????????????????</button>
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
                    <h5 v-if="editing" class="modal-title">?????????????????? ????????????????????????</h5>
                    <h5 v-else class="modal-title">???????????????????? ????????????????????????</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">??????????</label>
                            <input v-model="form.login" type="text" class="form-control" placeholder="??????????"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">????????????</label>
                            <input v-model="form.password" type="text" class="form-control" placeholder="????????????"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">???????????????? ????????</label>
                            <select class="form-select" v-model="form.role_id" aria-label="Default select example">
                                <option :value=roles.PURCHASING_MANAGER>???????????????? ???? ????????????????</option>
                                <option :value=roles.SALES_MANAGER>???????????????? ???? ????????????????</option>
                                <option :value=roles.ACCOUNTANT>??????????????????</option>
                                <option :value=roles.MARKETING_MANAGER>???????????????? ???? ????????????????????</option>
                                <option :value=roles.HR>???????????????? ???? ??????????????????</option>
                                <option :value=roles.ADMIN>??????????????????????????</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">??????????????????</label>
                            <select v-if="editing" class="form-select" v-model="form.employee_id"
                                    aria-label="Default select example">
                                <option v-for="employee in employees" :value=employee.id
                                        :selected="employee.id === form.employee_id">
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-auto">
                                            {{
                                                employee.last_name + ' '
                                                + employee.first_name + ' '
                                                + employee.middle_name
                                            }}
                                        </div>
                                        <div v-if="employee.post" class="col-auto">
                                           {{' (' + employee.post.name + ')'}}
                                        </div>
                                    </div>

                                </option>
                            </select>
                            <select v-else class="form-select" v-model="form.employee_id"
                                    aria-label="Default select example">
                                <option v-for="employee in employees" :value=employee.id>
                                    <div class="row g-3 align-items-center mb-3">
                                        <div class="col-auto">
                                            {{
                                                employee.last_name + ' '
                                                + employee.first_name + ' '
                                                + employee.middle_name
                                            }}
                                        </div>
                                        <div v-if="employee.post" class="col-auto">
                                            {{' (' + employee.post.name + ')'}}
                                        </div>
                                    </div>
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">????????????</button>
                        <button v-if="editing" type="submit" class="btn btn-outline-dark">??????????????????</button>
                        <button v-else type="submit" class="btn btn-dark">????????????????</button>
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
