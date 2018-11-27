# Pages
List of test cases in the current UI that MUST WORK:
- [x] Page->New->notFromTemplate (RcmAdmin\Controller\PageController)
- [x] Page->New->fromTemplate (RcmAdmin\Controller\PageController)
- [x] Page->Save (RcmAdmin\Controller\PageController)
- [x] Page->Publish (RcmAdmin\Controller\PageController)
- [x] Page->Delete (DELETE /api/admin/sites/8/pages/9588)
- [x] Page->EditProperties->withoutChangingPageUrlName (PUT /api/admin/sites/12486/pages/9261)
- [x] Page->EditProperties->andChangePageUrlName (PUT /api/admin/sites/12486/pages/9261)
- [x] Page->Restore (GET /rcm-admin/page/publish-page-revision/n/our-founders/137582)
- [x] Site->CopyPages->withPageAlreadyInHistory (POST /api/admin/sites/12486/page-copy/5969)
- [x] Site->CopyPages->withPageNotAlreadyInHistory (POST /api/admin/sites/12486/page-copy/5969)
- [x] Site->ManageSites->duplicate (POST /api/admin/site-copy ApiAdminSitesCloneController)
- [x] Site->Create? (POST /api/admin/manage-sites ApiAdminManageSitesController)
- [x] InventoryManager->createProduct (POST /api/resource/rcm-page/9547/copy)

Not doing right now but may exist in future:
- [ ] Page->Edit Permissions (doesn't currently change the page resource, changes ACL rules instead)

# Sites
List of test cases in the current UI that MUST WORK:
- [x] Site->ManageSites->DisableSite (PUT /api/admin/manage-sites/6 ApiAdminManageSitesController)
- [x] Site->ManageSites->EnableSite (PUT /api/admin/manage-sites/6 ApiAdminManageSitesController)
- [x] Site->ManageSites->DuplicateSite (POST /api/admin/site-copy ApiAdminSitesCloneController)
- [x] Site->CreateSite

# Containers
List of test cases in the current UI that MUST WORK:
- [x] Page->Save (RcmAdmin\Controller\PageController)
