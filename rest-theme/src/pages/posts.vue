

<template>
  <main class="content Dcolor">
    <div v-if="!isLoading">
      <div id="overflowing-gradient" class="Dcolor">
        <div class="gradient arrow prev gradient-left"></div>
        <div class="gradient arrow next gradient-right"></div>
        <div id="fixedHeader" class="x xac">
          <div class="feature-nav dib first-el"></div>
          <div
            :class="`feature-nav dib category_${key}`"
            v-for="(post, key) in regPosts"
            :id="`index-${post.id}`"
            :key="post.id"
          >
            <a :href="post.link">
              <p class="big-t fs5">{{ decode(post.title.rendered) }}</p>
            </a>
            <a
              class="db fs1"
              :href="post.categories_list[0].link"
            >{{post.categories_list[0].cat_name}}</a>
          </div>
          <div class="feature-nav dib last-el"></div>
        </div>
      </div>

      <div id="largest-container">
            <div  
            :id="`category_${key}`"
          v-for="(post, key ) in posts"
          :key="key"
          :class="`postEl category dt ${(key % 2 == 0) ?  'even' :  'odd' } index-${post.ID}`"
          :data-src="post.post_title">
            <div class="fixedEl">
                  <div class="little-container dtc vam" >
                    <div
                      class="psr bgpc bgsc dtc vam"
                      :href="post.guid"
                      v-for="(img, i) in post.acf.image_carousel"
                      :key="i"
                    >
                      <a  :href="post.guid"><img @load="onLoaded()" class="img-obj post-img" :src="img.image.sizes.large" /></a>
                    </div>
                  </div>
              </div>
    </div>
        
      </div>
    </div>
  </main>
</template>

<script>
import WordpressService from "../services/wordpress";
export default {
  data() {
    return {
      loading: true,
      error: false,
      isLoading: true,
      posts: [],
      regPosts: [],
      isLoaded: [],
      imgCount:0
    };
  },
 
  mounted() {
    this.getRegularPosts();
    this.getPosts();

    setTimeout(() => {
      this.isLoading = false;

      document.querySelector(".loader").classList.add("fadeout");

    }, 2000);
  },
  
  methods: {
  
   onLoaded() {

      this.isLoaded.push(true);

      if (this.isLoaded.length == this.imgCount) {
        this.constructSlideshow();
      }
    },
    getPosts: function() {
      const wpPromisedResult = WordpressService.getPosts();
      wpPromisedResult
        .then(result => {
          console.log("PostSlug Found!", result.posts, result.totalPages);
          this.loading = false;

          if (result.posts.length == 0) {
            this.error = true; //alternate content control too
            console.log("PostSlug Found, no data");
          } else {
            this.posts = result.posts;
            for (let i = 0; i< result.posts.length;i++) {
            this.imgCount += result.posts[i].acf.image_carousel.length

            }
          }
        })
        .catch(err => {
          this.error = true;

          console.log("PostSlug Error!", wpPromisedResult);
        });
    },
    getRegularPosts: function() {
      const wpPromisedResult = WordpressService.getRegularPosts();
      wpPromisedResult
        .then(result => {
          console.log("PostSlug Found!", result.posts, result.totalPages);
          this.loading = false;

          if (result.posts.length == 0) {
            this.error = true; //alternate content control too
            console.log("PostSlug Found, no data");
          } else {
            this.regPosts = result.posts;
          }
        })
        .catch(err => {
          this.error = true;

          console.log("PostSlug Error!", wpPromisedResult);
        });
    },
    formattedString(s) {
      let popped = s.split("");
      let shifted = popped.shift();

      return [shifted, popped.join("")];
    },
    decode: function(str) {
      var txt = document.createElement("textarea");
      txt.innerHTML = str;
      return txt.value;
    },
    constructSlideshow: function() {
       // SCROLLING FEATURE

      // Get Array of WP-posts
      var postContainers = document.getElementsByClassName("postEl");
      var masterContainer = {
        //scroll OBJ where each section is a property
        // category_0:[width, distance from top, 0, index-{post.id}]
        // ...
        // category_n:[width, distance from top, n, index-{post.id}]
      };

      function name(i) {
        // returns unique section ID
        return "category_" + i;
      }
      function modName(i) {
        return "category_" + i;
      }
      var elDimensions;
      var firstTime = true;
      // Get
      (elDimensions = function() {


        let largestContainer = document.querySelector("#largest-container");
        var masterHeight = 0;
        // start at one because first node is fixedHeader
        const imgMargin = parseInt(window.getComputedStyle(document.querySelector('.post-img')).marginRight);
        for (let i = 0; i < postContainers.length; i++) {
          let curr = postContainers[i].getElementsByClassName("post-img");
          let containerWidth = 0; //initial container width

          for (let i = 0; i < curr.length; i++) {
            let imgDimensions = curr[i].getBoundingClientRect(); 
            //get The  Width of the each post based on sum of contained IMGS + margin(?)
            containerWidth += imgDimensions.right - imgDimensions.left + imgMargin*2;
          }
          //add 2 additional margin units for even space on the first and last imgs 
          containerWidth += imgMargin*2;
          // The "overflowing" div holding all the imgs. Set height based on img widths
          let fixedEl = postContainers[i].firstElementChild;
          fixedEl.style.width = containerWidth + "px";
          postContainers[i].style.width = containerWidth + "px";
          postContainers[i].firstElementChild.style.width =
            containerWidth + "px";

          // Special - parent height and width equal so that vertical scroll can trigger side scroll
          postContainers[i].style.height = containerWidth + "px";

          document.getElementById(name(i)).style.top = masterHeight + "px";
          // DEFINING THE PROPERTIES OF THE MAIN OBJECT
          masterContainer.sumTotal = i;
          masterContainer[modName(i)] = [containerWidth, masterHeight, i];
          masterHeight += containerWidth;
          // Set initial transform property for Right-to-left (every other post) posts
          if (i % 2 != 0 && firstTime ) {
            completedTransform(modName(i), false);
          }
        }
        largestContainer.style.height =
          masterHeight+ "px";
        
        resetPostTrans();
      })();
            firstTime = false;

      function resetPostTrans() {
        var scrollPos =
          window.pageYOffset ||
          document.documentElement.scrollTop ||
          document.body.scrollTop ||
          0;
        for (var prop in masterContainer) {
          let isPast = scrollPos > masterContainer[prop][1] ? true : false;

          //set posts to their "completed state" if the initial scroll position is past the post element
          if (masterContainer[prop][2] % 2 == 0 && isPast) {

            document.getElementById(prop).firstElementChild.style.transform =
              "translate3d(-" +
              (masterContainer[prop][0] - window.innerWidth) +
              "px, 0, 0)";
          } 
        }
      }
      let gradient = document.querySelectorAll('.gradient');
          var objectLength = 0;
      for (var k in masterContainer) {
          if (masterContainer.hasOwnProperty(k)) {
            ++objectLength;
          }
      }

      function completedTransform(prop, isZero = true, specified = null) {
        if (isZero) {
          var transX = 0;
        } else {
          if (specified != null) {
            var transX = specified;
          } else {
            var transX = masterContainer[prop][0] - window.innerWidth;
          }
        }  
         document.getElementById(prop).firstElementChild.style.transform = "translate3d(-" + transX + "px, 0, 0)"
      }

      function shiftSlide(newVal) {
           
            let top = masterContainer[modName(newVal)][1];
            document.documentElement.scrollTop = document.body.scrollTop = top+2;
             removeAll( 'active', actArray );
            document.getElementById(name(newVal)).classList.add('active');
      }
      function shiftSlideAndCarousel(index) {
        
      }
      // NEXT AND PREVIOUS ARROWS FOR THE SLIDE
      for (let i = 0; i< gradient.length; i++) {
        gradient[i].addEventListener('click',function() {
          let id = document.querySelector('.category.active') ? document.querySelector('.category.active').id : document.querySelector('.category.interim').id;
          let index = parseInt(id.split('_')[1]);

         if ( i == 0 && index != 0 ) { // click left - navigate to previous slide 
            if ( index % 2 == 0 ) {
                 completedTransform(id);
            } else {
                 completedTransform(id,false);
            }

             let newIndex = (index - 1);
            shiftSlide(newIndex)
            shiftCarousel(newIndex);
          }
          if (i == 1 && index != objectLength-2){ //right 
           if ( index % 2 == 0 ) {
                 completedTransform(id,false);
            } else {
                 completedTransform(id);
            }
            let newIndex = (index + 1);
            shiftSlide(newIndex)
            shiftCarousel(newIndex);

          }
        })
      }
    function scrollofY() {
      return window.pageYOffset ||
          document.documentElement.scrollTop ||
          document.body.scrollTop ||
          0;
    }
      function resizeTrackPosition() {
        var scrollPos = scrollofY();
        for (var prop in masterContainer ) {
          let diff = masterContainer[prop][1] - scrollPos;
          if (diff >= 0 ) {
            if ( document.getElementById(prop).classList.contains('even')) {

            }
          }
        }


      }

      // Text slideshow that shifts according to the active post
      var actArray = document.getElementsByClassName("active");
      var intArray = document.getElementsByClassName("interim");
 
      var raf =
        window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        window.oRequestAnimationFrame;
      window.addEventListener("scroll", function() {
        if (raf) {
          activeElementTracking();
        }
      });

      var resizeTimer;

      window.addEventListener("resize", function() {
        if (raf) {
          // Recalculate Image and container dimensions
          elDimensions();
        resetPostTrans();

        }
        clearTimeout(resizeTimer);
        // Debouncing function
        resizeTimer = setTimeout(function() {
          // Run code here, resizing has "stopped"
          if (raf) {
          elDimensions();
          activeElementTracking();
        resetPostTrans();

          }
        }, 250);
      });

      function shiftCarousel(i) {
          let catClasses = document.getElementById(name(i)).classList;
            let j = catClasses.length - 1;
            for (let k = 0; k<catClasses.length;k++) {
              if(catClasses[k].split("-")[0] === "index") {
                j = k;
              }
            }
            if (
              catClasses[j].split("-")[0] === "index" &&
              !document
                .getElementById(catClasses[j])
                .classList.add("active-carousel")
            ) {
              let txtElWidth = 340;
              let txtElsInView = 3;
              
              // Aka remainder
              let carouselMargin = (window.innerWidth - txtElWidth * txtElsInView) / (txtElsInView*2);
              let translationX = (window.innerWidth / 2 - txtElWidth/2 - carouselMargin) * -i;

              if (carouselMargin <= 0) { // if 3 Txt elements cannot within window width
                carouselMargin = 10;
                translationX =[ 0.5*(3*txtElWidth - window.innerWidth) + carouselMargin*3]*(-1) - i*(txtElWidth + carouselMargin*2);
              }

                fixedHeader.style.transform =
                "translate3d(" + translationX + "px, 0, 0)";
              const featureNav = document.querySelectorAll(".feature-nav");
              for (let j = 0; j < featureNav.length; j++) {
                featureNav[j].style.marginLeft = carouselMargin + "px";
                featureNav[j].style.marginRight = carouselMargin + "px";
              }
              removeAll(
                "active-carousel",
                document.getElementsByClassName("active-carousel")
              );

              document
                .getElementById(catClasses[j])
                .classList.add("active-carousel");
            }
            resetPostTrans();
      }



      function checkVerticalArea(scrollPos) {
        for (let i = masterContainer.sumTotal; i >= 0; i--) {
          //INCREMENTING backwards
          let diff = masterContainer[modName(i)][1] - scrollPos;
          if (diff <= 0) {
            shiftCarousel(i);
            resetPostTrans();

            return [i, diff];
          }
        }
      }
      // Strip classes
      function removeAll(className, c) {
        // create counter to end while loop is an error occurs
        let i = 0;
        while (c[0] && i < 100) {
          c[0].classList.remove(className);
          i++;
        }
      }

      //IIFE & triggered onscroll
      var activeElementTracking;
      (activeElementTracking = function() {
        var scrollPos =
          window.pageYOffset ||
          document.documentElement.scrollTop ||
          document.body.scrollTop ||
          0;
        // CURRENT POST META
        let elMeta = checkVerticalArea(scrollPos);
        // get Current post properties
        const objArray = masterContainer[modName(elMeta[0])];
        //Change in active element
        if (Math.abs(elMeta[1]) > objArray[0] - window.innerHeight) {
          removeAll("active", actArray);

          // vertical scrolling state between elements
          document.getElementById(name(elMeta[0])).classList.add("interim");
        resetPostTrans();


        } else {
          //START of new Current post
          if (actArray.length > 1) {
            actArray[0].classList.remove("active");

          }

          document
            .getElementById(name(elMeta[0])).classList.add('active');
          document
            .getElementById(name(elMeta[0]))
            .firstElementChild.classList.add("active");
          // THE SCROLL MAGIC
          let b =
            (objArray[0] - window.innerWidth) /
            (objArray[0] - window.innerHeight);
          if (objArray[2] % 2 == 0) {
            document.getElementById(
              name(elMeta[0])
            ).firstElementChild.style.transform =
              "translate3d(-" + Math.abs(b * elMeta[1]) + "px, 0, 0)";
          } else {
            document.getElementById(
              name(elMeta[0])
            ).firstElementChild.style.transform =
              "translate3d(-" +
              (objArray[0] - window.innerWidth - Math.abs(b * elMeta[1])) +
              "px, 0, 0)";
          }
          removeAll("interim", intArray);
        }
      })();
    }
  },
  updated() {
    this.$nextTick(() => {
    })
  }
}
</script>
<style lang="scss" >
</style>