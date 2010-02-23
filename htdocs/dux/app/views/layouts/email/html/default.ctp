<?php echo $html->doctype('html4-strict')?> 
<html>
<head>
<style type="text/css">
body {
color: rgb(0,0,0);
font: normal normal normal 1em HelveticaNeue,'Helvetica Neue',Helvetica,'Trebuchet MS','MS Sans Serif',Tahoma,Arial,sans-serif;
padding: 20px;
}

h1, h2, h3, h4, ol, p, ul {
padding: 0;
margin: 0 0 10px;
}

h1 {
font: bold normal normal 1.18em HelveticaNeue-Light,'Helvetica Neue Light',Helvetica,Tahoma,'MS Sans Serif',sans-serif;
text-transform: uppercase;
word-spacing: -1px;
color: rgb(33,41,50);
}

h2 {
font: bold normal normal 1em HelveticaNeue-Light,'Helvetica Neue Light',Helvetica,Tahoma,'MS Sans Serif',sans-serif;
text-transform: uppercase;
word-spacing: -1px;
margin: 0 0 5px;
color: rgb(33,41,50);
padding-bottom: 5px;
border-bottom: solid 1px rgb(0,0,0);
}

strong {
font-weight: bold;
color: rgb(24,36,50);
padding: 1px 3px;
}

table, tr, td {
border-collapse: separate;
}

thead, tr, tbody, tfoot {
padding: 0;
margin: 0;
}

table td, table th {
border: solid 1px rgb(63,63,95);
padding: 5px 10px;
margin: 3px;
}

th {
background-color: rgb(227,227,227);
font-weight: normal;
}

ul {
list-style-type: circle;
}
</style>
</head>
<body>
<?php echo $content_for_layout;?> 
</body>
</html>