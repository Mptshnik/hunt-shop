import {ref} from 'vue';
import axios from "axios";
import router from "../router";

export default function useUsers()
{
    const errors = ref([]);
    const user = ref([]);
    const users = ref([]);

    errors.value = null;

    const getUsers = () =>
    {
        axios.get('user/all').then(res => {
            users.value = res.data
        });
    }

    const getUser = (id) =>
    {
        axios.get('post/' + id).then(res => {
            user.value = res.data
        });
    }

    const destroyUser = async (id) =>
    {
        await axios.post('user/delete/'+id);
    }

    const updateUser = async (id) =>
    {
        errors.value = '';
        try {
            await axios.post('user/update/'+id, user.value);
            await router.push('/users');
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

    const storeUser = async (data) =>
    {
        errors.value = '';

        try
        {
            await axios.post('user/create', data);
           // await router.push('/users');
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
        user,
        getUser,
        errors,
        storeUser,
        users,
        getUsers,
        destroyUser,
        updateUser
    }
}
