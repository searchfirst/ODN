$(function(){
    var ODN = {};
    ODN.routers = {
        app: new dac.AppRouter
    };
    ODN.views = {
        search: new dac.SearchView({
            router: ODN.routers.app,
            viewTemplate: 'searchView'
        })
    };
    Backbone.history.start({pushState:true});
    window.ODN = ODN;
})
