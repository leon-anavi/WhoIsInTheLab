/**
 * Master application
 * Includes Classes App
 */

window.App = window.App || {};
App.Env = App.Env || {};

(function($, _, Backbone, App){

    /**
     * Enviornment Setup
     * This reflects the template loader cache
     */
    //development | staging | production
    App.Env.environment = 'development';
    App.Env.templateVersion = '0.0.1';

    App.Models = App.Models || {};
    App.Views = App.Views || {};
    App.Collections = App.Collections || {};

    var WorkspaceRouter = Backbone.Router.extend({

        /**
         * Define application routes
         **/
        routes: {
            '!/show-users' : 'users'
        },

        //lower case for class instance
        models: {},
        views: {},
        collections: {},

        /**
         * Initialize application
         **/
        initialize: function (options) {
            var router = this;

            this.on('all', function (eventName) {
                console.info(eventName);
            });
        },

        /**
         * Users Routing
         * returns router
         **/
        users: function() {
            var router = this;

            //create new book model if not present
            router.collections.users = router.collections.users || new App.Collections.Users();

            // router.views.usersView.render();

            router.views.usersView = new  App.Views.UsersView({collection: router.collections.users});


            //this will be replaced with JSONP call
            $.get(App.Env.baseUrl + 'test-api/get_users.php', function(data) {
                console.log(data);

                _.each(data.data.users, function(item, index) {
                    router.collections.users.push(item);
                });

                router.models.guests = data.data.guests;

                router.trigger('users:updated');

            }, 'json');


            // turn off all index events
            router.on('all', function (eventName) {
                if (eventName && eventName.indexOf('route:') === 0 && eventName !== 'route:users') {
                    console.log('users off');
                    this.off(null, arguments.callee);
                    router.views.usersView && router.views.usersView.destroy() && (delete router.views.usersView);
                }
            });

            return this;
        }

    });


    /**
     * Document ready
     */
    $(function() {

        App.router = new WorkspaceRouter();

        Backbone.history.start();

    });

})($, _, Backbone, App);