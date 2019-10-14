
<template>
    <div id="app">
        
        <router-view :key="this.$route.fullPath"></router-view>
    </div>
</template>
<script>
import WordpressService from './services/wordpress';
export default {
    name: "app",
    
     mounted() {
            console.log(['ee',this.$route.path == '/list/' ]);
            if (this.$route.path == '/list/' ) {
                console.log(document.getElementById('menu-item-47'))
                document.getElementById('menu-item-47').classList.add('dn');
                document.getElementById('menu-item-106').classList.remove('dn');
            } else {

                document.getElementById('menu-item-47').classList.remove('dn');
                document.getElementById('menu-item-106').classList.add('dn');
            }
        }

    
}
</script>
<style lang="scss">


</style>