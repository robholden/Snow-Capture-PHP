<header>
  <div class="container">      
    <ul class='explore-links'>
      <li>
        <a href="/" class='<?php echo ($sc->common->currentURL() == $sc->common->siteURL() . '/') ? 'active' : ''; ?>'>Home</a>
      </li>
      <li>
        <a href="/search" class='margin-left <?php echo ($sc->vars->pageType == 'search') ? 'active' : ''; ?>'>Search</a>
      </li>
      <li>
        <a href="/map" class='margin-left <?php echo ($sc->vars->pageType == 'map') ? 'active' : ''; ?>'>Map</a>
      </li>
    </ul>
    
    <div id="logo">
      <h1>
        <a href="/" title="Snow Capture" class="main-link" tabindex="1">
          Snow Capture               
        </a>
      </h1>
      
      <div class="loading">
        <div class="loader">
          <div class="loader-inner ball-scale-ripple">
            <div></div>
          </div>
        </div>
      </div>
    </div>

    <div class="header-links <?php echo ! $sc->session->exists() ? 'guest' : ''; ?>">
      <ul>
    <?php

      // Display header for logged in users 
      // IsUser 
      if($sc->session->exists()):
      
      
    ?>
        
        <li class="pull-right">
          <a href="/" class="user-icon main-link toggle-user-search pull-right" data-tooltip data-placement="bottom" data-delay="500" title="Search" tabindex="5">
             <span><i class="fa fa-search fa-2x"></i></span>
          </a>          
          <form method="get" action="/search" class="pull-right">
            <input class="user-search"  value="<?php echo $sc->common->keyword() ? $sc->common->keyword() : ''; ?>" type="text" autocomplete="off" name="q" placeholder="Search something..." maxlength="50" />
          </form>
        </li><!-- .btn-group --> 
    
        <li class="pull-right">
        	<div class="sc-dropdown-container" data-dir-right>
            <a href="#" id="open-notifications" class="main-link" data-dropdown="#notification-dashboard" data-tooltip data-placement="bottom" data-delay="500" title="Notifications" tabindex="5">
               <span><i class="fa fa-bell-o fa-2x"></i><i class="fa fa-bell fa-2x"></i></span> <span id="notification-count">0</span>
            </a>
            <ul id="notification-dashboard" class="sc-dropdown notification-dashboard animate" data-width="250"></ul>
					</div>
        </li><!-- .btn-group --> 
    
        <li class="margin-right-xs pull-right">
        	<div class="sc-dropdown-container" data-dir-right>
            <a href="#" class="main-link user-pic" data-dropdown="#user-links" data-tooltip data-placement="bottom" data-delay="500" title="Menu" tabindex="4">
               <?php if (strpos($sc->user->picture, 'placeholder')): ?>
               <span><i class="fa fa-user"></i></span>
               <?php else: ?>
               <img src="<?php echo $sc->user->picture; ?>" alt="<?php echo $sc->user->displayName; ?>" />
               <?php endif; ?>
            </a> 
            <ul id="user-links" class="sc-dropdown animate" data-width="160">
              <?php include('user_links.php'); ?>
            </ul> 
          </div>       
        </li>

   <?php 
       
      // Else NotUser
      else:

    ?>

        <li class="pull-right">

      <?php

        // IsPageTypeSignIn
        if($sc->vars->pageType == 'sign-in'):

      ?>
          <a href="#sign-in" class="main-link login-local" title="Sign In" tabindex="5">
            Sign In
          </a>   
      <?php
        
        // Else NotPageTypeSignIn
        else:

      ?>  
      		<div class="sc-dropdown-container" data-dir-right>  
            <a href="/sign-in?url=<?php echo "$_SERVER[REQUEST_URI]"; ?>" class="user-icon main-link sign-in-dashboard" data-dropdown="#sign-in-dashboard" title="Sign In" tabindex="6">
              Sign In
            </a>         
  
            <div id="sign-in-dashboard" class="sc-dropdown sign-dashboard non-user animate custom-style" data-width="300">
              <?php include(WEB_ROOT . 'content/account/login.php'); ?>
            </div>
          </div>
      <?php

        // End IsPageTypeSignIn
        endif;

      ?>     
        </li>


        <li class="pull-right">
      <?php

        // IsPageTypeSignUp
        if($sc->vars->pageType == 'sign-up'):

      ?>

          <a href="#register" class="main-link margin-right margin-left register-local" tabindex="5">
            Sign Up
          </a>   

      <?php
        
        // Else NotPageTypeSignUp
        else:

      ?>  
      
          <a href="/sign-up" class="main-link margin-right margin-left" tabindex="5">
            Sign Up
          </a>  
      
      <?php

        // End IsPageTypeSignUp
        endif;

      ?>     
        </li>


    <?php

      // End IsUser
      endif;

    ?>        
        
        
      </ul>
    </div><!-- .header-links -->
  
 <?php 
 
   // IsNotUser
   if(!$sc->session->exists()): 
   
 ?>
 
      <div class="header-search margin-right <?php if($sc->session->exists()) { if ($sc->user->status < 0) { echo 'hidden'; } } ?>">
        <form id="search-form" class="form search-icon" action="/search">
          <div id="keyword-holder">
            <input id="header-search-input" class="search-input"  value="<?php echo $sc->common->keyword() ? $sc->common->keyword() : ''; ?>" type="text" name="q" placeholder="Search something..." maxlength="50" tabindex="2" />
            <button type="submit" tabindex="3" class="search-btn"><i class="fa fa-search"></i></button>
          </div><!-- #keyword-holder -->
        </form>
      </div><!-- .header-search -->     
  
<?php 
  
  // End IsNotUser
  endif;

?>    
  </div><!-- .container -->  
</header>