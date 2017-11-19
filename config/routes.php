<?php
$routes = ["index.php" => "controller/index.controller.php",
'logo.png' => 'views/partials/logo.png',
"/" => "controller/index.controller.php",
"home" => "controller/home.controller.php",
'login' => "views/login.view.html",
'logout' => "controller/logout.controller.php",
'log' => 'controller/login.controller.php',
'admin' => 'controller/admin.controller.php',
'signup' => 'controller/signup.controller.php',
'login.css' => 'views/partials/css/login.css',
'login.js' => 'views/partials/js/login.js',
'admin.css' => "views/partials/css/admin.css",
'admin.js' => "views/partials/js/admin.js",
'send' => "controller/send_msg.controller.php",
'recents' => 'controller/recent_msg.controller.php',
'favicon.ico' => 'favicon.ico',
"404" => "views/error404.view.html"
];

return $routes;
