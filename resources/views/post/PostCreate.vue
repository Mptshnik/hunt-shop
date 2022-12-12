<template>
    <div class="container" style="width: 40%; margin: 0 auto">
        <div v-if="errors" class="text-danger">
            {{errors}}
        </div>
        <form @submit.prevent="savePost">
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


export default {
    name: "PostCreate",
    setup()
    {
        const {storePost, errors} = usePosts();

        const form = reactive({
            'name': '',
            'description': ''
        })

        const savePost = async () =>
        {
            await storePost({...form})
        }

        return{
            savePost,
            errors,
            form
        }
    }
}
</script>

<style scoped>

</style>
