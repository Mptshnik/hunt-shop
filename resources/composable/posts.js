import {ref} from 'vue';
import axios from "axios";
import data from "bootstrap/js/src/dom/data";
import router from "../router";

export default function usePosts()
{
    const errors = ref([]);
    const post = ref([]);
    const posts = ref([]);
    let isLoading = true;

    errors.value = null;

    const getPosts = () =>
    {
         axios.get('post/all').then(res => {
            posts.value = res.data;
            setTimeout(()=> {
                isLoading = false;
            }, 5);
        });
    }

    const getPost = (id) =>
    {
        axios.get('post/' + id).then(res => {
            post.value = res.data;
            setTimeout(()=> {
                isLoading = false;
            }, 5);
        });
    }

    const destroyPost = async (id) =>
    {
        await axios.post('post/delete/'+id);
    }

    const updatePost = async (id) =>
    {
        errors.value = '';
        try {
            await axios.post('post/update/'+id, post.value);
            await router.push('/posts');
        }
        catch (e)
        {
            if(e.response.status === 422){
                for (const key in e.response.data.errors)
                {
                    errors.value += e.response.data.errors[key][0] + '. ';
                }
            }
        }
    }

    const storePost = async (data) =>
    {
        errors.value = '';

        try {
            await axios.post('post/create', data);
            await router.push('/posts');
        }
        catch (e)
        {
            if(e.response.status === 422){
                for (const key in e.response.data.errors)
                {
                    errors.value += e.response.data.errors[key][0] + '. ';
                }
            }
        }

    }

    return{
        isLoading,
        post,
        getPost,
        errors,
        storePost,
        posts,
        getPosts,
        destroyPost,
        updatePost
    }
}
