<template>
    <div class="Dcolor pt4">
    <div class="tal x xab mw800 xw pb1">
        <h1 class="big-toe dib pr1 pb0 fs5">{{ decode(post.title.rendered) }}</h1><catLinks class="pt0" :post="post"></catLinks>
    </div>
    <div v-html="post.content.rendered" id="mainPostArea"  >
    </div>
    </div>
</template>
<script>
import catLinks from './catLinks.vue'
export default {
    name:"postContent",
    components: {
        catLinks
    },
    props: ["post"],
    computed: {
        DOMconversion: function(str) {
            return str;
        }
    },
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


.mw800 {
    max-width:900px;

}
#app p {
}
.big-toe, p {
    max-width:700px;
}
.mw800 {

        padding-left:1rem;
        padding-right:1rem;
}
#mainPostArea {
    & > p {
        padding-left:1rem;
        padding-right:1rem;
    }

    & > figure, & > ul, & > div, & > p {
        margin-bottom:5rem;
    }
    .wp-block-cover {
            height: 59vw;
    max-height: 80vh;
    }
    ul.columns-2, figure.columns-2  {
        width:80vw;
        margin:0 auto;
        margin-bottom: 5rem;
        li:first-child {
            padding-right:3.5rem;
        }
        li:last-child {
            padding-left:3.5rem;
        }

        &.offset-left {
            li:first-child {padding-top:5rem;}
            li:last-child{padding-bottom:8rem}
        }
        &.offset-right {
            li:first-child{padding-bottom: 5rem}
            li:last-child {padding-top:8rem;}
        }
    }
    .wp-block-cover:last-child {
        margin-bottom:0;
    }
    @media screen and (max-width:764px) {
        & > figure, & > ul, & > div{
            margin-bottom:4rem;
        }
         ul.columns-2, figure.columns-2  {
             margin-bottom:4rem;
             width:86vw;
           li:first-child {
            padding-right:1.5rem;
            }
            li:last-child {
                padding-left:1.5rem;
            }

         }
          & > p {
            margin-bottom:2rem;
        }
    }
    @media screen and (max-width:650px) {
       
    }
}
.big-toe.pr1 {
    padding-right:1rem;
    padding-bottom:0;
    margin-top: -1rem;
    padding-top: 1rem;
}
.wp-block-gallery .blocks-gallery-item {
    width: 50%;
    box-sizing:border-box;
    margin-right:0;
}
</style>