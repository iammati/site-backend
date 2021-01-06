dataProcessing.999 = TYPO3\CMS\Frontend\DataProcessing\FilesProcessor
dataProcessing.999 {
    if.isTrue.field = ce_image

    references {
        fieldName = ce_image
        table = tt_content
    }

    as = ce_image
}
