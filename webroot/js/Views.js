$(function()
{
    /* 
     * Model and Collection for budget posts
     */
    PostsView = Backbone.View.extend({

        posts : null,
        initialize: function(options)
        {
            _.bindAll(this, "render");

            this.posts = options.posts;
        },

        render : function()
        {
            var self = this;
            this.el.html("");
            this.posts.each(function(model) {
                var years = model.get('years');
                var html = $("#postBarTemplate").tmpl({
                    height : Math.round(1000 * model.get('percentage')),
                    percentage : Math.round(model.get('percentage') * 100),
                    name : model.get('name')
                });

                self.el.append(html);
            });
            return this;
        },
    });
});
