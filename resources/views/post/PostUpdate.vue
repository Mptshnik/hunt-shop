<template>
    <div class="container" style="width: 40%; margin: 0 auto">
        <form @submit.prevent="editPost">
            <div class="mb-3">
                <label class="form-label text-white">Название должности</label>
                <input v-model="form.name" type="text" class="form-control" placeholder="должность">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Описание должности</label>
                <input v-model="form.description" type="text" class="form-control" placeholder="описание">
            </div>
            <button type="submit" class="btn btn-primary form-control">Создать</button>
        </form>
    </div>
</template>

<script>
import {reactive} from 'vue';
import usePosts from "../../composable/posts";
import axios from "axios";
axios.defaults.headers.common['Authorization'] = 'Bearer 2|EJ5vH08DvVrAJyacg3U1ebkB7G2ZKn3sQwSuBn7I'
export default {
    name: "PostCreate",
    setup()
    {
        const {updatePost, errors} = usePosts();

        const form = reactive({
            'name': '',
            'description': ''
        })

        const editPost = async (id) =>
        {
            await updatePost(id, {...form})
        }

        return{
            editPost,
            errors,
            form
        }
    }
}
</script>

<style scoped>

</style>
