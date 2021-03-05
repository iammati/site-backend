<?php

declare(strict_types=1);

namespace Site\Backend\Preview;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Site\Core\Helper\ConfigHelper;
use Site\Core\Utility\ExceptionUtility;
use TYPO3\CMS\Backend\Preview\PreviewRendererInterface;
use TYPO3\CMS\Backend\Preview\StandardContentPreviewRenderer;
use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\View\BackendLayout\Grid\GridColumnItem;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Class StandardContentPreviewRenderer.
 *
 * Legacy preview rendering refactored from PageLayoutView.
 * Provided as default preview rendering mechanism via
 * StandardPreviewRendererResolver which detects the renderer
 * based on TCA configuration.
 *
 * Can be replaced and/or subclassed by custom implementations
 * by changing this TCA configuration.
 *
 * See also PreviewRendererInterface documentation.
 */
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
            if ($record['header_layout'] == 100) {
                $hiddenHeaderNote = ' <em>['.htmlspecialchars($this->getLanguageService()->sL('LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:header_layout.I.6')).']</em>';
            }

            $identifier = 'Backend.ContentElements:'.str_replace('ce_', '', $record['CType']);
            $header = ll(env('BACKEND_EXT'), $identifier)['title'] ?? $record['header'];

            $outHeader = $record['date'] ? htmlspecialchars($itemLabels['date'].' '.BackendUtility::date($record['date'])).'<br />' : '';
            $outHeader .= '<strong>'.$this->linkEditContent($this->renderText($header), $record).$hiddenHeaderNote.'</strong><br />';
        }

        return $outHeader;
    }

    protected function renderContentElementPreviewFromFluidTemplate(array $row): ?string
    {
        $backendExt = env('BACKEND_EXT');

        $extKey = ConfigHelper::get($backendExt, 'Backend.Preview.extKey');
        $templateRootPaths = ConfigHelper::get($backendExt, 'Backend.Preview.templateRootPaths');

        if ($extKey === null) {
            ExceptionUtility::throw(
                'Backend-Preview could not be rendered since there is no configured Backend.Preview.extKey in EXT:'.$backendExt.'/Configuration/Config.php'
            );
        }

        if ($templateRootPaths === null) {
            ExceptionUtility::throw(
                'Backend-Preview could not be rendered since there is no configured Backend.Preview.templateRootPaths in EXT:'.$backendExt.'/Configuration/Config.php'
            );
        }

        $CType = ucfirst(str_replace('ce_', '', $row['CType']));

        $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:'.$extKey.'/'.$templateRootPaths.'/'.$CType.'.html');

        if ($fluidTemplateFile) {
            try {
                $view = GeneralUtility::makeInstance(StandaloneView::class);
                $view->setTemplatePathAndFilename($fluidTemplateFile);
                $view->assignMultiple($row);
                if (!empty($row['pi_flexform'])) {
                    $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
                    $view->assign('pi_flexform_transformed', $flexFormService->convertFlexFormContentToArray($row['pi_flexform']));
                }

                $renderedView = $view->render();

                if ($renderedView === null) {
                    $view->setTemplateSource('<f:be.infobox title="{error.title}" state="2">Seems like this CType\'s ['.$row['CType'].'] preview template is empty.</f:be.infobox>');

                    $renderedView = $view->render();
                }

                return $renderedView;
            } catch (\Exception $e) {
                $this->logger->warning(sprintf(
                    'The backend preview for content element %d can not be rendered using the Fluid template file "%s": %s',
                    $row['uid'],
                    $fluidTemplateFile,
                    $e->getMessage()
                ));

                if ($GLOBALS['TYPO3_CONF_VARS']['BE']['debug'] && $this->getBackendUser()->isAdmin()) {
                    $view = GeneralUtility::makeInstance(StandaloneView::class);

                    $view->assign('error', [
                        'message' => str_replace(Environment::getProjectPath(), '', $e->getMessage()),
                        'title' => 'Error while rendering FluidTemplate preview using '.str_replace(Environment::getProjectPath(), '', $fluidTemplateFile),
                    ]);

                    $view->setTemplateSource('<f:be.infobox title="{error.title}" state="2">{error.message}</f:be.infobox>');

                    return $view->render();
                }

                throw $e;
            }
        }

        return null;
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

    protected function getProcessedValue(GridColumnItem $item, string $fieldList, array &$info): void
    {
        $itemLabels = $item->getContext()->getItemLabels();
        $record = $item->getRecord();

        $fieldArr = explode(',', $fieldList);

        foreach ($fieldArr as $field) {
            if ($record[$field]) {
                $info[] = '<strong>'.htmlspecialchars((string) ($itemLabels[$field] ?? '')).'</strong> '
                    .htmlspecialchars(BackendUtility::getProcessedValue('tt_content', $field, $record[$field]) ?? '');
            }
        }
    }

    /**
     * Create thumbnail code for record/field but not linked.
     *
     * @param mixed[] $row   Record array
     * @param string  $table Table (record is from)
     * @param string  $field Field name for which thumbnail are to be rendered.
     *
     * @return string HTML for thumbnails, if any.
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
     * @param array  $row      The row.
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
                'returnUrl' => GeneralUtility::getIndpEnv('REQUEST_URI').'#element-tt_content-'.$row['uid'],
            ];
            $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
            $url = (string) $uriBuilder->buildUriFromRoute('record_edit', $urlParameters);

            return '<a href="'.htmlspecialchars($url).'" title="'.htmlspecialchars($this->getLanguageService()->getLL('edit')).'">555'.$linkText.'</a>';
        }

        return $linkText;
    }

    protected function getBackendUser(): BackendUserAuthentication
    {
        return $GLOBALS['BE_USER'];
    }

    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }

    protected function getIconFactory(): IconFactory
    {
        return GeneralUtility::makeInstance(IconFactory::class);
    }

    public function wrapPageModulePreview(string $previewHeader, string $previewContent, GridColumnItem $item): string
    {
        $content = '<span class="exampleContent">'.$previewHeader.$previewContent.'</span>';
        if ($item->isDisabled()) {
            return '<span class="text-muted">'.$content.'</span>';
        }

        return $content;
    }
}
