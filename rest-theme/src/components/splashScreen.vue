<template>
  <div :class="{ 'z4':true, 'Dcolor-r': true, loader: true, fadeout: !isLoading }">
    <div class="psa c10 ttu cxy">
      <h2>{{ posts[2] }}</h2>
      <h6 class="fs2 ">{{ posts[3] }}</h6>
    </div>
  </div>
</template>

<script>
    import WordpressService from '../services/wordpress';

export default {
  name: "splashScreen",
  props: ["isLoading"],
  data() {
    return {
      posts:[]
    }
  },
  mounted() {
    this.getPosts();
  },
  methods: {
     getPosts: function() {
                const wpPromisedResult = WordpressService.getColors();
                wpPromisedResult.then(result => {
                      console.log("PostSlug Found!", result.posts, result.totalPages);
                      this.loading = false;

                      if( result.posts.length == 0){
                          this.error = true; //alternate content control too
                          console.log("PostSlug Found, no data");
                      }else{
                          this.posts = result.posts;
                      }

                  })
                  .catch(err => {
                    this.error = true;

                    console.log("PostSlug Error!", wpPromisedResult);
                  });
            }
  }
};
</script>
<style lang="scss" >
  .loader {
    position:relative;
    width:100vw;
    height:100vh;
    transition:transform 1s;
    h2 {
      font-size:4rem;
      margin-bottom: 0;
      line-height: 0.7;

    }
  }
  .cxy {
    top: 50%;
    left:50%;
    transform: translate(-50%,-50%)
  }
  .loader.fadeout {
    transform:translateY(-200vh)
  }
</style>