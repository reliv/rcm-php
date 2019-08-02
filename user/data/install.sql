######################
# RcmUser Create Script
######################
-- USER
CREATE TABLE rcm_user_user (
  id VARCHAR(255) NOT NULL UNIQUE,
	username VARCHAR(255) NOT NULL UNIQUE,
	password VARCHAR(255) NOT NULL,
	state VARCHAR(255) NOT NULL DEFAULT 'disabled', -- 'enabled', 'disabled', etc...
  email VARCHAR(255) DEFAULT NULL,
  name VARCHAR(255) DEFAULT NULL,
	PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

CREATE TABLE rcm_user_user_role (
	id INT AUTO_INCREMENT NOT NULL, 
	userId VARCHAR(255) NOT NULL,
	roleId VARCHAR(255) NOT NULL,
	PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

-- ACL
CREATE TABLE rcm_user_acl_role (
	id INT AUTO_INCREMENT NOT NULL,
	parentRoleId VARCHAR(255) DEFAULT NULL,
	roleId VARCHAR(255) NOT NULL UNIQUE,
	description VARCHAR(255) DEFAULT NULL, 
	PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

INSERT INTO `rcm_user_acl_role`
(`id`,
`parentRoleId`,
`roleId`,
`description`)
VALUES
('1', NULL, 'guest', NULL),
('2', 'guest', 'user', NULL),
('3', 'user', 'manager', NULL),
('4', 'manager', 'admin', NULL);

CREATE TABLE rcm_user_acl_rule (
	id INT AUTO_INCREMENT NOT NULL,
	roleId VARCHAR(255) NOT NULL,
	rule VARCHAR(32) NOT NULL, -- allow or deny or ignore
	resourceId VARCHAR(255) NOT NULL, -- some resource value
	privilege VARCHAR(255) DEFAULT NULL, -- some privilege value (created, read, update, delete, execute)
	PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

-- LOGS
CREATE TABLE rcm_user_log (
  id INT AUTO_INCREMENT NOT NULL,
  dateTimeUtc DATETIME NOT NULL,
  type VARCHAR(16) NOT NULL,
  message TEXT NOT NULL,
  extra TEXT DEFAULT NULL,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;

/*
INSERT INTO `rcm_user_acl_rule`
(`id`,
`roleId`,
`rule`,
`resource`,
`privilege`)
VALUES
(1, 'admin', 'allow', 'root', NULL);
/*
/*
CREATE TABLE rcm_user_user_metadata (
	id INT AUTO_INCREMENT NOT NULL,

	userCreateDate DATETIME DEFAULT NULL,
	userCreatedById BIGINT DEFAULT NULL,
	userModifiedDate DATETIME DEFAULT NULL,
	userModifiedById BIGINT DEFAULT NULL,

	PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
*/
