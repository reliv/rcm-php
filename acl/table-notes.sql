CREATE TABLE `rcm_acl_group_policy` (
`policyId` INT NOT NULL AUTO_INCREMENT,
`group` VARCHAR(256) NOT NULL,
`rules` JSON NOT NULL,
PRIMARY KEY (`policyId`),
UNIQUE INDEX `group_UNIQUE` (`group` ASC));



CREATE TABLE `rcm_acl_user_group` (
`id` INT NOT NULL AUTO_INCREMENT,
`userId` VARCHAR(256) NOT NULL,
`groupId` JSON NOT NULL,
PRIMARY KEY (`id`),
UNIQUE INDEX `userId_groupId_UNIQUE` (`userId`,`groupId` ASC));
