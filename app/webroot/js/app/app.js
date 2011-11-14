$(function(){
    var ODN = {
        routers: {
            app: new dac.AppRouter,
            facades: new dac.FacadesRouter,
            customers: new dac.CustomersRouter,
            invoices: new dac.InvoicesRouter,
            utilities: new dac.UtilitiesRouter
        },
        views: {
            search: new dac.SearchView({viewTemplate: 'searchView'})
        }
    };
    $('nav#menu a').click(function(e){
        var usedModifier = e.which == 1 && (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey),
            url = $(this).attr('href').match(/^\/?(.*)/)[1];
        if (!usedModifier) {
            e.preventDefault();
            ODN.routers.app.navigate(url, true);
        }
    });
    Backbone.history.start({pushState:true});
    window.ODN = ODN;
})
