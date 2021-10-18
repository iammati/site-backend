<?php

declare(strict_types=1);

$fields = [];
$modelFields = Site\Core\Helper\ConfigHelper::get(env('BACKEND_EXT'), 'Fields.Ttcontent');

foreach ($modelFields as $typeCast => $typeCastFields) {
    foreach ($typeCastFields as $name => $value) {
        $fields[] = $name;
    }
}

$properties = [];

foreach ($fields as $field) {
    $properties[$field] = [
        'fieldName' => $field,
    ];
}

return [
    \Site\SiteBackend\Domain\Model\Ttcontent::class => [
        'tableName' => 'tt_content',
        'properties' => $properties,
    ],
];
