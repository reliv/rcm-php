/* @TODO delete this file eventually. It is just for testing purposes for Rcm\Acl\Controller\TestController */
INSERT INTO rcm_acl_group (id, rules) VALUES ('1', '[{\"effect\":\"allow\",\"actions\":[\"readTest\"],\"properties\":{\"testPropKey1\":\"testPropValue1\"}}]');
INSERT INTO rcm_acl_group (id, rules) VALUES ('2', '[{\"effect\": \"deny\", \"actions\": [\"readTest\"], \"properties\": {\"testPropKey1\": \"testPropValue1\"}}]');
INSERT INTO rcm_acl_user_group (id, group_id, userId) VALUES (NULL, '1', '4057510001');
INSERT INTO rcm_acl_user_group (id, group_id, userId) VALUES (NULL, '2', '8451706301');
