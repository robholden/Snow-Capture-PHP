<?php

/**
 * Store all structures
 * 
 * @author Robert Holden
 */
class Attempt
{
  public $date;
  public $id;
  public $ip;
  public $status;
  public $url;
}

class EmailLog
{
  public $date;
  public $id;
  public $subject;
  public $username;
}

class ResortRequest
{
  public $id;
  public $resort;
  public $username;
}

class UserLimits
{
  public $drafts = DRAFT_LIMIT;
  public $uploads = UPLOAD_LIMIT;
}

class UserOptions
{
  public $enableEmails = true;
  public $sendLikes = true;
  public $sendProcessing = true;
  public $uploadGeo = true;
}

class UserReport
{
  public $comment;
  public $date;
  public $id;
  public $reportedID;
  public $title;
  public $type;
  public $username;
}

class UserNotifications
{
  public $count = 0;
  public $new = 0;
  public $hasNew = false;
}