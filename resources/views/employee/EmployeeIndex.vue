
<script>
import axios from 'axios';
import roles from "/resources/helper/roles.js";
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const exportUrl = axios.defaults.baseURL+'employee/export/all';
        const isLoading = ref(true);
        const postIdFilter = ref([]);
        const errors = ref(null);
        const employee = ref([]);
        const authorizedUser = ref([]);
        const posts = ref([]);
        const rawEmployees = ref([]);
        const employees = ref([]);
        const editing = ref(false);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

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

        const getAuthorizedUser = async () =>
        {
            await axios.get('/authorized-user').then(res => {
                authorizedUser.value = res.data
            });
        }

        const filterByPost = () =>
        {
            isLoading.value = true;

            axios.get('employee/filterByPost/' + postIdFilter.value).then(res => {
                rawEmployees.value = res.data;
                employees.value = rawEmployees.value;
                isLoading.value = false;
            }).catch((error) => {
                isLoading.value = false;
            });
        }

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
            isLoading.value = true;

            await axios.get('employee/all').then(res => {
                rawEmployees.value = res.data;
                employees.value = rawEmployees.value;
                isLoading.value = false;
            });
        }

        const addEmployee = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#employeeModal').modal('show');
        }

        const storeEmployee = async (employee) => {
            errors.value = '';

            await axios.post('employee/create', employee).then((response) => {
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

        const exportEmployees = async () =>
        {
            await axios.get('employee/export/all').then((res) => {

            });
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

        const handleSearch = (e) => {
            onSearch(employees, rawEmployees, e);
        }

        const sortTable  = (index) => {
            sortRecords(employees, rawEmployees, index);
        }

        onMounted(()=> {
            getEmployees();
            getPosts();
            getAuthorizedUser();
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
            posts,
            sortTable,
            handleSearch,
            roles,
            isLoading,
            authorizedUser,
            postIdFilter,
            exportUrl,
            filterByPost,
            getEmployees,
            exportEmployees
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">????????????????????</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addEmployee" class="btn btn-dark">????????????????</button>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="??????????" @input="handleSearch">
            </div>
            <div class="col-auto">
                <select class="form-select form-control-sm" v-model="postIdFilter" aria-label="Default select example">
                    <!--<option value="" selected disabled hidden>?????????????????????? ???? ??????????????????</option>-->
                    <option v-for="post in posts" :value=post.id>{{ post.name }}</option>
                </select>
            </div>
            <div class="col-auto">
                <button @click="filterByPost" class="btn btn-dark">??????????????????????</button>
            </div>
            <div class="col-auto">
                <button @click="getEmployees" class="btn btn-dark">???????????????? ????????????</button>
            </div>
            <div class="col-auto">
                <a :href="exportUrl" class="btn btn-outline-light">??????????????</a>
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('last_name')">??????</th>
                    <th scope="col" v-on:click="sortTable('passport_series')">?????????? ?? ?????????? ????????????????</th>
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
                            <button v-if="employee.id !== authorizedUser.employee?.id" @click="deleteEmployee(employee.id)" class="btn btn-danger">??????????????</button>
                            <button v-else class="btn btn-danger disabled">??????????????</button>
                            <button v-if="employee.id !== authorizedUser.employee?.id" @click="editEmployee(employee)" class="ms-1 btn btn-secondary">????????????????</button>
                            <button v-else class="ms-1 btn btn-secondary disabled">????????????????</button>
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
                    <h5 v-if="editing" class="modal-title">?????????????????? ????????????????????</h5>
                    <h5 v-else class="modal-title">???????????????????? ????????????????????</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label class="form-label">??????????????</label>
                                <input v-model="form.last_name" type="text" class="form-control" placeholder="??????????????"/>
                            </div>
                            <div class="form-group col">
                                <label class="form-label">??????</label>
                                <input v-model="form.first_name" type="text" class="form-control" placeholder="??????"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">????????????????</label>
                            <input v-model="form.middle_name" type="text" class="form-control" placeholder="????????????????"/>
                        </div>
                        <div class="row">
                            <div class="form-group col">
                                <label class="form-label">?????????? ????????????????</label>
                                <input v-model="form.passport_series" type="text" class="form-control" placeholder="?????????? ????????????????"/>
                            </div>
                            <div class="form-group col">
                                <label class="form-label">?????????? ????????????????</label>
                                <input v-model="form.passport_number" type="text" class="form-control" placeholder="?????????? ????????????????"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">???????? ????????????????</label>
                            <input v-model="form.birthday_date" type="text" class="form-control" placeholder="???????? ???????????????? (????-????-????????)"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">???????????????? ??????????????????</label>
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

</style>
