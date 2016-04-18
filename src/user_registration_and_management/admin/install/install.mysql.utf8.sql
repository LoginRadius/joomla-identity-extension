CREATE TABLE IF NOT EXISTS `#__loginradius_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting` varchar(255) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting` (`setting`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

CREATE TABLE IF NOT EXISTS `#__loginradius_advanced_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `setting` varchar(255) NOT NULL,
  `value` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting` (`setting`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `#__loginradius_users` (
  `id` int(11) DEFAULT NULL,
  `LoginRadius_id` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `Uid` varchar(255) DEFAULT NULL,
  `lr_picture` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__loginradius_addresses` (
  `user_id` int(11) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `address_line1` varchar(100) DEFAULT NULL,
  `address_line2` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_basic_profile_data` (
  `user_id` int(11) NOT NULL,
  `loginradius_id` varchar(25) NOT NULL,
  `provider` varchar(20) DEFAULT NULL,
  `prefix` varchar(100) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(100) DEFAULT NULL,
  `full_name` varchar(200) DEFAULT NULL,
  `nick_name` varchar(200) DEFAULT NULL,
  `profile_name` varchar(100) DEFAULT NULL,
  `birth_date` varchar(15) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `country_name` varchar(100) DEFAULT NULL,
  `thumbnail_image_url` varchar(1000) DEFAULT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `local_country` varchar(500) DEFAULT NULL,
  `profile_country` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_certifications` (
  `user_id` int(11) NOT NULL,
  `certification_id` varchar(30) DEFAULT NULL,
  `certification_name` varchar(500) DEFAULT NULL,
  `authority` varchar(500) DEFAULT NULL,
  `license_number` varchar(500) DEFAULT NULL,
  `start_date` varchar(500) DEFAULT NULL,
  `end _date` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) DEFAULT NULL,
  `company_type` varchar(500) DEFAULT NULL,
  `industry` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ;

CREATE TABLE IF NOT EXISTS `#__loginradius_contacts` (
  `user_id` int(11) NOT NULL,
  `contact_id` varchar(30) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile_url` varchar(1000) DEFAULT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `status` text,
  `industry` varchar(500) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `phone_number` varchar(30) DEFAULT NULL,
  `dateofbirth` varchar(15) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `provider` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_courses` (
  `user_id` int(11) NOT NULL,
  `course_id` varchar(30) DEFAULT NULL,
  `course` varchar(100) DEFAULT NULL,
  `course_number` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_current_status` (
  `user_id` int(11) NOT NULL,
  `status_id` varchar(30) DEFAULT NULL,
  `status` text,
  `source` varchar(500) DEFAULT NULL,
  `created_date` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_education` (
  `user_id` int(11) NOT NULL,
  `school` varchar(100) DEFAULT NULL,
  `year` varchar(500) DEFAULT NULL,
  `type` varchar(500) DEFAULT NULL,
  `notes` varchar(100) DEFAULT NULL,
  `activities` varchar(100) DEFAULT NULL,
  `degree` varchar(100) DEFAULT NULL,
  `field_of_study` varchar(100) DEFAULT NULL,
  `start_date` varchar(500) DEFAULT NULL,
  `end_date` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_emails` (
  `user_id` int(11) NOT NULL,
  `email_type` varchar(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_extended_location_data` (
  `user_id` int(11) NOT NULL,
  `main_address` varchar(500) DEFAULT NULL,
  `hometown` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `local_city` varchar(500) DEFAULT NULL,
  `profile_city` varchar(500) DEFAULT NULL,
  `profile_url` varchar(1000) DEFAULT NULL,
  `local_language` varchar(10) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_extended_profile_data` (
  `user_id` int(11) NOT NULL,
  `website` varchar(500) DEFAULT NULL,
  `favicon` varchar(1000) DEFAULT NULL,
  `industry` varchar(200) DEFAULT NULL,
  `about` text,
  `timezone` varchar(100) DEFAULT NULL,
  `verified` varchar(10) DEFAULT NULL,
  `last_profile_update` varchar(100) DEFAULT NULL,
  `created` varchar(100) DEFAULT NULL,
  `relationship_status` varchar(20) DEFAULT NULL,
  `quote` varchar(1000) DEFAULT NULL,
  `interested_in` varchar(255) DEFAULT NULL,
  `interests` varchar(100) DEFAULT NULL,
  `religion` varchar(20) DEFAULT NULL,
  `political_view` varchar(100) DEFAULT NULL,
  `https_image_url` varchar(500) DEFAULT NULL,
  `followers_count` int(11) DEFAULT NULL,
  `friends_count` int(11) DEFAULT NULL,
  `is_geo_enabled` enum('0','1') DEFAULT NULL,
  `total_status_count` int(11) DEFAULT NULL,
  `number_of_recommenders` int(11) DEFAULT NULL,
  `honors` varchar(1000) DEFAULT NULL,
  `associations` varchar(1000) DEFAULT NULL,
  `hirable` enum('0','1') DEFAULT NULL,
  `repository_url` varchar(1000) DEFAULT NULL,
  `age` int(3) DEFAULT NULL,
  `professional_headline` varchar(1000) DEFAULT NULL,
  `provider_access_token` varchar(100) DEFAULT NULL,
  `provider_token_secret` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_facebook_events` (
  `user_id` int(11) NOT NULL,
  `event_id` varchar(30) NOT NULL,
  `name` varchar(500) NOT NULL,
  `start_time` varchar(30) NOT NULL,
  `end_time` varchar(30) NOT NULL,
  `location` varchar(500) NOT NULL,
  `rsvp_status` varchar(500) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `updated_date` varchar(30) NOT NULL,
  `privacy` varchar(30) NOT NULL,
  `owner_id` varchar(500) DEFAULT NULL,
  `owner_name` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_facebook_likes` (
  `user_id` int(11) NOT NULL,
  `likes_id` varchar(40) DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `category` varchar(500) DEFAULT NULL,
  `created_date` varchar(500) DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_facebook_posts` (
  `user_id` int(11) NOT NULL,
  `post_id` varchar(500) NOT NULL,
  `from_name` varchar(100) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `start_time` varchar(100) DEFAULT NULL,
  `update_time` varchar(100) DEFAULT NULL,
  `message` text,
  `place` varchar(500) DEFAULT NULL,
  `picture` varchar(1000) DEFAULT NULL,
  `likes` int(8) DEFAULT NULL,
  `shares` int(8) DEFAULT NULL,
  `Post` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_favorites` (
  `user_id` int(11) NOT NULL,
  `social_id` varchar(30) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_groups` (
  `user_id` int(11) NOT NULL,
  `group_id` varchar(30) NOT NULL,
  `name` varchar(500) NOT NULL,
  `type` varchar(500) NOT NULL,
  `description` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `country` varchar(500) NOT NULL,
  `postal_code` varchar(30) NOT NULL,
  `logo` varchar(500) NOT NULL,
  `image` varchar(500) NOT NULL,
  `member_count` varchar(30) NOT NULL,
  `provider` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_imaccounts` (
  `user_id` int(11) NOT NULL,
  `account_type` varchar(20) DEFAULT NULL,
  `account_username` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_inspirational_people` (
  `user_id` int(11) NOT NULL,
  `social_id` varchar(20) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_languages` (
  `user_id` int(11) NOT NULL,
  `language_id` varchar(30) DEFAULT NULL,
  `language` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_linkedin_companies` (
  `user_id` int(11) NOT NULL,
  `company_id` varchar(20) NOT NULL,
  `company_name` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_patents` (
  `user_id` int(11) NOT NULL,
  `patent_id` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `date` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_phone_numbers` (
  `user_id` int(11) NOT NULL,
  `number_type` varchar(20) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_positions` (
  `user_id` int(11) NOT NULL,
  `position` varchar(100) DEFAULT NULL,
  `summary` varchar(100) DEFAULT NULL,
  `start_date` varchar(20) DEFAULT NULL,
  `end_date` varchar(20) DEFAULT NULL,
  `is_current` enum('0','1') DEFAULT NULL,
  `company` int(11) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_recommendations_received` (
  `user_id` int(11) NOT NULL,
  `recommendation_id` varchar(30) DEFAULT NULL,
  `recommendation_type` varchar(100) DEFAULT NULL,
  `recommendation_text` text,
  `recommender` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_skills` (
  `user_id` int(11) NOT NULL,
  `skill_id` varchar(20) DEFAULT NULL,
  `name` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_sports` (
  `user_id` int(11) NOT NULL,
  `sport_id` varchar(20) DEFAULT NULL,
  `sport` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_status` (
  `user_id` int(11) NOT NULL,
  `provider` varchar(20) NOT NULL,
  `status_id` varchar(20) NOT NULL,
  `status` text,
  `date_time` varchar(20) DEFAULT NULL,
  `likes` int(8) DEFAULT NULL,
  `place` varchar(500) DEFAULT NULL,
  `source` varchar(500) DEFAULT NULL,
  `image_url` varchar(1000) DEFAULT NULL,
  `link_url` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_twitter_mentions` (
  `user_id` int(11) NOT NULL,
  `mention_id` varchar(30) NOT NULL,
  `text` text,
  `date_time` varchar(20) DEFAULT NULL,
  `likes` int(11) NOT NULL,
  `place` varchar(500) DEFAULT NULL,
  `source` varchar(500) DEFAULT NULL,
  `image_url` varchar(500) NOT NULL,
  `link_url` varchar(500) NOT NULL,
  `mentioned_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `#__loginradius_volunteer` (
  `user_id` int(11) NOT NULL,
  `volunteer_id` varchar(30) DEFAULT NULL,
  `role` varchar(500) DEFAULT NULL,
  `organization` varchar(500) DEFAULT NULL,
  `cause` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;