    $(function(){
        var duxApp = {
            appRouter: new dac.AppRouter,
            facadesRouter: new dac.FacadesRouter,
            customersRouter: new dac.CustomersRouter,
            invoicesRouter: new dac.InvoicesRouter,
            utilitiesRouter: new dac.UtilitiesRouter,
            searchView: new dac.SearchView({viewTemplate: 'searchView'})
        };
        $('nav#menu a').click(function(e){
            var usedModifier = e.which == 1 && (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey),
                url = $(this).attr('href').match(/^\/?(.*)/)[1];
            if (!usedModifier) {
                e.preventDefault();
                duxApp.appRouter.navigate(url,true);
            }
        });
        Backbone.history.start({pushState:true});
        window.duxApp = duxApp;
    })
