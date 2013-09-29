/**
 * View - Index
 */

(function($, _, Backbone, App){

    App.Views.UsersView = Backbone.View.extend({
        el: '#placehoder-users',
        template: null,
        collection: null,
        events: {},
        shown: false,

        initialize: function(options){
            var view = this;

            this.model = options.model;

            var templateName = 'UsersView';
            var templateUrl =  App.Env.baseUrl + 'app/templates/users.html';

            templateLoader.loadRemoteTemplate(templateName, templateUrl, function(data){
                view.template = _.template(data);
            });

            App.router.on('users:updated', function() {
                view.render();
            });

            App.router.on('users:hide', function() {
                view.shown = false;
                view.$el.fadeOut();
            });

            App.router.trigger('users:update');

        },

        render: function(){
            var view = this;

            view.shown = true;
            view.$el.html(this.template.call(this)).fadeIn();

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