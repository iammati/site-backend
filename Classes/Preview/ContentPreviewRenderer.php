<?php

declare(strict_types=1);

namespace Site\SiteBackend\Preview;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Site\Core\Helper\ConfigHelper;
use Site\Core\Service\BackendUserService;
use Site\Core\Utility\ExceptionUtility;
use Site\Frontend\Page\Rendering\CTypeRenderer;
use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Http\ApplicationType;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

class ContentPreviewRenderer implements PreviewRendererInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function renderPageModulePreviewHeader(GridColumnItem $item): string
    {
        $record = $item->getRecord();
        $itemLabels = $item->getContext()->getItemLabels();

        $outHeader = '';

        if ($record['header']) {
            $infoArr = [];
            $this->getProcessedValue($item, 'header_position,header_layout,header_link', $infoArr);
            $hiddenHeaderNote = '';

            // If header layout is set to 'hidden', display an accordant note:
            if (100 == $record['header_layout']) {
                $hiddenHeaderNote = ' <em>[' . htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.6')) . ']</em>';
            }

            $langCode = '';

            if (ApplicationType::fromRequest(serverRequest())->isBackend()) {
                $langCode = GeneralUtility::makeInstance(BackendUserService::class)->getUser()->uc['lang'] ?? 'en';
            }

            $identifier = 'Backend.ContentElements:' . str_replace('ce_', '', $record['CType']);
            $header = ll(env('BACKEND_EXT'), $identifier, $langCode)['title'] ?? $record['header'];

            $outHeader = $record['date'] ? htmlspecialchars($itemLabels['date'] . ' ' . BackendUtility::date($record['date'])) . '<br />' : '';
            $outHeader .= '<strong>' . $this->linkEditContent($this->renderText($header), $record) . $hiddenHeaderNote . '</strong><br />';
        }

        return $outHeader;
    }

    public function renderPageModulePreviewContent(GridColumnItem $item): string
    {
        $record = $item->getRecord();

        return $this->renderContentElementPreviewFromFluidTemplate($record);
    }

    public function renderPageModulePreviewFooter(GridColumnItem $item): string
    {
        return '';
    }

    public function wrapPageModulePreview(string $previewHeader, string $previewContent, GridColumnItem $item): string
    {
        $content = '<span class="exampleContent">' . $previewHeader . $previewContent . '</span>';
        if ($item->isDisabled()) {
            return '<span class="text-muted">' . $content . '</span>';
        }

        return $content;
    }

    protected function renderContentElementPreviewFromFluidTemplate(array $row): ?string
    {
        $backendExt = env('BACKEND_EXT');

        $extKey = ConfigHelper::get($backendExt, 'Backend.Preview.extKey');
        $templateRootPaths = ConfigHelper::get($backendExt, 'Backend.Preview.templateRootPaths');

        if (null === $extKey) {
            ExceptionUtility::throw(
                'Backend-Preview could not be rendered since there is no configured Backend.Preview.extKey in EXT:' . $backendExt . '/Configuration/Config.php'
            );
        }

        if (null === $templateRootPaths) {
            ExceptionUtility::throw(
                'Backend-Preview could not be rendered since there is no configured Backend.Preview.templateRootPaths in EXT:' . $backendExt . '/Configuration/Config.php'
            );
        }

        $rootPathsIdentifier = 'ContentElements.rootPaths';

        if ($row['is_irre']) {
            $rootPathsIdentifier = 'ContentElements.rootPaths.IRREs';
        }

        $CType = ucfirst(str_replace('ce_', '', $row['CType']));
        $header = "<p><b>{$CType}</b></p>";

        if (!$row['backendRenderPreview']) {
            return $header;
        }

        $renderingRootPaths = ConfigHelper::get(env('FRONTEND_EXT'), $rootPathsIdentifier);

        /** @var CTypeRenderer */
        $CTypeRendering = GeneralUtility::makeInstance(CTypeRenderer::class);
        $CTypeRendering->cObj = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $CTypeRendering->cObj->renderingRootPaths = $renderingRootPaths;
        $CTypeRendering->cObj->data = $row;
        $CTypeRendering->cObj = $CTypeRendering->renderingEvent($CType, 'beforeRendering');

        return $header . $CTypeRendering->render();
    }

    protected function getProcessedValue(GridColumnItem $item, string $fieldList, array &$info): void
    {
        $itemLabels = $item->getContext()->getItemLabels();
        $record = $item->getRecord();

        $fieldArr = explode(',', $fieldList);

        foreach ($fieldArr as $field) {
            if ($record[$field]) {
                $info[] = '<strong>' . htmlspecialchars((string) ($itemLabels[$field] ?? '')) . '</strong> '
                    . htmlspecialchars(BackendUtility::getProcessedValue('tt_content', $field, $record[$field]) ?? '');
            }
        }
    }

    /**
     * Create thumbnail code for record/field but not linked.
     *
     * @param mixed[] $row   Record array
     * @param string  $table Table (record is from)
     * @param string  $field field name for which thumbnail are to be rendered
     *
     * @return string HTML for thumbnails, if any
     */
    protected function getThumbCodeUnlinked($row, $table, $field): string
    {
        return BackendUtility::thumbCode($row, $table, $field, '', '', null, 0, '', '', false);
    }

    /**
     * Processing of larger amounts of text (usually from RTE/bodytext fields) with word wrapping etc.
     *
     * @param string $input Input string
     *
     * @return string Output string
     */
    protected function renderText(string $input): string
    {
        $input = strip_tags($input);
        $input = GeneralUtility::fixed_lgd_cs($input, 1500);

        return nl2br(htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8', false));
    }

    /**
     * Will create a link on the input string and possibly a big button after the string which links to editing in the RTE.
     * Used for content element content displayed so the user can click the content / "Edit in Rich Text Editor" button.
     *
     * @param string $linkText String to link. Must be prepared for HTML output.
     * @param array  $row      the row
     *
     * @return string If the whole thing was editable $str is return with link around. Otherwise just $str.
     */
    protected function linkEditContent(string $linkText, $row): string
    {
        $backendUser = $this->getBackendUser();
        if ($backendUser->check('tables_modify', 'tt_content') && $backendUser->recordEditAccessInternals('tt_content', $row)) {
            $urlParameters = [
                'edit' => [
                    'tt_content' => [
                        $row['uid'] => 'edit',
                    ],
                ],
                'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI') . '#element-tt_content-' . $row['uid'],
            ];
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $url = (string) $uriBuilder->buildUriFromRoute('record_edit', $urlParameters);

            return '<a href="' . htmlspecialchars($url) . '" title="' . htmlspecialchars($this->getLanguageService()->getLL('edit')) . '">' . $linkText . '</a>';
        }

        return $linkText;
    }

    /**
     * @return BackendUserAuthentication
     */
    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    /**
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    /**
     * @return IconFactory
     */
    protected function getIconFactory(): IconFactory
    {
        return GeneralUtility::makeInstance(IconFactory::class);
    }
}
