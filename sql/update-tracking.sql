ALTER TABLE `rcm_sites`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_domains`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_languages`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_countries`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_pages`
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_containers`
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_plugin_instances`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_plugin_wrappers`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_redirects`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_revisions`
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';
ALTER TABLE `rcm_setting`
  ADD createdDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD createdByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD createdReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedDate DATETIME NOT NULL DEFAULT '0000-01-01 00:00:00',
  ADD modifiedByUserId VARCHAR(255) NOT NULL DEFAULT 'predates-tracking',
  ADD modifiedReason VARCHAR(512) NOT NULL DEFAULT 'predates-tracking';

ALTER TABLE `rcm_sites`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_domains`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_languages`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_countries`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_pages`
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_containers`
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_plugin_instances`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_plugin_wrappers`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_redirects`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_revisions`
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
ALTER TABLE `rcm_setting`
  CHANGE createdDate createdDate DATETIME NOT NULL,
  CHANGE createdByUserId createdByUserId VARCHAR(255) NOT NULL,
  CHANGE createdReason createdReason VARCHAR(512) NOT NULL,
  CHANGE modifiedDate modifiedDate DATETIME NOT NULL,
  CHANGE modifiedByUserId modifiedByUserId VARCHAR(255) NOT NULL,
  CHANGE modifiedReason modifiedReason VARCHAR(512) NOT NULL;
