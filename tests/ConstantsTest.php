<?php

declare(strict_types=1);

it('sets the artisan binary constant', function () {
    expect(ARTISAN_BINARY)->toEqual('application');
});
