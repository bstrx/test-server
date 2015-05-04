<?php
namespace MyServer\Controller;

use MyServer\Core\Request;
use MyServer\Core\Controller;

class UserController extends Controller
{
    /**
     * Returns common user's info like money and level and key/value properties
     *
     * Request example:
     * 'user' => [
     *     'info' => [
     *         'money'
     *         'level'
     *     ],
     *     'properties' => [
     *         'customPropertyKey',
     *         'anotherKey'
     *     ]
     * ]
     *
     * @param Request $request
     * @return array same as request, but with values for requested keys
     */
    public function getInfoAction(Request $request)
    {
        $userRequest = $request->get('user');
        if (!$userRequest || !is_array($userRequest)) {
            return;
        }

        $userId = $request->get('id');
        if (!$userId) {
            return;
        }

        $result = ['user' => []];

        if (!empty($userRequest['info']) && is_array($userRequest['info'])) {
            $cacheKey = $this->getUserInfoCacheKey($userId);
            $userInfo = $this->getMemcached()->get($cacheKey);
            if (!$userInfo) {
                $userInfo = $this->getInfo($userId, $userRequest['info']);
                $this->getMemcached()->set($cacheKey, $userInfo);
            }

            $result['user']['info'] = $userInfo;
        }

        if (!empty($userRequest['properties']) && is_array($userRequest['properties'])) {
            $cacheKey = $this->getUserPropertyCacheKey($userId);
            $userProperties = $this->getMemcached()->get($cacheKey);
            if (!$userProperties) {
                $userProperties = $this->getProperties($userId, $userRequest['properties']);
                $this->getMemcached()->set($cacheKey, $userProperties);
            }
            $result['user']['properties'] = $userProperties;
        }

        return $result;
    }

    /**
     * Updates user's info and key/value properties
     *
     * Request example:
     * user => [
     *     info => [
     *         'money' => 1600,
     *         'level' => 80
     *     ],
     *     properties => [
     *         'customPropertyKey' => 'value1',
     *         'anotherKey' => 'value2'
     *     ]
     * ]
     *
     * @param Request $request
     */
    public function updateInfoAction(Request $request)
    {
        $userRequest = $request->get('user');
        if (!$userRequest || !is_array($userRequest)) {
            return;
        }

        $usedId = $request->get('id');
        if (!$usedId) {
            return;
        }

        if (!empty($userRequest['info']) && is_array($userRequest['info'])) {
            $this->updateInfo($usedId, $userRequest['info']);
        }

        if (!empty($userRequest['properties']) && is_array($userRequest['properties'])) {
            $this->updateProperties($usedId, $userRequest['properties']);
        }
    }

    /**
     * Returns user info according to the given list
     *
     * @param int $userId
     * @param array $requiredInfo
     * @return array
     */
    private function getInfo($userId, array $requiredInfo)
    {
        $result = [];
        $userInfo = $this->getDb()->fetchBy('user', ['id' => $userId], 1);

        foreach ($requiredInfo as $field) {
            if (isset($userInfo[$field])) {
                $result[$field] =  $userInfo[$field];
            } else {
                $result[$field] = null;
            }
        }

        return $result;
    }

    /**
     * Returns user properties according to the given list
     *
     * @param int $userId
     * @param array $requiredProperties
     * @return array
     */
    private function getProperties($userId, array $requiredProperties)
    {
        $result = [];
        $userProperties = $this->getDb()->fetchBy('user_property', ['id' => $userId], 1);

        foreach ($requiredProperties as $field) {
            if (isset($userProperties[$field])) {
                $result[$field] =  $userProperties[$field];
            } else {
                $result[$field] = null;
            }
        }

        return $result;
    }

    /**
     * Updates user's info if update of specific fields is allowed
     *
     * @param int $userId
     * @param array $info
     */
    private function updateInfo($userId, array $info)
    {
        /** @var array $allowedFields fields that can be updated by client */
        $allowedFields = ['money', 'level'];

        $fieldsToUpdate = [];
        foreach ($info as $key => $value) {
            if (in_array($key, $allowedFields)) {
                $fieldsToUpdate[$key] = $value;
            }
        }

        if ($fieldsToUpdate) {
            $this->getDb()->update('user', $fieldsToUpdate, ['id' => $userId]);
            $this->getMemcached()->delete($this->getUserInfoCacheKey($userId));
        }
    }

    /**
     * Updates/inserts user's key/value properties
     *
     * @param int $userId
     * @param array $properties
     */
    private function updateProperties($userId, array $properties)
    {
        //TODO prepare options for saving
        $this->getDb()->insertOrUpdate('user_property', $properties, ['id' => $userId]);
        $this->getMemcached()->delete($this->getUserPropertyCacheKey($userId));
    }

    /**
     * @param $userId
     * @return string
     */
    private function getUserInfoCacheKey($userId)
    {
        return 'user_info_' . $userId;
    }

    /**
     * @param $userId
     * @return string
     */
    private function getUserPropertyCacheKey($userId)
    {
        return 'user_property_' . $userId;
    }
}
