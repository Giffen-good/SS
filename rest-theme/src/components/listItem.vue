<template>
        <a :href="post.link" sm="xw" class="x  Dcolor toKids">
            <div class="x1050 pr1">
                <p v-html="decode(post.title.rendered)" class="big-toe fs4"></p>
            </div>
            <div sm="x10100 xw fsS reverso" class="x1050 x xac tal ttu fsS">
                <div sm="x10100" class="x1050 pr1"><catLinks :post="post" /></div>
                <div class="x1050 x xac fs1"><span>{{ getRevisedDate(post.date) }}</span></div>
            </div>
        </a>
</template>
<script>
    import WordpressService from '../services/wordpress';
    import catLinks from './catLinks.vue'
export default {
    name:"listItem",
    props:["post"],
    components: {
        catLinks
    },
    methods: {
        getRevisedDate: function(date) {
            return date.split('-')[0];
        },
        decode: function(str) {
            function htmlEntities(str) {
                return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            }   
               var txt = document.createElement("textarea");
                txt.innerHTML = str;
                return txt.value;
        }
    }    
}
</script>
<style lang="scss" >
     a,a:hover {
        text-decoration: none;
    }
 
</style>