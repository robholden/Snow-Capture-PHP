<?php

interface IFilter 
{
  /**
   * Returns array from url, or false if not set
   * Seperated by '|'
   *
   * @return <boolean, array>
   */
  public function current();
}