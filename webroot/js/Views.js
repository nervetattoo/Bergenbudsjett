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
            $(".items").html("");
            var total = 0;
            this.posts.each(function(model) {
                var years = model.get('years'),
                    percentage = model.get('percentage'),
                    hBase = 200 * model.get('percentage'),
                    wBase = 100 * model.get('percentage');

                var height = Math.round(hBase + (80 * percentage)) + 70,
                    width = 100;
                    //width = Math.round(wBase + (30 * percentage)) + 20;

                total += width;

                //height : Math.round(300 * model.get('percentage')) + 50,

                var millions = Math.round(years[2011] / 1000);
                var html = $("#postBarTemplate").tmpl({
                    height : height,
                    width : width,
                    millions : millions,
                    percentage : Math.round(model.get('percentage') * 100 * 10) / 10,
                    id : model.cid,
                    name : model.get('name')
                });

                $(".items")
                    .append(html);
            });
            $(".items")
                .css({
                    width : total + "px",
                    'padding-left' : '150px',
                    'padding-right' : '150px',
                });
            return this;
        },

        openPost : function(e)
        {
            var self = this,
                node = $(e.currentTarget);


            this.$(".item.active").removeClass("active");
            node.addClass('active');
            /*
            var toRemove = 0;
            this.$(".item.active").each(function() {
                var n = $(this);
                toRemove = 100 - n.data('width');
                n.find('span')
                        .css('width', (n.data('width') - 1) + 'px')
                        .end()
                    .css('width', n.data('width') + 'px')
                    .removeClass("active");
            });

            var items = $(".items"),
                nodeWidth = 100 - node.width();

            items.css({
                width : items.width() + nodeWidth - toRemove
            });
            */

            /**
             * Set width and cache current width for resetting
            node
                .data('width', node.width())
                .css('width', '100px')
                .addClass("active")
                .find('span')
                    .css('width', '99px');
             */

            /**
             * Move node to the middle of .items
            var items = $(".item");
            var insertAt = Math.round(items.length / 2);
            items.slice(insertAt, insertAt + 1).before(
                node.detach()
            );
             */

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
                    _(resp.posts).each(function(node) {
                        var size = Math.round(
                            376 * (node.y2011 / resp.total)
                        );
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
                }
            });
        },
    });
});
