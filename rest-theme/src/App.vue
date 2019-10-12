
<template>
    <div id="app">
        
        <router-view :key="this.$route.fullPath"></router-view>
    </div>
</template>
<script>
import WordpressService from './services/wordpress';
export default {
    name: "app",
    methods: {
         onChangePage(to) {
             
         }
    }
}
</script>
<style lang="scss">


</style>