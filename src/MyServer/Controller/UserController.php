<?php
namespace MyServer\Controller;

use MyServer\Core\Request;
use MyServer\Core\Controller;

class UserController extends Controller
{
    /**
     * Expected array example:
     * user => [
     *     info => [
     *         'money'
     *         'level'
     *     ],
     *     properties => [
     *         'customPropertyKey',
     *         'anotherKey'
     *     ]
     * ]
     *
     * @param Request $request
     * @return array|void
     */
    public function getInfoAction(Request $request)
    {
        $userRequest = $request->get('user');
        if (!$userRequest || !is_array($userRequest)) {
            return;
        }

        $usedId = $request->get('id');
        if (!$usedId) {
            return;
        }

        $result = ['user' => []];

        if (!empty($userRequest['info']) && is_array($userRequest['info'])) {
            $result['user']['info'] = $this->getInfo($usedId, $userRequest['info']);
        }

        if (!empty($userRequest['properties']) && is_array($userRequest['properties'])) {
            $result['user']['properties'] =  $this->getProperties($usedId, $userRequest['properties']);
        }

        return $result;
    }

    /**
     * Expected array example:
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
            }
        }

        return $result;
    }

    /**
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
            }
        }

        return $result;
    }

    /**
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
        }
    }

    /**
     * @param int $userId
     * @param array $properties
     */
    private function updateProperties($userId, array $properties)
    {
        //TODO prepare options for saving
        $this->getDb()->insertOrUpdate('user_property', $properties, ['id' => $userId]);
    }
}
