<?php

// Get post values we need
$_password = $api->checkPost('password');
$_session = $api->checkPost('session_id');

// Validate password
if (! $_password)
{
  $api->data = array(
      'error' => 'Please enter your password'
  );
  $api->leave();
}

// Validate session
if (! $_session)
{
  $api->data = array(
      'error' => 'Could not find session'
  );
  $api->leave();
}

// Encrypt password
$_password = (new Session())->saltData($api->user(), $_password);

// Are they the same?
if ($_password !== $api->user()->password())
{
  $api->data = array(
      'error' => 'Incorrect password, please try again'
  );
  $api->leave();
}

// Let's start deleting
$_sessions = explode(',', $_session);
for ($i = 0; $i < sizeof($_sessions); $i++)
{
  $_sess = new Session((new Security())->decrypt($_sessions[$i]));

  // Make sure the session exists, if not then job done
  if ($_sess->exists())
  {
    
    // Make sure the users' match
    if ($_sess->user->id !== $api->user()->id)
    {
      (new Security())->addToNaughtyList(1);
    }
    
    else 
    {
      // Now we can delete the session :)
      $_sess->delete();
    }
  }
}

$api->data = array(
    'success' => 'Session ended successfully',
    'ids' => $_sessions
);