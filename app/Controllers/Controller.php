<?php

namespace App\Controllers;

use Interop\Container\ContainerInterface;

abstract class Controller {

  protected $c;

  public function __construct(\Interop\Container\ContainerInterface $c) {

    $this->c = $c;

  }

}

?>
