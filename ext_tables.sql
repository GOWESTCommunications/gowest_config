#
# Add SQL definition of database tables
#
CREATE TABLE tt_content (
    tx_gowestconfig_gutter varchar(60) DEFAULT '' NOT NULL,
    tx_gowestconfig_summary varchar(255) DEFAULT '' NOT NULL,
    tx_gowestconfig_hide_in_breakpoint varchar(255) DEFAULT '' NOT NULL,
    tx_gowestconfig_crop tinyint(3) NOT NULL DEFAULT '0',
    tx_gowestconfig_aspect_ratio varchar(9) NOT NULL DEFAULT ''
);

CREATE TABLE pages (
    tx_gowestconfig_summary varchar(255) DEFAULT '' NOT NULL
);

CREATE TABLE sys_file_reference (
    copyright varchar(255) DEFAULT NULL,
);

CREATE TABLE sys_file_metadata (
    copyright varchar(255) DEFAULT '' NOT NULL,
);