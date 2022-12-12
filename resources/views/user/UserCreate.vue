<template>
    <div class="container" style="width: 40%; margin: 0 auto">
        <div v-if="errors" class="bg-danger text-white" style="border-radius: 5px; height: 30px">
            <div class="ms-1">
                {{errors}}
            </div>
        </div>
        <form @submit.prevent="saveUser">
            <div class="mb-3">
                <label class="form-label text-white">Логин</label>
                <input v-model="form.login" type="text" class="form-control" placeholder="логин">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Пароль</label>
                <input v-model="form.password" type="text" class="form-control" placeholder="пароль">
            </div>
            <div class="mb-3">
                <label class="form-label text-white">Выберите роль</label>
                <select class="form-select" v-model="form.role_id" aria-label="Default select example">
                    <option :value=roles.PURCHASING_MANAGER>Менеджер по закупкам</option>
                    <option :value=roles.SALES_MANAGER>Менеджер по продажам</option>
                    <option :value=roles.ACCOUNTANT>Бухгалтер</option>
                    <option :value=roles.MARKETING_MANAGER>Менеджер по маркетингу</option>
                    <option :value=roles.HR>Менеджер по персоналу</option>
                    <option :value=roles.ADMIN>Администратор</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary form-control">Создать</button>
        </form>
    </div>


</template>


<script>
import {reactive} from 'vue';
import roles from "/resources/helper/roles.js";
import useUsers from "../../composable/users";

export default {
    name: "UserCreate",
    setup()
    {
        const {storeUser, errors} = useUsers();

        const form = reactive({
            'login': '',
            'password': '',
            'role_id': '',
            'agreement': '1'
        })

        const saveUser = async () =>
        {
            await storeUser({...form})
        }

        return{
            roles,
            saveUser,
            errors,
            form
        }
    }
}
</script>

<style scoped>

</style>
