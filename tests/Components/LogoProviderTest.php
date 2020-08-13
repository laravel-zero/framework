<?php

declare(strict_types=1);

use LaravelZero\Framework\Components\Logo\FigletString;

it('can generate the application logo', function () {
    $logo = <<<LOGO

                      _ _           _   _             
    /\               | (_)         | | (_)            
   /  \   _ __  _ __ | |_  ___ __ _| |_ _  ___  _ __  
  / /\ \ | '_ \| '_ \| | |/ __/ _` | __| |/ _ \| '_ \ 
 / ____ \| |_) | |_) | | | (_| (_| | |_| | (_) | | | |
/_/    \_\ .__/| .__/|_|_|\___\__,_|\__|_|\___/|_| |_|
         | |   | |                                    
         |_|   |_|                                    


LOGO;

    $figlet = new FigletString('Application', []);

    expect((string) $figlet)->toEqual($logo);
});
