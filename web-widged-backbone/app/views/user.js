/**
 * View - Index
 */

(function($, _, Backbone, App){

    App.Views.UsersView = Backbone.View.extend({
        el: '#placehoder-users',
        template: null,
        collection: null,
        events: {},

        initialize: function(options){
            var view = this;

            this.model = options.model;

            var templateName = 'UsersView';
            var templateUrl =  App.Env.baseUrl + 'app/templates/users.html';

            templateLoader.loadRemoteTemplate(templateName, templateUrl, function(data){
                view.template = _.template(data);
                view.render();
            });
        },

        render: function(){
            var view = this;
            this.$el.html(this.template.call(this));
        },

        /**
         * Undelegate View events
         */
        destroy: function(){
            this.dispose();
            this.$el.html('');

            return this;
        }
    });

})($, _, Backbone, App);