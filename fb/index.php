<html
    xmlns="http://www.w3.org/1999/xhtml"
    xmlns:addthis="http://www.addthis.com/help/api-spec">
    <head>



    </head>
    <body>





<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=meerilsa"></script>
<a class="addthis_button"></a>

<script type="text/javascript">
// Alert a message when the user shares somewhere
function shareEventHandler(evt) {
    if (evt.type == 'addthis.menu.share') {
        alert(typeof(evt.data)); // evt.data is an object hash containing all event data
        alert(evt.data.service); // evt.data.service is specific to the "addthis.menu.share" event
    }
}

// Listen for the share event
addthis.addEventListener('addthis.menu.share', shareEventHandler);


</script>
    </body>
</html>

