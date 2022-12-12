<template>

</template>

<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import { Form, Field, useResetForm } from 'vee-validate';
import router from "../../router";

const errors = ref([]);
const posts = ref([]);
const editing = ref(false);
const formValues = ref();
const form = ref(null);

const getPosts = () =>
{
    axios.get('post/all').then(res => {
        posts.value = res.data;
    });
}

const storePost = (data, {resetForm}) =>
{
    errors.value = '';

    axios.post('post/create', data).then((response) => {
        //posts.value.unshift(response.data.user);
        $('#userFormModal').modal('hide');
        resetForm();
    }).catch((error) => {
        if (error.response.data.errors) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '. ';
            }
        }
    });
}

const updatePost = (data, {resetForm}) =>
{
    errors.value = '';

    axios.post('post/update', data).then((response) => {
        //posts.value.unshift(response.data.user);
        $('#userFormModal').modal('hide');
        resetForm();
    }).catch((error) => {
        if (error.response.data.errors) {
            for (const key in e.response.data.errors) {
                errors.value += e.response.data.errors[key][0] + '. ';
            }
        }
    });
}
export default {
    name: "PostModal",
}
</script>

<style scoped>

</style>
