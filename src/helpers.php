<?php

$baseDir = "foundation/src/Illuminate/Foundation/helpers.php";

if (file_exists(__DIR__."/../../$baseDir")) {
    require __DIR__."/../../$baseDir";
} else if (file_exists(__DIR__."/../vendor/laravel-zero/$baseDir")) {
    require __DIR__."/../vendor/laravel-zero/$baseDir";
} else {
    require __DIR__."/../../../laravel-zero/$baseDir";
}
