<?php

declare(strict_types=1);

namespace Tests;

use LaravelZero\Framework\Components\Logo\FigletString;

final class LogoProviderTest extends TestCase
{
    public function testLogoCanBeGenerated(): void
    {
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

        $this->assertEquals($logo, (string) $figlet);
    }
}
