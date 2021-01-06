<?php

defined('TYPO3_MODE') || die('Access denied.');

use Site\Core\Service\TCAService;

TCAService::showFields(basename(__FILE__, '.php'), '
    irre_accordions_item,
');
