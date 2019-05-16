<?php

namespace App\Traits;


use App\Kernel;
use Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Symfony\Component\HttpKernel\KernelInterface;

trait GoogleClient
{
    public $googleClient;

    public $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(Google_Service_Drive::DRIVE);
        $root = $this->kernel->getProjectDir();
        $client->setAuthConfig($root . '/credentials.json');
        $client->setAccessType('online');
        $client->setPrompt('select_account consent');

        $tokenPath = $root . '/token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        if ($client->isAccessTokenExpired()) {

            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {

                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }

            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        $this->googleClient = $client;

        return $client;
    }

    public function printFiles()
    {
        $this->initializationOfGoogleClient();

        $service = new Google_Service_Drive($this->googleClient);

        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name)'
        );
        $results = $service->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            print "No files found.\n";
        } else {
            print "Files:\n";
            foreach ($results->getFiles() as $file) {
                printf("%s (%s)\n", $file->getName(), $file->getId());
            }
        }
    }

    public function uploadFile($content)
    {
        $this->initializationOfGoogleClient();

        $fileMetadata = new Google_Service_Drive_DriveFile(array(
            'name' => $content['name']));
        $service = new Google_Service_Drive($this->googleClient);

        $file = $service->files->create($fileMetadata, array(
            'data' => file_get_contents($content["tmp_name"]),
            'mimeType' => $content['type'],
            'uploadType' => 'multipart',
            'fields' => 'id'));
        
        return $file->id;
    }

    public function downloadFile($id)
    {
        $this->initializationOfGoogleClient();

        $service = new Google_Service_Drive($this->googleClient);
        $response = $service->files->get($id, array(
            'alt' => 'media'));

        return $response->getBody()->getContents();
    }

    public function deleteFile($id)
    {
        $this->initializationOfGoogleClient();

        $service = new Google_Service_Drive($this->googleClient);
        $service->files->delete($id);
    }

    public function initializationOfGoogleClient()
    {
        if ($this->googleClient == null) {
            $this->googleClient = $this->getClient();
        }
    }
}