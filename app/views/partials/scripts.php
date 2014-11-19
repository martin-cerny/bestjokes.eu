<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-50160154-1', 'bestjokes.eu');
    ga('send', 'pageview');
</script>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/cs_CZ/sdk.js#xfbml=1&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<script>
$(".delete").on('click', function(e){
    var $form=$(this).closest('form');
    var $id = $(this).attr('id');
    e.preventDefault();
    $('#confirmation-' + $id).modal({ backdrop: 'static', keyboard: false })
        .one('click', '.btn-delete', function (e) {
            $form.trigger('submit');
        });
});

//$(".delete").on("click", function(e) {
//    var link = this;
//
//    e.preventDefault();
//
//    $("<div>Are you sure you want to delete?</div>").dialog({
//        buttons: {
//            "Ok": function() {
//                window.location = link.href;
//            },
//            "Cancel": function() {
//                $(this).dialog("close");
//            }
//        }
//    });
//});
</script>