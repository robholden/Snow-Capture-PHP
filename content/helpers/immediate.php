<?php

if ($sc->session->exists())
{
  // Disabled?
  if ($sc->user->isDisabled())
  {
    $sc->session->logout();
  }
}

if ($sc->session->exists() && $sc->vars->pageType !== 'timeout')
{
  // Timeout?
  if ($sc->session->isTimedOut())
  {
    $url = "$_SERVER[REQUEST_URI]";
    $sc->common->goToURL('/timeout?url=' . $url);
  }
}

// Update session last active date
$sc->session->isActive();

?>