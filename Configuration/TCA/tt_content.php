<?php

// Registration of the Backend Previews
// - will be called when EXT:site_backend/Configuration/Config.php:Backend.Preview.enabled is set to true
Site\Core\Service\TCAService::registerBackendPreviews(__DIR__ . '/Overrides/');
