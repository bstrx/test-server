<?php
namespace MyServer\Core\Authentication;

use \Exception;
use MyServer\Core\Db;
use MyServer\Core\Request;
use MyServer\Core\ServiceContainer;
use MyServer\Core\Session;

/**
 * Authenticates user
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Authenticator
{
    /**
     * @var Request
     */
    private $authData;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Db
     */
    private $db;

    /**
     * @param $authData
     * @throws Exception
     */
    public function __construct($authData) {
        if (empty($authData)) {
            throw new Exception('No authentication data provided');
        }

        $this->authData = $authData;
        $this->session = ServiceContainer::get('session');
        $this->db = ServiceContainer::get('db');
    }

    /**
     * Tries to quickly get user from session. In case of failure, tries to login him via networks
     *
     * @return array|null
     */
    public function authenticateUser()
    {
        $user = $this->getUserFromSession();
        if (!$user) {
            $user = $this->getUserFromNetwork();
            if ($user) {
                $this->session->start();
                $this->session->set('user', $user);
            }
        }

        return $user;
    }

    /**
     * Tries to get user from session. Session id can be passed by client to server
     * and vice versa at the end of the script in response.
     *
     * @return array|null
     */
    private function getUserFromSession()
    {
        if (!empty($this->authData['sessionId'])) {
            $this->session->setId($this->authData['sessionId']);
            $this->session->start();

            return $this->session->get('user');
        }

        return null;
    }

    /**
     * Authenticates user in social networks and registers him in our system if needed
     *
     * @return array|null
     * @throws Exception
     */
    private function getUserFromNetwork()
    {
        $user = null;
        if (!empty($this->authData['personId']) && !empty($this->authData['networkKey']) && !empty($this->authData['authKey'])) {
            $authenticated = $this->checkNetworkAuthentication(
                $this->authData['personId'],
                $this->authData['networkKey'],
                $this->authData['authKey']
            );

            if ($authenticated) {
                $user = $this->db->fetchOneBy('user', [
                    'person_id' => $this->authData['personId'],
                    'auth_key' => $this->authData['authKey']
                ]);
            }
        }

        return $user;
    }

    /**
     * @param $personId
     * @param $network
     * @param $authKey
     * @return bool
     */
    private function checkNetworkAuthentication($personId, $network, $authKey)
    {
        switch($network) {
            case 'vk':
                $authenticationProvider = $this->getVkontakteAuthenticator();
                break;
            case 'odnoklassniki':
                $authenticationProvider = $this->getOdnoklassnikiAuthenticator();
                break;
            default:
                return false;
        }

        return $authenticationProvider->checkAuthKey($personId, $authKey);
    }

    /**
     * Separated method for easier unit testing
     * @return VkontakteAuthenticator
     */
    private function getVkontakteAuthenticator()
    {
        return new VkontakteAuthenticator();
    }

    /**
     * Separated method for easier unit testing
     * @return OdnoklassnikiAuthenticator
     */
    private function getOdnoklassnikiAuthenticator()
    {
        return new OdnoklassnikiAuthenticator;
    }
}
