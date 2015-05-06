<?php
namespace MyServer\Core\Authentication;

use \Exception;
use MyServer\Core\Db;
use MyServer\Core\ServiceContainer;
use MyServer\Core\Session;

/**
 * Authenticates user
 * @author Vladimir Prudilin bstrxx@gmail.com
 */
class Authenticator
{
    /**
     * @var array
     */
    private $authParams;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Db
     */
    private $db;

    /**
     * @param $authParams
     * @throws Exception
     */
    public function __construct($authParams)
    {
        if (empty($authParams)) {
            throw new Exception('No authentication data provided');
        }

        $this->authParams = $authParams;
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

        if ($user) {
            $fieldsToUpdate = [];
            $currentDate = new \DateTime();
            $lastVisit = new \DateTime($user['last_visit']);
            if ($currentDate->format('d.m.Y') == $lastVisit->add(new \DateInterval('P1D'))->format('d.m.Y')) {
                $user['days_in_row']++;
                $fieldsToUpdate['days_in_row'] = $user['days_in_row'];
            }

            $user['last_visit'] = $fieldsToUpdate['last_visit'] = $currentDate->format("Y-m-d H:i:s");
            $this->db->update('user', $fieldsToUpdate, ['id' => $user['id']]);
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
        if (!empty($this->authParams['sessionId'])) {
            $this->session->setId($this->authParams['sessionId']);
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
        if (!empty($this->authParams['personId']) &&
            !empty($this->authParams['networkKey']) &&
            !empty($this->authParams['authKey']))
        {
            $authenticationProvider = $this->getAuthentificationProvider($this->authParams['networkKey']);
            if (!$authenticationProvider) {
                return null;
            }

            $authenticated = $authenticationProvider->checkAuthKey(
                $this->authParams['personId'],
                $this->authParams['authKey']
            );

            if ($authenticated) {
                $user = $this->db->fetchOneBy('user', [
                    'person_id' => $this->authParams['personId'],
                    'auth_key' => $this->authParams['authKey']
                ]);
            }
        }

        return $user;
    }

    /**
     * @param $network
     * @return AuthenticationProviderInterface
     */
    private function getAuthentificationProvider($network)
    {
        switch($network) {
            case 'vk':
                return $this->getVkontakteAuthenticator();
                break;
            case 'odnoklassniki':
                return $this->getOdnoklassnikiAuthenticator();
                break;
            default:
                return null;
        }
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
