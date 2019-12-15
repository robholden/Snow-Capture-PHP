<div class="normal-content">
  <h3>
  	Your Information
  </h3>
  <p>
    Manage all your personal information here.
  </p>
  
  <hr />
  
  <form id="settings-form" class="form" action="/api/user/update_general" method="POST">
    <div class="row"> 
      <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="validate-group pull-left margin-bottom">
          <label for="name">Name</label>
          <input name="name" id="name" class="form-control validate" type="text" value="<?php echo $sc->user->name; ?>" placeholder="..." required>
        </div><!-- .validate-group -->
      </div><!-- .col -->
    </div><!-- .row -->
  
    <div class="row"> 
      <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="validate-group margin-bottom">
          <label for="display-name">Display Name</label>
          <input name="display_name" id="display-name" class="form-control validate" type="text" value="<?php echo $sc->user->displayName; ?>" data-original="<?php echo $sc->user->displayName; ?>" placeholder="..." required>
          <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
          <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        </div><!-- .validate-group -->
      </div><!-- .col -->
  
      <div class="col-xs-12 col-sm-12 col-md-6">
        <div class="validate-group pull-left margin-bottom">
          <label for="email">Email Address</label>
          <input name="email" id="email" class="form-control validate" type="email" value="<?php echo $sc->user->email; ?>" src="/template/images/placeholder.jpg" data-original="<?php echo $sc->user->email; ?>" placeholder="..." required>
          <span class="invalid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
          <span class="valid-circle validate-circle form-control-feedback" aria-hidden="true"></span>
        </div><!-- .validate-group -->
      </div><!-- .col -->
    </div><!-- .row -->
  
  	<hr />
  
    <button type="submit" class="button button-primary margin-top">Save Settings</button>
  </form>
</div>




