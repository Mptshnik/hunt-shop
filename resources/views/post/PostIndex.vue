<script>
import axios from 'axios';
import { ref, onMounted, reactive } from 'vue';
import useSorting from "../../helper/sorting";
import useSearching from "../../helper/searching";

export default
{
    setup()
    {
        const isLoading = ref(true);
        const errors = ref(null);
        const post = ref([]);
        const posts = ref([]);
        const rawPosts = ref([]);
        const editing = ref(false);
        const {sortRecords} = useSorting();
        const {onSearch} = useSearching();

        const form = reactive({
            'name': '',
            'description': ''
        })

        const clearForm = () =>
        {
            form.description = '';
            form.name = '';
        }

        const getPost = (id) =>
        {
            axios.get('post/' + id).then(res => {
                post.value = res.data;
            });
        }

        const deletePost = async (id) =>
        {
            await axios.post('post/delete/'+id);
            await getPosts();
        }

        const getPosts = async () =>
        {
            await axios.get('post/all').then(res => {
                rawPosts.value = res.data;
                posts.value = rawPosts.value;
                isLoading.value = false;
            });
        }

        const addPost = () =>{
            clearForm();
            errors.value = '';
            editing.value = false;
            $('#postModal').modal('show');
        }

        const storePost = async (data) =>
        {
            errors.value = '';

            await axios.post('post/create', data).then((response) => {
                //getPosts();
                posts.value.push(response.data.post)
                $('#postModal').modal('hide');

                clearForm();
            }).catch((error) => {
                if (error.response.data.errors) {
                    for (const key in error.response.data.errors) {
                        errors.value += error.response.data.errors[key][0] + '. ';
                    }
                }
            });
        }

        const editPost = (data) =>
        {
            errors.value = '';
            editing.value = true;
            $('#postModal').modal('show');

            form.description = data.description;
            form.name = data.name;

            post.value = data;
        }

        const updatePost = async (data) =>
        {
            errors.value = '';

            await axios.post('post/update/' + post.value.id, data).then((response) => {
                getPosts();
                $('#postModal').modal('hide');

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
                updatePost({...form});
            } else {
                storePost({...form});
            }
        }

        const handleSearch = (e) => {
            onSearch(posts, rawPosts, e);
        }

        const sortTable  = (index) => {
            sortRecords(posts, rawPosts, index);
        }

        onMounted(()=> {
            getPosts();
        });

        return{
            posts,
            deletePost,
            editing,
            handleSubmit,
            addPost,
            form,
            errors,
            editPost,
            handleSearch,
            sortTable,
            isLoading
        }
    }
}
</script>


<template>
    <div class="container">
        <div>
            <h1 class="card-title text-white mb-3">??????????????????</h1>
        </div>
        <div class="row g-3 align-items-center mb-3">
            <div class="col-auto">
                <button @click="addPost" class="btn btn-dark">????????????????</button>
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
                    <th class="text-end">????????????????</th>
                </tr>
                </thead>
                <tbody v-if="isLoading">
                <h1 class="text-white mt-2">
                    ????????????????...
                </h1>
                </tbody>
                <tbody v-else>
                <tr class="table-active" v-for="post in posts" :key="post.id">
                    <td>{{ post.name }}</td>
                    <td>
                        <div class="content">
                            {{ post.description }}
                        </div>
                    </td>
                    <td>
                        <div class="float-end">
                            <button @click="deletePost(post.id)" class="btn btn-danger">??????????????</button>
                            <button @click="editPost(post)" class="ms-1 btn btn-secondary">????????????????</button>
                        </div>
                    </td>
                </tr>
                </tbody>

            </table>
        </div>
    </div>




    <div class="modal" id="postModal" tabindex="-1">
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
                            <label class="form-label">???????????????? ??????????????????</label>
                            <input v-model="form.name" type="text" class="form-control" placeholder="??????????????????"/>
                        </div>
                        <div class="form-group">
                            <label class="form-label">???????????????? ??????????????????</label>
                            <textarea v-model="form.description" type="text" class="form-control" placeholder="????????????????"/>
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
    max-width: 700px;
}

table{
    width:100%; /*must be set (to any value)*/
}

.overflow-wrap-hack{
    max-width: 700px;
}
</style>
