<template>
    <div class="container" style="width: 500px">
        <Pie v-if="loaded" :data="chartData"/>
    </div>
</template>

<script>
import { Chart as ChartJS, ArcElement, Tooltip, Legend } from 'chart.js'
import { Pie, Bar } from 'vue-chartjs'
import axios from "axios";

ChartJS.register(ArcElement, Tooltip, Legend)
ChartJS.defaults.color = '#ffffff';

export default {
    name: 'EmployeeBarChart',
    components: {
        Pie,
        Bar
    },
    mounted() {
        this.getPosts();
    },
    methods:{
        async getPosts()
        {
            await axios.get('post/all').then(res => {
                this.loaded = true;
                res.data.forEach((element) => {
                    this.chartData.labels.unshift(element['name']);
                    this.chartData.datasets[0]['data'].unshift(element['employees_count']);
                })
            });
        }
    },
    data() {
        return{
            loaded: false,
            chartData: {
                labels: [],
                datasets: [{
                    backgroundColor: ['#41B883', '#E46651', '#00D8FF', '#DD1B16'],
                    data: []
                }]
            },
        }
    }
}
</script>


<style scoped>

</style>
