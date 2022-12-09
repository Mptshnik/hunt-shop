<template>
    <div class="container" style="width: 40%; margin: 0 auto">
        <div class="text-danger" v-if="errors">
            {{errors}}
        </div>
        <form @submit.prevent="editPost">
            <div class="mb-3">
                <label class="form-label text-white">Название должности</label>
                <input v-model="post.name" type="text" class="form-control" placeholder="должность">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Описание должности</label>
                <input v-model="post.description" type="text" class="form-control" placeholder="описание">
            </div>
            <button type="submit" class="btn btn-primary form-control">Изменить</button>
        </form>
    </div>
</template>

<script>
import {reactive, onMounted} from 'vue';
import usePosts from "../../composable/posts";
import axios from "axios";
import router from "../../router";

export default {
    name: "PostEdit",
    props:{
        id:{
            required: true,
            type: String
        }
    },
    setup(props)
    {
        const {updatePost, errors, getPost, post} = usePosts();

        const form = reactive({
            'name': '',
            'description': ''
        })

        onMounted(() => getPost(props.id))

        const editPost = async () =>
        {
            await updatePost(props.id);
        }

        return{
            post,
            editPost,
            errors,
            form
        }
    },

}
</script>

<style scoped>

</style>
