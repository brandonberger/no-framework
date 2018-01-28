<?php declare(strict_types = 1);

return [
    ['GET', '/', ['Underground\Controllers\Homepage', 'show']],
    ['GET', '/{slug}', ['Underground\Controllers\Page', 'show']],
];
