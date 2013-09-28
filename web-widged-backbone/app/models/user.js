/**
 * Model - Hackafe user
 **/
App.Models.User = Backbone.Model.extend({

    //instance methods
    defaults: {

        name1: 'Guest',
        name2: null,
        twitter: null,
        googlePlus: null,
        facebook: null,
        tel: null,
        email: null
    },

    initialize: function(options) {

    }

},{
//static methods
});

App.Collections.Users = Backbone.Collection.extend({
    model: App.Models.User
});