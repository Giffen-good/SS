<template>
    <div class="dib">
        <span v-for="(cat, key ) in post.categories_list"><a v-html="decode(cat.cat_name)" class="fs1" :href="cat.link"></a>  
            <span class="fs1" v-if="(key != post.categories_list.length-1)"> & </span>
        </span>
    </div>
</template>
<script>
export default {
    name:"catLinks",
    props: ["post"],
    methods: {
        decode: function(str) {
               var txt = document.createElement("textarea");
                txt.innerHTML = str;
                return txt.value;
        }
    }
}
</script>
<style lang="scss" >
    a:hover,a {
        text-decoration: none;
    }
    p.big-toe {
        line-height: 1.2rem;
        text-align: left;
        padding: 0.82rem 0 0.82rem 0;
    }

</style>