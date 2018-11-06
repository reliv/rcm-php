#Pages
MUST WORK:
- [x] Page->New->notFromTemplate (RcmAdmin\Controller\PageController)
- [x] Page->New->fromTemplate (RcmAdmin\Controller\PageController)
- [x] Page->Save (RcmAdmin\Controller\PageController)
- [x] Page->Publish (RcmAdmin\Controller\PageController)
- [x] Page->Delete (DELETE /api/admin/sites/8/pages/9588)
- [ ] Page->Edit Properties
- [x] Site->CopyPages->withPageAlreadInHistory (POST /api/admin/sites/12486/page-copy/5969)
- [x] Site->CopyPages->withPageNotAlreadInHistory (POST /api/admin/sites/12486/page-copy/5969)
- [x] InventoryManager->createProduct (POST /api/resource/rcm-page/9547/copy)

Not doing right now but may exist in future:
- [ ] Page->Move (doesn't currently exist in RCM UI)
- [ ] Page->Edit Permissions (doesn't currently change the page resource, changes ACL rules instead)
