<?php

namespace MyServer\Service\Authentication;

class VkontakteAuthenticator implements AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $networkKey, $authKey)
    {
        //do something for odnoklassniki

        return true;
    }
}