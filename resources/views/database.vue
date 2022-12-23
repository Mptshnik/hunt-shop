<template>
    <div class="container px-4 py-5" id="hanging-icons">
        <h2 class="pb-2 border-bottom text-white">Управление базой данный</h2>
        <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
            <div class="col d-flex align-items-start">
                <div>
                    <h5 class="text-white">Выгрузка данных</h5>
                    <p></p>
                    <a :href="exportUrl" class="btn btn-outline-light">
                        Выгрузить данные
                    </a>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div>
                    <h5 class="text-white">Создание резервной копии</h5>
                    <p></p>
                    <a href="#" class="btn btn-outline-light">
                        Резервная копия
                    </a>
                </div>
            </div>
            <div class="col d-flex align-items-start">
                <div>
                    <h5 class="text-white">Восстановление данных</h5>
                    <a href="#" class="btn btn-outline-light">
                        Восстановить данные
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "database",
    setup()
    {
        const exportUrl = axios.defaults.baseURL+'database/export/all';

        return{
            exportUrl
        }
    }
}
</script>

<style scoped>

</style>
