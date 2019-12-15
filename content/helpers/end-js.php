<?php
  if (DEVELOPMENT < 2)
  {
    loadJS(new DirectoryIterator(WEB_ROOT . 'js/guest'));
    loadJS(new DirectoryIterator(WEB_ROOT . 'js/plugins'));
    if($sc->session->isUser())
    {
      loadJS(new DirectoryIterator(WEB_ROOT . 'js/user'));
      loadJS(new DirectoryIterator(WEB_ROOT . 'js/plugins/user'));

      if ($sc->session->isAdmin())
      {
        loadJS(new DirectoryIterator(WEB_ROOT . 'hq/js'));
      }
    }
  }

  else
  {
    echo $sc->session->isGuest() ? '<script src="/js/min/sc.guest.min.js"></script>' : ($sc->session->isAdmin() ? '<script src="/js/min/sc.admin.min.js"></script>' : '<script src="/js/min/sc.user.min.js"></script>');
  }

  function loadJS($dir)
  {
    foreach ($dir as $fileinfo)
    {
      if (! $fileinfo->isDot())
      {
        if (strpos($fileinfo->getFilename(), '.js'))
        {
          $js = preg_replace('#(.*)\/Snow-Capture#', '', $fileinfo->getPathname());

          echo '
            <script src="' . str_replace(WEB_ROOT, '/', str_replace('//', '/', $js)) .'"></script>
          ';
        }
      }
    }
  }
?>




<?php

  if(DEVELOPMENT == 3):

?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-70028606-1', 'auto');
  ga('send', 'pageview');

</script>
<?php

endif;

?>