import {ref} from 'vue';
import axios from "axios";
import data from "bootstrap/js/src/dom/data";
import router from "../router";

export default function usePosts()
{
    const errors = ref([]);
    const posts = ref([]);
    let isLoading = true;

    const getPosts = () =>
    {
         axios.get('post/all').then(res => {
            posts.value = res.data;
            setTimeout(()=> {
                isLoading = false;
            }, 5);
        });
    }

    const destroyPost = async (id) =>
    {
        await axios.post('post/delete/'+id);
    }

    const updatePost = async (id, data) =>
    {
        await axios.post('post/update/'+id, data);
    }

    const storePost = async (data) =>
    {
        await axios.post('post/create', data);
        await router.push('/posts');
    }

    return{
        isLoading,
        errors,
        storePost,
        posts,
        getPosts,
        destroyPost,
        updatePost
    }
}
