<?php

use Site\Core\Service\TCAService;

defined('TYPO3_MODE') || die('Access denied.');

(function () {
    $customerProject = getenv('CUSTOMER_PROJECT');

    TCAService::allowTablesStartsWith('tx_'.$customerProject);
})();
