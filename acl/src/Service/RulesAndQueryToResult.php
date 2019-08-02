<?php

namespace Rcm\Acl\Service;

use Rcm\Acl\Effects;
use Rcm\Acl\Query;
use Rcm\Acl\QueryResult;
use Rcm\Acl\Rule;

class RulesAndQueryToResult
{
    /**
     * Similar to lodash.isMatch. Warning, this IS NOT RECURSIVE.
     *
     * @param array $object
     * @param array $source
     * @return bool
     */
    protected static function isMatch(array $object, array $source): bool
    {
        foreach ($source as $key => $value) {
            if (!array_key_exists($key, $object) || $object[$key] !== $value) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Rule[] $rules
     * @param Query $query
     * @return QueryResult
     */
    public function __invoke(array $rules, Query $query): QueryResult
    {
        $applicableRules = array_filter($rules, function (Rule $rule) use ($query) {
            return in_array($query->getAction(), $rule->getActions())
                && self::isMatch($query->getProperties(), $rule->getProperties());
        });
        if (count($applicableRules) === 0) {
            return new QueryResult(Effects::DENY); // If no rules apply, then deny
        }

        usort($applicableRules, function (Rule $ruleA, Rule $ruleB) {
            $ruleAPropertiesLength = count($ruleA->getProperties());
            $ruleBPropertiesLength = count($ruleB->getProperties());
            if ($ruleAPropertiesLength > $ruleBPropertiesLength) {
                return -1; // Higher prop count means higher priority
            } else {
                if ($ruleAPropertiesLength < $ruleBPropertiesLength) {
                    return 1; // Lower prop count means lower priority
                } else { // if the properties lengths are equal
                    if ($ruleA->getEffect() === Effects::DENY && $ruleB->getEffect() === Effects::ALLOW) {
                        return -1; // Deny has higher priority than allow
                    } else {
                        if ($ruleA->getEffect() === Effects::ALLOW && $ruleB->getEffect() === Effects::DENY) {
                            return 1; // Allow has lower priority than deny
                        } else {
                            return 0; // If they are both "allow" or both "deny", we don't care about priority
                        }
                    }
                }
            }
        });

        return new QueryResult(array_shift($applicableRules)->getEffect());
    }
}
