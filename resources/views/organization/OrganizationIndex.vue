<script>
import {onMounted, reactive, ref} from "vue";
import axios from "axios";

export default {
    setup()
    {
        const editing = ref([]);
        const errors = ref(null);
        const organization = ref(null);
        const form = reactive({
            'name': '',
            'taxpayer_number': '',
            'physical_address': '',
            'juridical_address': ''
        });

        const clearForm = () =>
        {
            form.name = '';
            form.taxpayer_number = '';
            form.physical_address = '';
            form.juridical_address = '';
        }

        const getOrganization = () => {
            axios.get('seller/' + 1).then(res => {
                organization.value = res.data
            });
        }

        const addOrganization = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#organizationModal').modal('show');
        }

        const storeOrganization= async (data) =>
        {
            errors.value = '';

            await axios.post('seller/create', data).then((response) => {
                getOrganization();
                $('#organizationModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editOrganization = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#organizationModal').modal('show');

            form.name = data.name;
            form.taxpayer_number = data.taxpayer_number;
            form.juridical_address = data.juridical_address;
            form.physical_address = data.physical_address;

            organization.value = data;
        }

        const updateOrganization = async (data) =>
        {
            errors.value = '';

            await axios.post('seller/update/1', data).then((response) => {
                getOrganization();
                $('#organizationModal').modal('hide');

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
                updateOrganization({...form});
            } else {
                storeOrganization({...form});
            }
        }


        onMounted(getOrganization)

        return{
            editing,
            errors,
            organization,
            form,
            handleSubmit,
            addOrganization,
            editOrganization
        }
    }
}
</script>


<template>
    <div class="container">
        <div v-if="!organization">
            <div class="row">
                <div class="col text-center">
                    <button class="btn btn-outline-light" @click="addOrganization">???????????????? ???????????? ???? ??????????????????????</button>
                </div>
            </div>
        </div>
        <div v-else class="card text-white bg-dark mb-3" style="max-width: 25rem; width: 100%; margin: 0 auto">
            <div class="card-header">???????????? ??????????????????????</div>
            <div class="card-body">
                <h5 class="card-title">????????????????????????: {{organization.name}}</h5>
            </div>
            <ul class="list-group list-group-flush">
                <li class="bg-dark text-white list-group-item">??????: {{organization.taxpayer_number}}</li>
                <li class="bg-dark text-white list-group-item">?????????????????????? ??????????: {{organization.juridical_address}}</li>
                <li class="bg-dark text-white list-group-item">???????????????????? ??????????: {{organization.physical_address}}</li>
            </ul>
            <div class="card-body">
                <button type="button" class="card-link btn btn-outline-light" @click="editOrganization(organization)">????????????????</button>
            </div>
        </div>




        <div class="modal" id="organizationModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 v-if="editing" class="modal-title">?????????????????? ???????????? ??????????????????????</h5>
                        <h5 v-else class="modal-title">???????????????????? ???????????? ??????????????????????</h5>
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
                                <label class="form-label">??????</label>
                                <input v-model="form.taxpayer_number" type="text" class="form-control" placeholder="??????"/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">?????????????????????? ?????????? ??????????????????????</label>
                                <textarea v-model="form.juridical_address" type="text" class="form-control" placeholder="?????????????????????? ??????????"/>
                            </div>
                            <div class="form-group">
                                <label class="form-label">???????????????????? ?????????? ??????????????????????</label>
                                <textarea v-model="form.physical_address" type="text" class="form-control" placeholder="???????????????????? ??????????"/>
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
    </div>
</template>

<style scoped>

</style>
