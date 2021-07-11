#
# Table structure for table 'pages'
#
CREATE TABLE pages (
    logo int(11) unsigned DEFAULT '0',

    copyright varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tt_content'
#
CREATE TABLE tt_content (
    -- TCA for container related fields --
    containerIsFluid tinyint(2) DEFAULT 0 NOT NULL,
    containerSpaceBefore varchar(60) DEFAULT '' NOT NULL,
	containerSpaceAfter varchar(60) DEFAULT '' NOT NULL,

    -- Default TCA fields for Content Elements --
    fd_rte text DEFAULT NULL,

    -- IRRE inline-record items --
    irre_accordions_item int(11) unsigned DEFAULT '0' NOT NULL,

    parentid int(11) unsigned DEFAULT '0' NOT NULL,
    parenttable varchar(100) DEFAULT 'tt_content' NOT NULL
);

#
# Table structure for table 'tx_sitebackend_domain_model_accordions'
#
CREATE TABLE tx_sitebackend_domain_model_accordions (
    header varchar(100) DEFAULT '' NOT NULL,
    rte text DEFAULT NULL,
    file int(11) unsigned DEFAULT '0' NOT NULL,
    image int(11) unsigned DEFAULT '0' NOT NULL,
    subaccords int(11) unsigned DEFAULT '0' NOT NULL,

    parentid int(11) unsigned DEFAULT '0' NOT NULL,
    parenttable varchar(100) DEFAULT 'tt_content' NOT NULL
);
