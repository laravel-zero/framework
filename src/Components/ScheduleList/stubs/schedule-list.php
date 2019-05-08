<?php

return [
    'remove_strings_from_command' => [
        "'".PHP_BINARY."'",
        "'".strtolower(config('app.name'))."'",
    ],
];
