@TODOs:

MUST:
- Ensure page->edit properties audit logs (also run through PageMutationService)
- double check the resourceIds change and stay the same properly across all operations and with respect to locations
- add versionId to ChangeLogEvent and ensure it is sorted
- 500 when visiting log screen when not logged in

SHOULD:
- write doc explaining "fromIds" and why they were removed (include "link to thing that links to nothing") 
- document that locations that never existed before or have been depublished have different resourceIds than when they get published and subsequent drafts
- document resourceIds and how they change and stay the same
- document or fix that duplicateFromUnknown creates published rows with null content

COULD:
- Implment code to fill in "USER_FULL_NAME_UNKNOWN"
- Implement "page move" in RCM UI and also ensure it audit logs properly
