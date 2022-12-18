<template>
    <div class="container">
        <h5 class="text-white text-center">
            Количество сотрудников по должностям
        </h5>
    </div>
    <EmployeeBarChart></EmployeeBarChart>
</template>

<script>
import EmployeeBarChart from "../components/chart/EmployeeBarChart.vue";
export default {
name: "EmployeeStatistics",
    components: {EmployeeBarChart}
}
</script>

<style scoped>

</style>
