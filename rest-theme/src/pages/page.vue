

<template>
    <main class="content">
        
            <div v-if="(isList)" class="hpad Dcolor">
                
                <listItem class="pl1 pr1 dotted-line" v-for="post in posts" :key="post.ID" :post="post"></listItem>
                <div class="tal res sm pl1"><span>{{ posts.length }} Results</span></div>
            
            </div>
            <div v-else="(!isList)">

                <div v-if="(isPost)">
                <div v-for="post in posts" :key="post.ID"><postContent :post="post" v-if="compareBranch(post.link)"></postContent></div>
                    <listItem class="pl1 pr1 dotted-line" v-for="post in posts" :key="post.ID" :post="post"></listItem>
                </div>
                <div v-else="(!isPost)">
                        <div class="page" v-if="(error)">
                            <h2>Not Found</h2>
                        </div>

                        <article class="page Dcolor hpad " v-else="(!error)">

                            <div class="entry-content" v-html="page.content.rendered">
                            </div>
                        </article>
                        <div id="featured-image" v-html="page.featured_image.html" class="" sm="dn">
                        </div>
                
                </div>
            </div>
      
    </main>

</template>

<script>
    import WordpressService from '../services/wordpress';
    import listItem from '../components/listItem.vue'
    import postContent from '../components/postContent.vue'

    export default {
        props: ['post_slug'],

        data() {
            return {
                loading: true,
                error: false,
                page: {
                    id: 0,
                    slug: '',
                    title: { rendered: '' },
                    content: { rendered: '' }
                },
                isList:false,
                isPost:false,
                posts: []
            }
        },
        components: {
            listItem,
            postContent
        },
        
        mounted() {
            console.log('ticked');
            this.checkList();
            this.checkPost();
            this.getPosts();
            this.getPage();

        },

        methods: {
             decode: function(str) {
               var txt = document.createElement("textarea");
                txt.innerHTML = str;
                return txt.value;
            },
            delay: function(ms) {
                const startPoint = new Date().getTime();
                while (new Date().getTime() - startPoint <= ms) {
                    /* wait */
                }
            },
            checkList: function() {
                if (this.$route.path == '/list/') {
                    this.isList = true;
                }
            },
            checkPost: function() {
                if (this.$attrs.post_type == 'post') {
                    this.isPost = true
                }
            },
            compareBranch: function(link) {
                let linked = link.split('/');
                if ('/' + linked[linked.length - 2] + '/' == this.$route.path) return true;
            },
            getPage: function() {

                const wpPromisedResult = WordpressService.getPageBySlug( this.post_slug );
                wpPromisedResult.then(result => {
                      console.log("PageSlug Found!", result.posts, result.totalPages);
                      this.loading = false;

                      if( result.posts.length == 0){
                          this.error = true; //alternate content control too
                          console.log("PageSlug Found, no data");
                      }else{
                          this.page = result.posts[0];
                      }

                  })
                  .catch(err => {
                    this.error = true;

                    console.log("PageSlug Error!", wpPromisedResult);
                  });
            },// getPage
            getPosts: function() {
                const wpPromisedResult = WordpressService.getRegularPosts();
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

    }
</script>
<style lang="scss" >
article.page {
   h3 {
        text-align: left;
    width: 540px;
    margin-bottom:0;
    max-width:90%;
    text-transform: initial;
    }
   
    h6 {
        text-align: left;
    }
    p {
        margin-bottom: 0;
        text-align:left;
        line-height:1.25rem;
    }
    h3,h6,p {
        padding-left:1rem;

    }

}
main.content p {
        margin-bottom: 0;
}

.big-toe {
    text-align: left;
    padding: 0.7rem 0 0.7rem 0;
}
.res {
    padding-top:0.7rem;
    color:rgba(83, 24, 179, 0.25);
    text-transform: uppercase
}
.sm {
    font-size:0.8rem;
}
#featured-image img {
        right: 0;
    bottom: 0;
    position: fixed;
    padding-bottom: 4rem;
    padding-right: 4rem;
    box-sizing: content-box;
}
#app a.x p {
    margin-bottom:0;
}
a.x p:hover {

}

</style>