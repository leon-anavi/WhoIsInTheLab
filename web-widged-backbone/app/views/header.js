/**
 * View - Header
 */

(function($, _, Backbone, App){

    App.Views.HeaderView = Backbone.View.extend({
        el: '#placehoder-header',
        template: null,
        model: null,
        events: {},

        initialize: function(options){
            var view = this;

            var templateName = 'HeaderView';
            var templateUrl =  App.Env.baseUrl + 'app/templates/header.html';

            templateLoader.loadRemoteTemplate(templateName, templateUrl, function(data){
                view.template = _.template(data);
                view.render();
            });
        },

        render: function(){
            var view = this;
            this.$el.html(this.template.call(this));
            console.log(view.model);
        },

        /**
         * Undelegate View events
         */
        destroy: function(){
            var view = this;
            view.dispose();
            view.$el.html('');

            return this;
        }
    });

})($, _, Backbone, App);