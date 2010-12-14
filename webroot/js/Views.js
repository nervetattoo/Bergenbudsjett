$(function()
{
    /* 
     * Model and Collection for budget posts
     */
    PostsView = Backbone.View.extend({

        posts : null,
        initialize: function(options)
        {
            _.bindAll(this, "render", "openPost");

            this.posts = options.posts;
        },

        events : {
            "click .item" : "openPost"
        },

        render : function()
        {
            var self = this;
            this.el.html("");
            this.posts.each(function(model) {
                var years = model.get('years');
                var html = $("#postBarTemplate").tmpl({
                    height : Math.round(300 * model.get('percentage')) + 50,
                    percentage : Math.round(model.get('percentage') * 100 * 10) / 10,
                    id : model.cid,
                    name : model.get('name')
                });

                self.el.append(html);
            });
            return this;
        },

        openPost : function(e)
        {
            var self = this,
                node = $(e.currentTarget);

            this.$(".item.active").removeClass("active");
            node.addClass("active");

            var model = this.posts.getByCid(node.attr("id"));
            var container = $(".circles").html("");

            $.ajax({
                url : '/budget/postsAndGrants.json',
                dataType : 'json',
                data : {
                    id : model.id
                },
                success : function(resp)
                {
                    console.log(resp);
                    _(resp.posts).each(function(node) {
                        var size = Math.round(376 * (node.y2011 / resp.total));
                        if (size < 50)
                            size = 50;
                        if (size > 176)
                            size = 176;
                        if (size == 0)
                            size = 2;
                        if (size % 2 != 0)
                            size++;
                        container.append(
                            $("#circleTemplate").tmpl({
                                size : size,
                                post : node.desc
                            })
                        );
                    });
                    console.log(container);
                }
            });

            /*
            this.posts.each(function(model) {
                var years = model.get('years');
                var html = $("#postBarTemplate").tmpl({
                    height : Math.round(1000 * model.get('percentage')),
                    percentage : Math.round(model.get('percentage') * 100),
                    name : model.get('name')
                });

                self.el.append(html);
            });
            */
        },
    });
});
