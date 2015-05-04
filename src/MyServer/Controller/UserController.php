<?php
namespace MyServer\Controller;

use MyServer\Core\Request;
use MyServer\Core\Controller;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return array|void
     */
    public function getInfoAction(Request $request)
    {
        $usedId = $request->get('id');
        if (!$usedId) {
            return;
        }

        $result = $this->getDb()->fetchOneBy('user', ['id' => $usedId]);
        if (!$result) {
            return;
        }



        return $result;
    }

    public function updateInfoAction(Request $request)
    {
        $fieldsToUpdate = [];
        $allowedFields = ['money', 'level'];
        foreach ($allowedFields as $field) {
            $newValue = $request->get($field);
            if ($newValue) {
                $fieldsToUpdate[$field] = $newValue;
            }
        }

        $usedId = $request->get('id');
        $this->getDb()->update('user', $fieldsToUpdate, ['id' => $usedId]);

        //save properties
        $newUserProperties = $request->get('user_property');
        if ($newUserProperties && is_array($newUserProperties)) {
            $this->getDb()->insertOrUpdate('user_property', $newUserProperties, ['id' => $usedId]);
        }
    }
}
