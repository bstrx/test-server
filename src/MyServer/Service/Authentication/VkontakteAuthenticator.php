<?php

namespace MyServer\Service\Authentication;

/**
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class VkontakteAuthenticator implements AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $networkKey, $authKey)
    {
        //do something for vkontakte

        return true;
    }
}