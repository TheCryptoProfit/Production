-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_link_builder`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_link_builder` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`hits` INT(10) NULL DEFAULT '0',
	`phrase` VARCHAR(100) NULL DEFAULT NULL,
	`url` VARCHAR(200) NULL DEFAULT NULL,
	`rel` ENUM('no','alternate','author','bookmark','help','license','next','nofollow','noreferrer','prefetch','prev','search','tag') NULL DEFAULT 'no',
	`title` VARCHAR(100) NULL DEFAULT NULL,
	`target` ENUM('no','_blank','_parent','_self','_top') NULL DEFAULT 'no',
	`post_id` INT(10) NULL DEFAULT '0',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`publish` CHAR(1) NULL DEFAULT 'Y',
	`max_replacements` SMALLINT(2) NULL DEFAULT '1',
	`attr_title` TEXT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `unique` (`phrase`, `url`),
	INDEX `url` (`url`),
	INDEX `publish` (`publish`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_link_redirect`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_link_redirect` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`hits` INT(10) NULL DEFAULT '0',
	`url` VARCHAR(255) NULL DEFAULT NULL,
	`url_redirect` VARCHAR(150) NULL DEFAULT NULL,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`redirect_type` VARCHAR(25) NULL DEFAULT '',
	`redirect_rule` VARCHAR(25) NULL DEFAULT 'custom_url',
	`target_status_code` VARCHAR(25) NULL DEFAULT '',
	`target_status_details` TEXT NULL,
	`group_id` INT(5) NULL DEFAULT '1',
	`post_id` INT(10) NULL DEFAULT '0',
	`publish` CHAR(1) NULL DEFAULT 'Y',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `unique` (`url`),
	INDEX `url_redirect` (`url_redirect`),
	INDEX `redirect_type` (`redirect_type`),
	INDEX `redirect_rule` (`redirect_rule`),
	INDEX `target_status_code` (`target_status_code`),
	INDEX `group_id` (`group_id`),
	INDEX `post_id` (`post_id`),
	INDEX `publish` (`publish`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_monitor_404`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_monitor_404` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`hits` INT(10) NULL DEFAULT '1',
	`url` VARCHAR(200) NULL DEFAULT NULL,
	`referrers` TEXT NULL,
	`user_agents` TEXT NULL,
	`data` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `unique` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_web_directories`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_web_directories` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`directory_name` VARCHAR(255) NULL DEFAULT NULL,
	`submit_url` VARCHAR(255) NULL DEFAULT NULL,
	`pagerank` DOUBLE NULL DEFAULT NULL,
	`alexa` DOUBLE NULL DEFAULT NULL,
	`status` SMALLINT(1) NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `submit_url` (`submit_url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_post_planner_cron`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_post_planner_cron` (
	`id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_post` BIGINT(20) NOT NULL,
	`post_to` TEXT NULL,
	`post_to-page_group` VARCHAR(255) NULL DEFAULT NULL,
	`post_privacy` VARCHAR(255) NULL DEFAULT NULL,
	`email_at_post` ENUM('off','on') NOT NULL DEFAULT 'off',
	`status` SMALLINT(1) NOT NULL DEFAULT '0',
	`response` TEXT NULL,
	`started_at` TIMESTAMP NULL DEFAULT NULL,
	`ended_at` TIMESTAMP NULL DEFAULT NULL,
	`run_date` DATETIME NULL DEFAULT NULL,
	`repeat_status` ENUM('off','on') NOT NULL DEFAULT 'off' COMMENT 'one-time | repeating',
	`repeat_interval` INT(11) NULL DEFAULT NULL COMMENT 'minutes',
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`attempts` SMALLINT(6) NOT NULL,
	`deleted` TINYINT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	UNIQUE INDEX `id_post` (`id_post`),
	INDEX `status` (`status`),
	INDEX `deleted` (`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_alexa_rank`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_alexa_rank` (
	`id` INT(10) NOT NULL AUTO_INCREMENT,
	`domain` VARCHAR(50) NOT NULL DEFAULT '0',
	`global_rank` INT(10) NOT NULL DEFAULT '0',
	`rank_delta` VARCHAR(150) NOT NULL DEFAULT '0',
	`country_rank` INT(10) NOT NULL DEFAULT '0',
	`country_code` VARCHAR(4) NOT NULL DEFAULT '0',
	`country_name` VARCHAR(50) NOT NULL DEFAULT '0',
	`update_date` DATE NOT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `update_date` (`update_date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_report_log`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_report_log` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`log_id` VARCHAR(50) NULL DEFAULT NULL,
	`log_action` VARCHAR(50) NULL DEFAULT NULL,
	`desc` VARCHAR(255) NULL DEFAULT NULL,
	`log_data_type` VARCHAR(50) NULL DEFAULT NULL,
	`log_data` LONGTEXT NULL,
	`source` TEXT NULL,
	`date_add` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	INDEX `log_id` (`log_id`),
	INDEX `log_action` (`log_action`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_serprank_website`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_serprank_website` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`website` VARCHAR(255) NULL DEFAULT NULL,
	`search_engine` VARCHAR(50) NOT NULL DEFAULT 'google.com',
	`is_competitor` CHAR(1) NULL DEFAULT 'Y',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `website` (`website`, `search_engine`),
	INDEX `search_engine` (`search_engine`),
	INDEX `is_competitor` (`is_competitor`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_serprank_keyword`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_serprank_keyword` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`keyword` VARCHAR(255) NULL DEFAULT NULL,
	`search_engine` VARCHAR(50) NOT NULL DEFAULT 'google.com',
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `keyword` (`keyword`, `search_engine`),
	INDEX `search_engine` (`search_engine`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_serprank_mainrank`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_serprank_mainrank` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_website` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`id_keyword` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`search_engine` VARCHAR(50) NOT NULL DEFAULT 'google.com',
	`website_nbpages` SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0',
	`position` SMALLINT(3) NOT NULL DEFAULT '-1',
	`position_prev` SMALLINT(3) NOT NULL DEFAULT '-1',
	`position_worst` SMALLINT(3) NOT NULL DEFAULT '-1',
	`position_best` SMALLINT(3) NOT NULL DEFAULT '-1',
	`position_last_report` SMALLINT(3) NOT NULL DEFAULT '-1',
	`top100` TEXT NULL,
	`created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`publish` CHAR(1) NULL DEFAULT 'Y',
	`last_check_status` VARCHAR(20) NULL DEFAULT NULL,
	`last_check_msg` TEXT NULL,
	`last_check_data` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`id`),
	UNIQUE INDEX `idx_unique` (`id_keyword`, `search_engine`, `id_website`),
	INDEX `website_nbpages` (`website_nbpages`),
	INDEX `position` (`position`),
	INDEX `position_prev` (`position_prev`),
	INDEX `position_last_report` (`position_last_report`),
	INDEX `publish` (`publish`),
	INDEX `last_check_data` (`last_check_data`),
	INDEX `search_engine` (`search_engine`),
	INDEX `id_website` (`id_website`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Table structure for table `{wp_prefix}psp_serprank_pagerank`
--

CREATE TABLE IF NOT EXISTS `{wp_prefix}psp_serprank_pagerank` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`id_mainrank` INT(10) UNSIGNED NOT NULL DEFAULT '0',
	`page_link` VARCHAR(255) NULL DEFAULT NULL,
	`rank_date` DATETIME NULL DEFAULT NULL,
	`position` SMALLINT(3) NOT NULL DEFAULT '-1',
	`top100` TEXT NULL,
	PRIMARY KEY (`id`),
	INDEX `id_mainrank` (`id_mainrank`),
	INDEX `page_link` (`page_link`),
	INDEX `rank_date` (`rank_date`),
	INDEX `position` (`position`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;