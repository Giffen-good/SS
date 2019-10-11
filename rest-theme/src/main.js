import Vue from 'vue/dist/vue.js'

import router from './router.js'
import App from './App.vue'
import Footer from './components/theme-footer.vue';
Vue.component('theme-footer', Footer);

//var App =
new Vue({
    el: '#app',
    router: router,
    render: h => h(App),
    components: {App},
    mounted() {
        this.updateTitle('');
    },

    methods: {
        updateTitle(pageTitle) {
            document.title = (pageTitle ? pageTitle + ' - ' : '') + wp.site_name;
        }
    },

    updated: function(){

        this.$nextTick(function () {
            // Handy function for when all children - RESEARCH THIS
                // Watch for any links pulled in from WP articles, etc.
                jQuery( "a[href^='"+wp.base_path+"'], a[href^='/']:not([href*='#'])"  ).on("click", function(event){
                    event.preventDefault();
    
                  //Decide which route:
                    var linkPath = ( jQuery(this).attr("href").indexOf(wp.base_url) > -1) ? jQuery(this).attr("href").replace(wp.base_url,'/') : jQuery(this).attr("href").replace(wp.base_path,'/');
    
                  // Actually change the path now:
                    router.replace( linkPath );
                });

        });
    },

    events: {
        'page-title': function(pageTitle) {
            this.updateTitle(pageTitle);
        }
    }
});


///////////////////////////////////////////////////////////////////////
//  Fix the static nav <a> links to be <router-link>.
///////////////////////////////////////////////////////////////////////
jQuery( "a[href^='"+wp.base_path+"'], a[href^='/']:not([href*='#'])"  ).on("click", function(event){
    event.preventDefault();

  //Decide which route:
    var linkPath = ( jQuery(this).attr("href").indexOf(wp.base_url) > -1) ? jQuery(this).attr("href").replace(wp.base_url,'/') : jQuery(this).attr("href").replace(wp.base_path,'/');

  // Actually change the path now:
    router.replace( linkPath ); //resolvedRoute.route.fullPath );

  //UI Stuff:
    jQuery(".site-header .navbar-collapse").collapse('hide');  //hide the nav.
    jQuery(".site-header li").removeClass('current-menu-item');
    jQuery(this).parent("li").addClass('current-menu-item');

}); // click


