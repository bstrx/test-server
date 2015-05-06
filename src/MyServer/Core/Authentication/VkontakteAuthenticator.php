<?php

namespace MyServer\Core\Authentication;

/**
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class VkontakteAuthenticator implements AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $authKey)
    {
        //do something for vkontakte

        return true;
    }
}
