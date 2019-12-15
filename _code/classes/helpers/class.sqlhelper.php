<?php

/**
 * Helper object for storing SQL commands
 * 
 * @author Robert Holden
 */
class SQLHelper extends Query
{




  /**
   * Prepares user select from
   */
  public function prepareUser()
  {
    $this->select('user.*');
    $this->select('options.enable_emails');
    $this->select('options.send_likes');
    $this->select('options.send_processing');
    $this->select('options.upload_geo');
    $this->select('limits.drafts as draft_limit');
    $this->select('limits.uploads as upload_limit');
    $this->select('(SELECT file FROM sc_images WHERE id = user.picture) as picture');
    $this->select('(SELECT file FROM sc_images WHERE id = user.picture_cover) as picture_cover');
    $this->select('(SELECT COUNT(*) FROM sc_images WHERE user_id = user.id AND status = ' . IMAGE_PUBLISHED . ') as active');
    $this->select('(SELECT COUNT(*) FROM sc_images WHERE user_id = user.id AND status = ' . IMAGE_PRIVATE . ') as privates');
    $this->select('(SELECT COUNT(*) FROM sc_images WHERE user_id = user.id AND status = ' . IMAGE_PROCESSING . ') as processing');
    $this->select('(SELECT COUNT(*) FROM sc_images WHERE user_id = user.id AND status = ' . IMAGE_DRAFT . ') as drafts');
    $this->select('(SELECT COUNT(*) FROM sc_image_likes likes WHERE likes.user_id = user.id AND (SELECT image.status FROM sc_images image WHERE image.id = likes.image_id) = ' . IMAGE_PUBLISHED . ') as likes');
    $this->select('(SELECT salt FROM sc_user_salts WHERE user_id = user.id) as salt');
    $this->select('(SELECT COUNT(*) FROM sc_notifications WHERE user_to_id = user.id AND deleted = 0) as notif_count');
    $this->select('(SELECT COUNT(*) FROM sc_notifications WHERE user_to_id = user.id AND deleted = 0 AND status = ' . NOTIFICATION_CREATED . ') as notif_count_new');
    
    $this->from('sc_users user');
    $this->from('LEFT OUTER JOIN sc_user_options options ON options.user_id = user.id');
    $this->from('LEFT OUTER JOIN sc_user_limits limits ON limits.user_id = user.id');
  }




  /**
   * Prepares image select
   */
  public function prepareImage()
  {
    $this->select('image.*');
    $this->select('image.latitude as image_latitude, image.longitude as image_longitude');
    $this->select('resort.name as resort_name, resort.id as tbl_resort_id');
    $this->select('resort.country_id as tbl_resort_country_id');
    $this->select('resort.latitude as resort_latitude, resort.longitude as resort_longitude');
    $this->select('country.name as country_name, country.id as tbl_country_id');
    $this->select('country.latitude as country_latitude, country.longitude as country_longitude');
    $this->select('altitude.display_height as altitude, altitude.id as tbl_altitude_id');
    $this->select('activity.type as activity, activity.id as tbl_activity_id');
    $this->select('user.id as userid, user.status as userstatus');
    $this->select('IFNULL((SELECT ROUND((SUM(value) / COUNT(id)), 1) FROM sc_image_ratings WHERE image_id = image.id), 0) AS rating');
    $this->select('(SELECT COUNT(id) FROM sc_image_likes WHERE image_id = image.id) AS likes');
    
    $this->from('sc_images image');
    $this->from('LEFT OUTER JOIN sc_resorts resort ON resort.id = image.resort_id');
    $this->from('LEFT OUTER JOIN sc_countries country ON country.id = image.country_id');
    $this->from('LEFT OUTER JOIN sc_altitudes altitude ON altitude.id = image.altitude_id');
    $this->from('LEFT OUTER JOIN sc_activities activity ON activity.id = image.activity_id');
    $this->from('LEFT OUTER JOIN sc_users user ON user.id = image.user_id');
  }


  
  public function save($obj)
  {
    $this->insertUpdate('sc_' . strtolower(get_class($obj)) , ! $obj->exists());
    
    foreach ($obj as $key => $value)
    {
      $field = strtolower(preg_replace('/([A-Z])/', '_$1', $key));
      if ($field != 'id' && $value != null)
      {
        $this->values($field, $obj->$key);
      }
    }
    
    // Always presume id is present
    $this->whereParam('id', '=', $obj->id);
    
   // Execute
   return $this->execute();
  }
  
/**
 * Gets data row from datastore
 * @param integer $id
 * @param object $obj
 * @return boolean
 */
  public function get($id, $obj)
  {
    // Validate id
    if (! is_numeric($id))
    {
      return false;
    }    
    
    // Get row
    $this->select('*');
    $this->from('sc_' . strtolower(get_class($obj)));
    $this->whereParam('id', '=', $id);
    
    // Prepare limit
    $this->limit(1);
    
    // Execute query
    $this->execute();
    
    // Make sure there is data
    if (! $this->hasRows())
    {
      return false;
    }
    
    (new Functions)->assign($this->fetchArray(), $obj);
  }


  /**
   * Destruct
   */
  function __destruct()
  {}
}