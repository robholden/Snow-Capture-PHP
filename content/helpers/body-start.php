<body class="<?php echo $sc->session->exists() ? 'is-user' : 'is-guest'; echo $sc->common->isMobile ? ' mobile' : ''; ?>">

<?php 

if ($sc->session->exists()):

?>

<script>
// call facebook script
//   (function(d){
//    var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
//    js = d.createElement('script'); js.id = id; js.async = true;
//    js.src = "https://connect.facebook.net/en_US/all.js";
//    d.getElementsByTagName('head')[0].appendChild(js);
//   }(document));
</script>

<?php 

endif;

?>


<?php

  // Cookie Check
  if(!isset($_COOKIE['cookies_enabled'])):

?>
  <div id="cookie-alert" class="alert alert-danger fade in">
    <div class="container">
      <form id="cookie_submit" method="POST" action="/">
        <input type="hidden" value="true" name="cookie" />
        <button id="cookie-close" type="submit" class="close pull-right" aria-hidden="true">OK</button>
        <p>
          We use cookies to give you the best online experience. 
          By using our website you agree to our use of cookies in accordance with our <a href="/policies/cookies">Cookie Policy</a>
        </p>
      </form>
    </div>
  </div>

  <script>
    $('#cookie_submit').on('submit', function(){
      $.ajax({
        type: "POST",
        url: "/",
        beforeSend: function() {},
        data: {cookie:'true'},
        success: function(data){
          $('#cookie-alert').slideUp(250);
        }
      });
      return false;
    });
  </script>
<?php

  endif;

?>


<noscript>
  <div class="alert alert-danger text-center margin-none">
    <h4 class="container">Please enable Javascript. Snow Capture is awful without it :(</h4>
  </div>
</noscript>



<?php 

// IsUer
if($sc->session->exists()):

  // IsStatusOne
  if($sc->user->status == LEVEL_USER):

?>

<div style="z-index: 6;" class="alert alert-danger text-center padding-xs margin-none">
  <p class="container">Please confirm your email address. <?php if($sc->user->canConfirm()): ?><a href="#" class="send-confirm inline small codeblue margin-left">Resend email</a> <?php endif; ?></p>
</div>

<?php 

  // End StatusIsOne
  endif;

// End IsUser
endif;

?>