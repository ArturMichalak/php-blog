<?php


namespace Libs\GoogleAuth;
use Google_Client;
use Google_Service_Oauth2;
use GuzzleHttp\Client;
use JetBrains\PhpStorm\ArrayShape;

class GoogleAuth implements GoogleAuthInterface
{
    public Google_Client $client;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(__DIR__ . '/../../../credentials.json');
        $guzzleClient = new Client(array( 'curl' => array( CURLOPT_SSL_VERIFYPEER => false, ), ));
        $client->setHttpClient($guzzleClient);
        //$client->addScope(Google_Service_Oauth2::USERINFO_PROFILE);
        $client->addScope(Google_Service_Oauth2::USERINFO_EMAIL);
        $this->client = $client;
        session_start();
    }

    #[ArrayShape(['code' => "string", 'name' => "string", 'lastname' => "string"])]
    public function info(): array
    {
        if(isset($_GET["code"]))
        {
            $token = $this->client->fetchAccessTokenWithAuthCode($_GET["code"]);
            if(!isset($token['error']))
            {
                $this->client->setAccessToken($token['access_token']);
                $_SESSION['access_token'] = $token['access_token'];
                $googleService = new Google_Service_Oauth2($this->client);
                $data = $googleService->userinfo->get();

                if(!empty($data['given_name']))
                {
                    $_SESSION['user_first_name'] = $data['given_name'];
                }

                if(!empty($data['family_name']))
                {
                    $_SESSION['user_last_name'] = $data['family_name'];
                }

                if(!empty($data['email']))
                {
                    $_SESSION['user_email_address'] = $data['email'];
                }

                if(!empty($data['gender']))
                {
                    $_SESSION['user_gender'] = $data['gender'];
                }

                if(!empty($data['picture']))
                {
                    $_SESSION['user_image'] = $data['picture'];
                }
            }
            header('location:/');
        }

        if(!isset($_SESSION['access_token']))
        {
            $result = ['code' => $this->client->createAuthUrl()];
        } else {
            $result = ['email' => $_SESSION['user_email_address']];
        }

        return $result;
    }

    public function logout()
    {
        $token = $this->client->getAccessToken();
        if ($token) $this->client->revokeToken($token);
        session_destroy();
        header('location:/');
    }
}