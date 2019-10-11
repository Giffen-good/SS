<style>
article footer{
  border-top: 1px solid #ddd;
}

.entry-meta>p{
  display:inline-block;
  margin: 10px 10px 0 0;
}

article img{
  max-width: 100%;
  height: auto;
}
</style>

<template>
    <div>
     <div class="fixedEl">
          <div class="little-container dtc vam" >
            <div
              class="psr bgpc bgsc dtc vam"
              :href="post.guid"
              v-for="(img, i) in post.acf.image_carousel"
              :key="i"
            >
              <a  :href="post.guid"><img class="img-obj post-img" :src="img.image.sizes.large" /></a>
            </div>
          </div>
      </div>
    </div>
</template>

<script>
    import WordpressService from '../services/wordpress';
    import Mixin from '../globals.js';

    export default {
      name: "post",
      props: ["post"]
        


    }
</script>
