<?php

namespace Rcm\Acl\Service;

use Doctrine\ORM\EntityManager;
use Rcm\Acl\Entity\Group;

class GetAllGroups
{
    protected $cachedAllGroups;

    protected $groupsDirectory;

    public function __construct(
        string $groupsDirectory
    ) {
        $this->groupsDirectory = $groupsDirectory;
    }

    /**
     * Note: This may do some short-term caching in the future
     *
     * @TODO cache all these file system reads across requests?
     *
     * @return Group[]
     */
    public function __invoke(): array
    {
        if ($this->cachedAllGroups === null) {
            $this->cachedAllGroups = [];

            $dir = $this->groupsDirectory;
            if ($dh = opendir($dir)) {
                while (($fileName = readdir($dh)) !== false) {
                    $fullFilePath = $dir . '/' . $fileName;
                    if (!is_file($fullFilePath)) {
                        continue;
                    }
                    $groupName = basename($fileName, '.json');
                    $fileContent = file_get_contents($fullFilePath);
                    $fileContentDecoded = json_decode($fileContent, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \RuntimeException(
                            'Group file "' . $groupName . '" contains invalid JSON.'
                        );
                    }
                    $customProps = [];
                    foreach ($fileContentDecoded as $key => $value) {
                        if ($key !== 'rules') {
                            $customProps[$key] = $value;
                        }
                    }
                    $this->cachedAllGroups[] = new Group(
                        $groupName,
                        $fileContentDecoded['rules'],
                        $customProps
                    );
                }
                closedir($dh);
            }
        }

        return $this->cachedAllGroups;
    }
}
