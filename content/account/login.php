

<form id="login-form" class="small-form" action="/api/user/login" method="POST">
  <div id="login-alert" class="alert alert-danger center margin-bottom hidden" role="alert"></div>
  <div class="row"> 
    <div class="col-sm-12 margin-bottom">      
      <label for="login-username">Username</label>
      <div class="custom-field validate-group">
        <i class="fa fa-user icon-left"></i>
        <input name="username" id="login-username" class="validate" type="text" placeholder="..." required>        
      </div><!-- .custom-field -->
    </div><!-- .col -->
    
    <div class="col-sm-12 margin-bottom">      
      <label for="login-password">Password</label>
      <div class="custom-field validate-group">
        <i class="fa fa-lock icon-left"></i>
        <input name="password" id="login-password" class="validate" type="password" placeholder="..." required>        
      </div><!-- .custom-field -->
    </div><!-- .col -->

    <div class="col-sm-12">
      <div class="validate-group">
        <label for="remembered">
          <input class="margin-right-xs" name="remembered" id="remembered" value="true" type="checkbox">
          Remember me
        </label> <br />
        <a href="/forgot-password" class="small margin-top-xs">Forgot Password?</a>
      </div><!-- .validate-group -->
    </div><!-- .col -->
  </div><!-- .row -->

  <div class="row align-center"> 
    <div class="col-sm-12 align-center">
      <input type="hidden" id="LOGIN_URL" value="<?php echo empty($_GET['url']) ? '' : $_GET['url']; ?>" />
      <input type="hidden" id="LOGIN_FORM_TOKEN" name="FORM_TOKEN" value="<?php echo $sc->common->generateFormToken('LOGIN'); ?>" />
      <button id="login-button" type="submit" class="button button-primary block margin-top<?php echo ($sc->vars->pageType == 'sign-in') ? ' button-large' : ''; ?>">Sign In</button>
    </div><!-- .col -->
  </div><!-- .row -->
</form>
