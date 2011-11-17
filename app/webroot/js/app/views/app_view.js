    dac.AppView = cbb.View.extend({
        el: $('body').get(0),
        events: {
            'click [role=main] a[href*="customers/"]': 'navigateUrl',
            'click nav#menu a': 'navigateUrl',
        },
        navigateUrl: function(e) {
            var usedModifier = e.which == 1 && (e.altKey || e.ctrlKey || e.shiftKey || e.metaKey),
                href = $(e.target).attr('href').replace(/^\/?(.*)/, '$1');
            if (!usedModifier) {
                e.preventDefault();
                this.router.navigate(href,true);
            }
        }
    });
