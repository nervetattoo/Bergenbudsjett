$(function()
{
    /* 
     * Model and Collection for budget posts
     */
    Post = Backbone.Model.extend({

        initialize: function()
        {
            if (!this.get("id"))
                this.set({id : ""});
            if (!this.get("name"))
                this.set({name : ""});
            if (!this.get("years"))
                this.set({years : {2011:0,2010:0,2009:0}});
            if (!this.get("num"))
                this.set({num : 0});
        },

        mediaId : function()
        {
        },
    });

    PostCollection = Backbone.Collection.extend({
        model: Post,

        initialize: function()
        {
            this.url = "/budget/groups.json";
        },

        /**
         * Get the actual image ids the collection is carrying.
         */
        parse : function(response)
        {
            return _(response.groups).map(function(data) {
                return {
                    id : data._id,
                    name : data.name,
                    num : data.num,
                    years : {
                        2011 : data.y2011,
                        2010 : data.y2010,
                        2009 : data.y2009,
                    }
                };
            });
        },
    });

});
