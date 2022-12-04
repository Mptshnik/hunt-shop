<script>
import usePosts from "../../composable/posts";
import {onMounted} from "vue";
import axios from "axios";

export default
{

    setup()
    {
        const {isLoading, posts, getPosts, destroyPost} = usePosts()

        const deletePost = async (id) =>
        {

            await destroyPost(id);
            await getPosts();
        }

        onMounted(getPosts);

        return{
            posts,
            isLoading,
            deletePost
        }
    }
}
</script>


<template>
    <div class="container">
        <router-link to="/posts/create" class="btn btn-dark mb-1">Добавить</router-link>
        <div>
            <table class="table table-dark table-hover">
                <thead>
                <tr>
                    <th scope="col">Название</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr class="table-active" v-for="post in posts" :key="post.id">
                    <td>{{ post.name }}</td>
                    <td>
                        <div class="content">
                            {{ post.description }}
                        </div>
                    </td>
                    <td>
                        <button @click="deletePost(post.id)" class="btn btn-danger">Удалить</button>
                        <button class="ms-1 btn btn-secondary">Изменить</button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>


<style scoped>
.content{
    word-wrap:break-word; /*old browsers*/
    overflow-wrap:break-word;
    word-break: break-all;
    max-width: 700px;
}

table{
    width:100%; /*must be set (to any value)*/
}

.overflow-wrap-hack{
    max-width: 700px;
}
</style>
