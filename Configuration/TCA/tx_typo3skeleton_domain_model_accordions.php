<?php

defined('TYPO3_MODE') || die('Access denied.');

use Site\Core\Form\Fields\Input;
use Site\Core\Form\Fields\RTE;
use Site\Core\Service\TCAService;

return TCAService::findConfigByType('Inline', basename(__FILE__, '.php'), '', [
    // Text of an IRRE as a preview of the Inline-Record item / accordion in the BE
    'label' => 'rte',

    // Create new <title>
    'title' => 'Accordion',

    'columns' => [
        'header' => Input::make('Header'),
        'rte' => RTE::make('RTE'),
    ],
]);
