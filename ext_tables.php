<?php

use Site\Core\Service\TCAService;

(function () {
    $customerProject = env('CUSTOMER_PROJECT');

    TCAService::allowTablesStartsWith('tx_'.$customerProject);
})();
