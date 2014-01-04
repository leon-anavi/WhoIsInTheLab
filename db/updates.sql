-- Update 1
ALTER TABLE  `who_users` ADD  `user_google_plus` VARCHAR( 100 ) NOT NULL ,
ADD  `user_website` VARCHAR( 200 ) NOT NULL;

-- Update 2
ALTER TABLE  `who_online`
    ADD  `online_since` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
CREATE TABLE IF NOT EXISTS `who_history` (
  `history_id` int(11) NOT NULL AUTO_INCREMENT,
  `history_MAC` varchar(17) NOT NULL,
  `history_to` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `history_from` timestamp NOT NULL,
  PRIMARY KEY (`history_id`)
);

--Update 3
ALTER TABLE  `who_users` ADD  `user_fstoken` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `who_users` ADD  `user_fscheckin` TIMESTAMP NOT NULL DEFAULT 0;
