<?php  

// INITIALISATION FROM /content/views/image-display.php :) 
$locations = $sc->common->location();
$dates = $sc->common->taken();
$activities = $sc->common->activity();
$altitudes = $sc->common->altitude();
$tags = $sc->common->tag();

$locations = !$locations ? array() : $locations;
$dates = !$dates ? array() : $dates;
$activities = !$activities ? array() : $activities;
$altitudes = !$altitudes ? array() : $altitudes;
$tags = !$tags ? array() : $tags;

$selected = false;

?>

<div class="row clear <?php echo ! $sc->common->isMobile ? 'as-dropdown' : ''; ?>">
  
  <!-- Tags -->
  <div class="col-md-3 col-sm-6 margin-bottom">
      <div class="filter-dropdown">
      <label class="filter-dropdown-toggle">
        <input id="tag-input" type="text" class="filter-input-tag" placeholder="Tag" />
        <i class="fa fa-tag pull-right"></i>
        <i class="fa ion-load-c fa-spin pull-right" style='display: none'></i>
      </label>
      
      <div id="tag-filter-values" class="filter-values">
      </div>
    </div>

    <div class="filter-choose" data-type="tag">
<?php 
  foreach($tags as $tag)
  {
    echo '
      <p class="filter-chosen" data-value="' . trim($tag) . '">
        <i class="fa fa-tag margin-right-xs"></i>' . trim($tag) . '
        <a href="#" class="fa fa-times pull-right filter-chosen-remove"></a>
      </p>
    ';
  }
?>
    </div>
  </div><!-- .filter-group col-xs-12 col-sm-3 -->


<?php if ($sc->vars->pageType != 'location'): ?>

  <div class="clear"></div>

  <!-- Locations -->
  <div id="locations" class="col-md-3 col-sm-6 margin-bottom">
    <div class="filter-dropdown">
      <label class="filter-dropdown-toggle">
        <input type="text" class="filter-input" placeholder="Location" />
        <i class="fa fa-map-marker pull-right"></i>
      </label>
      <div class="filter-values">
<?php 
    
  //$all_countries = (new Country)->getFromImages();
  $all_countries = (new Country)->getAll();
  foreach ($all_countries as $key => $country)
  {
    $selected = in_array(utf8_decode($country->name), $locations) ? 'is-chosen' : '';
    echo  '<a href="#" class="filter-value '. $selected . '" data-value="' . utf8_decode($country->name) . '">'
            . utf8_decode($country->name) . 
          '</a>';
    
    // Select country's resorts
    $all_resorts = (new Resort)->getByCountry($country->id);
    if ($all_resorts)
    {
      foreach ($all_resorts as $resort)
      {
        $selected = in_array(utf8_decode($resort->name), $locations) ? 'is-chosen' : '';
        echo  '<a href="#" class="filter-value inner-value ' . $selected . '" data-value="' . utf8_decode($resort->name) . '" data-parent="' . utf8_decode($country->name) . '">
                <i class="fa fa-map-pin icon-left"></i> ' . utf8_decode($resort->name) . 
              '</a>';
      }
    }
  }
    
?>
      </div>
    </div>
    
    <div class="filter-choose" data-type="location"></div>
  </div><!-- .filter-group col-xs-12 col-sm-3 -->
	
<?php endif; ?>
	
  
  
  <!-- Activities -->
  <div id="activities" class="col-md-3 col-sm-6 margin-bottom">
    <div class="filter-dropdown">
      <label class="filter-dropdown-toggle">
        <input type="text" class="filter-input" placeholder="Activity" />
        <i class="fa fa-rocket pull-right"></i>
      </label>
      <div class="filter-values">
<?php 
    
  $all_activities = (new Activity)->getAll();
  foreach ($all_activities as $key => $activity)
  {
    $selected = in_array($activity->type, $activities) ? 'is-chosen' : '';
    echo '<a href="#" class="filter-value '. $selected . '" data-value="' . $activity->type . '">' . ucwords($activity->type) . '</a>';
  }
    
?>
      </div>
    </div>
    
    <div class="filter-choose" data-type="activity"></div>
  </div><!-- .filter-group col-xs-12 col-sm-3 -->  
    
  
  
  
  
  <!-- Altitudes -->
  <div id="altitudes" class="col-md-3 col-sm-6 margin-bottom">
    <div class="filter-dropdown">
      <label class="filter-dropdown-toggle">
        <input type="text" class="filter-input" placeholder="Altitude" />
        <i class="fa fa-line-chart pull-right"></i>
      </label>
      <div class="filter-values">
<?php 
    
  $all_altitudes = (new Altitude)->getAll();
  foreach ($all_altitudes as $key => $altitude)
  {
    $selected = in_array($altitude->height, $altitudes) ? 'is-chosen' : '';
    echo '<a href="#" class="filter-value '. $selected . '" data-value="' . $altitude->height . '">' . ucwords($altitude->displayHeight) . '</a>';
  }
    
?>
      </div>
    </div>
    
    <div class="filter-choose" data-type="altitude"></div>
  </div><!-- .filter-group col-xs-12 col-sm-3 -->





  <!-- Dates -->
  <div id="dates" class="col-md-3 col-sm-6 margin-bottom">
    <div class="filter-dropdown">
      <label class="filter-dropdown-toggle">
        <input type="text" class="filter-input" placeholder="Year Taken" />
        <i class="fa fa-clock-o pull-right"></i>
      </label>
      <div class="filter-values">
<?php 
    
  $years = array_reverse(range(1910, date("Y")));
  foreach ($years as $key => $year)
  {
    $selected = in_array($year, $dates) ? 'is-chosen' : '';
    echo '<a href="#" class="filter-value '. $selected . '" data-value="' . $year . '">' . $year . '</a>';
  }
    
?>
      </div>
    </div>
    
    <div class="filter-choose" data-type="taken"></div>
  </div><!-- .filter-group col-xs-12 col-sm-3 -->    
  
  
  <div class="clear"></div>
</div><!-- .row -->




