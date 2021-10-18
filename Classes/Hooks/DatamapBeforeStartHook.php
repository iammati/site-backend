<?php

declare(strict_types=1);

namespace Site\SiteBackend\Hooks;

use B13\Container\Hooks\Datahandler\DatamapBeforeStartHook as B13DatamapBeforeStartHook;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\MathUtility;

/**
 * This overrides b13/container delivered DatamapBeforeStart hook
 * since it is bz default setting colPos to 0 instead of reading
 * the actual value from the db-row which is done here.
 */
class DatamapBeforeStartHook extends B13DatamapBeforeStartHook
{
    protected function extractContainerIdFromColPosInDatamap(array $datamap): array
    {
        if (!empty($datamap['tt_content'])) {
            foreach ($datamap['tt_content'] as $id => &$data) {
                if (isset($data['colPos'])) {
                    $colPos = $data['colPos'];
                    if (MathUtility::canBeInterpretedAsInteger($colPos) === false) {
                        [$containerId, $newColPos] = GeneralUtility::intExplode('-', $colPos);
                        $data['colPos'] = $newColPos;
                        $data['tx_container_parent'] = $containerId;
                    } elseif (!isset($data['tx_container_parent'])) {
                        $dbRow = $this->getRawTtcontentByUid($id);
                        $data['tx_container_parent'] = $dbRow['tx_container_parent'];
                        $data['colPos'] = (int)$colPos;
                    }
                }
            }
        }
        return $datamap;
    }

    protected function getRawTtcontentByUid(int $uid)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');

        return $queryBuilder
            ->select('*')
            ->from('tt_content')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid), \PDO::PARAM_INT)
            )
        ->execute()
        ->fetchAssociative();
    }
}
