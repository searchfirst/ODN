<script>
yepnope([
{test:Modernizr.localstorage,nope:['/js/libs/poly/storage.js']}
]);
</script>
<?php echo $this->Minify->js_link($minify['js']['foot']) ?> 
<script src="/js/app/cache.js"></script>
<script src="/js/app/templates.js"></script>
<script src="/js/app/models/customer.js"></script>
<script src="/js/app/models/service.js"></script>
<script src="/js/app/models/website.js"></script>
<script src="/js/app/models/user.js"></script>
<script src="/js/app/models/note.js"></script>
<script src="/js/app/models/invoice.js"></script>
<script src="/js/app/models/facade.js"></script>
<script src="/js/app/models/schedule.js"></script>
<script src="/js/app/views/customers.js"></script>
<script src="/js/app/views/services.js"></script>
<script src="/js/app/views/websites.js"></script>
<script src="/js/app/views/users.js"></script>
<script src="/js/app/views/notes.js"></script>
<script src="/js/app/views/invoices.js"></script>
<script src="/js/app/views/facades.js"></script>
<script src="/js/app/views/schedules.js"></script>
<script src="/js/app/controllers/customers.js"></script>
<script src="/js/app/controllers/services.js"></script>
<script src="/js/app/controllers/websites.js"></script>
<script src="/js/app/controllers/users.js"></script>
<script src="/js/app/controllers/notes.js"></script>
<script src="/js/app/controllers/invoices.js"></script>
<script src="/js/app/controllers/facades.js"></script>
<script src="/js/app/controllers/schedules.js"></script>
<script src="/js/app/app.js"></script>
<?php echo $this->Minify->js_tpl($minify['tpl']) ?>
