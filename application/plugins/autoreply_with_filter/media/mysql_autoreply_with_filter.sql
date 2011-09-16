-- --------------------------------------------------------

--
-- Table structure for table `plugin_sms_to_email`
--

CREATE TABLE IF NOT EXISTS `plugin_autoreply_with_filter` (
  `id_user` int(11) NOT NULL,
  `wordlist` TEXT NOT NULL,
  `textreply` TEXT NOT NULL,
  `enable` ENUM('true', 'false') NOT NULL DEFAULT 'false',
  `filter_to` TEXT NOT NULL,
  UNIQUE(`id_user`)
) ENGINE=MyISAM;
