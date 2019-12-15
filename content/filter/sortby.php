<div class="sc-dropdown-container" data-dir-right>
  <a href="#" id="open-notifications" class="sortby-dropdown filter-icon" data-dropdown="#sort-dashboard">
     Sort By <span id="sort-text" class="sort-text"><?php echo $sc->vars->pageType == 'search' ? 'Random' : 'Date Uploaded (Newest)'; ?></span>
  </a>
  
  <ul id="sort-dashboard" class="sc-dropdown animate" data-width="250">
  	<li class="separator"><span>Date Uploaded</span></li>
  	<li>
  		<a href="#" data-sort="uploaded-desc" class="notification-link toggle-sort <?php echo $sc->vars->pageType != 'search' ? 'active' : ''; ?>">
        Newest
      </a>
  	</li>
  	<li>
  		<a href="#" data-sort="uploaded-asc" class="notification-link toggle-sort">
        Oldest
      </a>
  	</li>
  	
  	<li class="separator"><span>Date Taken</span></li>
  	<li>
  		<a href="#" data-sort="taken-desc" class="notification-link toggle-sort">
        Newest
      </a>
  	</li>
  	<li>
  		<a href="#" data-sort="taken-asc" class="notification-link toggle-sort">
        Oldest
      </a>
  	</li>
  	
  	<li class="separator"><span>Rating</span></li>
  	<li>
  		<a href="#" data-sort="rating-desc" class="notification-link toggle-sort">
        Highest
      </a>
  	</li>
  	<li>
  		<a href="#" data-sort="rating-asc" class="notification-link toggle-sort">
        Lowest
      </a>
  	</li>
  	
  	<li class="separator"><span>Likes</span></li>
  	<li>
  		<a href="#" data-sort="likes-desc" class="notification-link toggle-sort">
        Highest
      </a>
  	</li>
  	<li>
  		<a href="#" data-sort="likes-asc" class="notification-link toggle-sort">
        Lowest
      </a>
  	</li>
  	
		<li class="separator"><span>Other</span></li>
  	<li>
  		<a href="#" data-sort="random" class="notification-link toggle-sort <?php echo $sc->vars->pageType == 'search' ? 'active' : ''; ?>">
        Random
      </a>
  	</li>
  </ul>
</div>