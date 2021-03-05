<?php

use Site\Core\Service\TCAService;

defined('TYPO3_MODE') || die('Access denied.');

(function () {
    $customerProject = env('CUSTOMER_PROJECT');

    TCAService::allowTablesStartsWith('tx_'.$customerProject);
})();
