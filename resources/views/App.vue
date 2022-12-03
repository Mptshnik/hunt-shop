<template>
    <BasicLayout>
        <router-view></router-view>
    </BasicLayout>
</template>

<script>
import Test from "./components/Test.vue";
import BasicLayout from "./components/layouts/BasicLayout.vue";
export default {
    components: {BasicLayout, Test}
}
</script>

<style>

</style>
