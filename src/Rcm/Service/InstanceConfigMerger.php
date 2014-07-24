<?php
/**
 * Created by PhpStorm.
 * User: rmcnew
 * Date: 12/4/13
 * Time: 3:29 PM
 */

namespace Rcm\Service;


class InstanceConfigMerger
{
    public function mergeConfigArrays($default, $changes)
    {

        if (empty($default)) {
            return $changes;
        }

        if (empty($changes)) {
            return $default;
        }

        foreach ($changes as $key => &$value) {
            if (is_array($value)) {
                if (isset($value['0'])) {
                    /*
                     * Numeric arrays ignore default values because of the
                     * "more in default that on production" issue
                     */
                    $default[$key] = $changes[$key];
                } else {
                    if (isset($default[$key])) {
                        $default[$key] = self::mergeConfigArrays(
                            $default[$key],
                            $changes[$key]
                        );
                    } else {
                        $default[$key] = $changes[$key];
                    }
                }
            } else {
                $default[$key] = $changes[$key];
            }
        }
        return $default;
    }
}
