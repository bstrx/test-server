<?php
namespace MyServer\Core\Authentication;

/**
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
interface AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $authKey);
}