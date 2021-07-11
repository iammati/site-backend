<?php

use Site\Core\Form\Fields\File;
use Site\Core\Form\Fields\Image;
use Site\Core\Form\Fields\Inline;
use Site\Core\Form\Fields\InlineItem;
use Site\Core\Form\Fields\Input;
use Site\Core\Form\Fields\RTE;

return Inline::make('Accordion', [
    'label' => 'rte',

    'columns' => [
        'header' => Input::make('Header'),
        'rte' => RTE::make('RTE'),
        'file' => File::make('File', [
            'fieldName' => 'file'
        ]),
        'image' => Image::make('Image', [
            'fieldName' => 'image'
        ]),
        'subaccords' => InlineItem::make('sub accordsss', [
            'config' => [
                'foreign_table' => 'tx_sitebackend_domain_model_accordions'
            ]
        ]),
    ],
]);
