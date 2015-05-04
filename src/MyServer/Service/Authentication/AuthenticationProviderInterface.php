<?php
namespace MyServer\Service\Authentication;


interface AuthenticationProviderInterface
{
    public function checkAuthKey($personId, $networkKey, $authKey);
}