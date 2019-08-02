-- Add privileges
ALTER TABLE rcm_user_acl_rule
ADD privileges LONGTEXT NOT NULL COMMENT '(DC2Type:json_array)';
-- Migrate data
UPDATE rcm_user_acl_rule SET rcm_user_acl_rule.privileges = CONCAT("[\"", rcm_user_acl_rule.privilege, "\"]")
WHERE rcm_user_acl_rule.privilege IS NOT NULL;
UPDATE rcm_user_acl_rule SET rcm_user_acl_rule.privileges = CONCAT("[]")
WHERE rcm_user_acl_rule.privilege IS NULL;
-- Drop privilege
-- ALTER TABLE rcm_user_acl_rule DROP privilege;
