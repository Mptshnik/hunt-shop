<script>
import {onMounted, reactive, ref} from "vue";
import axios from "axios";
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default {
    setup()
    {
        const editing = ref([]);
        const errors = ref([]);
        const promotion = ref([]);
        const rawPromotions = ref([]);
        const promotions = ref([]);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();
        const form = reactive({
            'name': '',
            'description': '',
            'start_date': '',
            'end_date': '',
        })

        const clearForm = () =>
        {
            form.name = '';
            form.description = '';
            form.start_date = '';
            form.end_date = '';
        }

        errors.value = null;

        const getPromotions = () =>
        {
            axios.get('promotion/all').then(res => {
                promotions.value = res.data
                rawPromotions.value = promotions.value;
            });
        }

        const getPromotion = (id) =>
        {
            axios.get('promotion/' + id).then(res => {
                promotion.value = res.data
            });
        }

        const deletePromotion = async (id) =>
        {
            await axios.post('promotion/delete/'+id);
            await getPromotions();
        }

        const addPromotion = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#promotionModal').modal('show');
        }

        const storePromotion = async (data) =>
        {
            errors.value = '';

            await axios.post('promotion/create', data).then((response) => {
                getPromotions();
                $('#promotionModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editPromotion = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#promotionModal').modal('show');

            form.name = data.name;
            form.description = data.description;
            form.start_date = data.start_date;
            form.end_date = data.end_date;

            promotion.value = data;
        }

        const updatePromotion = async (data) =>
        {
            errors.value = '';

            await axios.post('promotion/update/' + promotion.value.id, data).then((response) => {
                getPromotions();
                $('#promotionModal').modal('hide');

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
                updatePromotion({...form});
            } else {
                storePromotion({...form});
            }
        }


        const handleSearch = (e) => {
            onSearch(promotions, rawPromotions, e);
        }

        const sortTable  = (index) => {
            sortRecords(promotions, rawPromotions, index);
        }

        onMounted(getPromotions);

        return{
            form,
            errors,
            promotions,
            deletePromotion,
            editing,
            addPromotion,
            editPromotion,
            handleSubmit,
            handleSearch,
            sortTable
        }
    }
}
</script>

<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">??????????</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addPromotion" class="btn btn-dark">????????????????</button>
            </div>
            <div class="col-auto">
                <input type="text" class="form-control" placeholder="??????????" @input="handleSearch">
            </div>
        </div>
        <div>
            <table class="table table-dark table-hover table-sm">
                <thead>
                <tr>
                    <th scope="col" v-on:click="sortTable('name')">????????????????</th>
                    <th scope="col" v-on:click="sortTable('description')">????????????????</th>
                    <th scope="col" v-on:click="sortTable('start_date')">???????? ????????????????????</th>
                    <th class="text-end">????????????????</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-active" v-for="promotion in promotions" :key="promotion.id">
                    <td>
                        {{ promotion.name }}
                    </td>
                    <td>
                        <div class="content">
                            {{promotion.description}}
                        </div>
                    </td>
                    <td>
                        {{'?? '+ promotion.start_date + ' ???? ' + promotion.end_date}}
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deletePromotion(promotion.id)" class="btn btn-danger me-2">??????????????</button>
                            <button @click="editPromotion(promotion)" class="btn btn-secondary me-2">????????????????</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>



    <div class="modal" id="promotionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 v-if="editing" class="modal-title">?????????????????? ??????????</h5>
                    <h5 v-else class="modal-title">???????????????????? ??????????</h5>
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
                        <div class="form-group">
                            <label class="form-label">????????????????</label>
                            <textarea v-model="form.description" type="text" class="form-control" placeholder="????????????????"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">???????? ????????????</label>
                            <input v-model="form.start_date" type="text" class="form-control" placeholder="???????? ???????????? (????-????-????????)"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">???????? ??????????????????</label>
                            <input v-model="form.end_date" type="text" class="form-control" placeholder="???????? ?????????????????? (????-????-????????)"/>
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
