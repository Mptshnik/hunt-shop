<script>
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import roles from "../../helper/roles";
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const isLoading = ref(true);
        const editing = ref([]);
        const errors = ref([]);
        const category = ref([]);
        const categories = ref([]);
        const rawCategories = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();
        const form = reactive({
            'name': '',
        })

        const clearForm = () =>
        {
            form.name = '';
        }

        errors.value = null;

        const getCategories = () =>
        {
            axios.get('item-category/all').then(res => {
                rawCategories.value = res.data;
                categories.value = rawCategories.value;
                isLoading.value = false;
            });
        }

        const getCategory = (id) =>
        {
            axios.get('item-category/' + id).then(res => {
                category.value = res.data
            });
        }

        const deleteCategory = async (id) =>
        {
            await axios.post('item-category/delete/'+id);
            await getCategories();
        }

        const addCategory = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#categoryModal').modal('show');
        }

        const storeCategory = async (data) =>
        {
            errors.value = '';

            await axios.post('item-category/create', data).then((response) => {
                getCategories();
                $('#categoryModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editCategory = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#categoryModal').modal('show');

            form.name = data.name;

            category.value = data;
        }

        const updateCategory = async (data) =>
        {
            errors.value = '';

            await axios.post('item-category/update/' + category.value.id, data).then((response) => {
                getCategories();
                $('#categoryModal').modal('hide');

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
                updateCategory({...form});
            } else {
                storeCategory({...form});
            }
        }

        const handleSearch = (e) => {
            onSearch(categories, rawCategories, e);
        }

        const sortTable  = (index) => {
            sortRecords(categories, rawCategories, index);
        }

        onMounted(getCategories);

        return{
            form,
            errors,
            categories,
            deleteCategory,
            editing,
            addCategory,
            editCategory,
            handleSubmit,
            roles,
            sortTable,
            handleSearch,
            isLoading
        }
    }
}
</script>


<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">?????????????????? ??????????????</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addCategory" class="btn btn-dark">????????????????</button>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="??????????" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('name')">???????????????? ??????????????????</th>
                    <th class="text-end">????????????????</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    ????????????????...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="category in categories" :key="category.id">
                    <td>
                        <div class="content">
                            {{ category.name }}
                        </div>
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deleteCategory(category.id)" class="btn btn-danger me-2">??????????????</button>
                            <button @click="editCategory(category)" class="btn btn-secondary me-2">????????????????</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>





    <div class="modal" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">?????????????????? ??????????????????</h5>
                    <h5 v-else class="modal-title">???????????????????? ??????????????????</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form @submit.prevent="handleSubmit">
                    <div class="modal-body">
                        <div v-if="errors" class="text-danger">
                            {{errors}}
                        </div>
                        <div class="form-group">
                            <label class="form-label">????????????????????????</label>
                            <input v-model="form.name" type="text" class="form-control" placeholder="????????????????????????"/>
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
    max-width: 200px;
}

</style>
