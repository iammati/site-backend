<?php

declare(strict_types=1);

use Site\Core\Service\TCAService;

// Palette: containerDefault (for EXT:container)
TCAService::addPalette(
    'containerDefault',

    '
        containerIsFluid;,
        containerSpaceBefore;,
        containerSpaceAfter;,
    ',

    [
        'label' => 'Configuration',
    ]
);
