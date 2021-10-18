<?php

declare(strict_types=1);

use Site\Core\Service\TcaService;

// Palette: containerDefault (for EXT:container)
TcaService::addPalette(
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

// Pre-defined palettes to be used in containers
// using 1 to 4 columns
TcaService::addPalette(
    'txContainerRecords1Col',
    '
        containerDefault,
        tx_container_records_colpos_10,
    ',
    [
        'label' => 'Container 1-Column',
    ]
);

TcaService::addPalette(
    'txContainerRecords2Col',
    '
        containerDefault,
        tx_container_records_colpos_10,
        tx_container_records_colpos_20,
    ',
    [
        'label' => 'Container 2-Column',
    ]
);

TcaService::addPalette(
    'txContainerRecords3Col',
    '
        containerDefault,
        tx_container_records_colpos_10,
        tx_container_records_colpos_20,
        tx_container_records_colpos_30,
    ',
    [
        'label' => 'Container 3-Column',
    ]
);
TcaService::addPalette(
    'txContainerRecords4Col',
    '
        containerDefault,
        tx_container_records_colpos_10,
        tx_container_records_colpos_20,
        tx_container_records_colpos_30,
        tx_container_records_colpos_40,
    ',
    [
        'label' => 'Container 4-Column',
    ]
);
