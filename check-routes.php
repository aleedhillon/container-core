<?php

require_once __DIR__ . '/public/index.php';

var_dump(json_encode($router->getRoutes(), JSON_PRETTY_PRINT));