<template>
    <div class="dib">
        <span v-for="(cat, key ) in post.categories_list"><a class="fs1" :href="cat.link">{{ decode(cat.cat_name)  }}</a>  
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
        padding: 0.7rem 0 0.7rem 0;
    }

</style>