/**
 * Main App controller.
 * Pack your default stuff in here as a `Backbone.Controller`
 */
$(function() {
    BudgetApp = Backbone.Controller.extend({                                                                
        initialize : function(options)
        {                                                                                                            
            this.bind('closeAll', this.closeAll);
            /**
             * MediaCollection
             */
            this.models = new PostCollection();
            this.models.fetch({
                success : this.renderPosts
            });
        },

        routes : {
            "post/:id" : "openPost",
            "" : "posts",
        },

        openPost : function(id)
        {
        },

        renderPosts : function(collection)
        {
            this.postsView = new PostsView({
                posts : collection,
                el : $(".bottom")
            }).render();
        }
    });
});
