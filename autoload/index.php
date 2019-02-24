<?php
include "Loader.php";
spl_autoload_register(['Loader', 'autoload']);

new App\Mvc\View\Index();