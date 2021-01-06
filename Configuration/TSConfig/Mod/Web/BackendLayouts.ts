mod.web_layout.BackendLayouts {
    // @import 'EXT:site_backend/Configuration/TSConfig/Mod/Web/Layout/*.ts'
    Content {
        title = Content
        icon = EXT:site_backend/Resources/Public/Images/BackendLayouts/OneColumn.png

        config {
            backend_layout {
                colCount = 1
                rowCount = 1

                rows {
                    1 {
                        columns {
                            1 {
                                name = Content
                                colPos = 0
                                allowed = *
                            }
                        }
                    }
                }
            }
        }
    }
}
