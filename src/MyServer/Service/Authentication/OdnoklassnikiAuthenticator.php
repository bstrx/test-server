<?php

namespace MyServer\Service\Authentication;

/**
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class OdnoklassnikiAuthenticator implements AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $networkKey, $authKey)
    {
        //do something for odnoklassniki

        return true;
    }
}