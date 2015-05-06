<?php

namespace MyServer\Core\Authentication;

/**
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class OdnoklassnikiAuthenticator implements AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $authKey)
    {
        //do something for odnoklassniki

        return true;
    }
}
