<?php
namespace PagarMe\Magento\Test\HookHandler;

use Behat\Behat\Hook\Scope\AfterStepScope;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

trait ScreenshotAfterFailedStep
{
    protected function getImgurClientID()
    {
        return getenv('IMGUR_CLIENT_ID');
    }

    protected function getScreenshot(AfterStepScope $scope)
    {
        $driver = $this->getSession()->getDriver();
        $image = base64_encode($driver->getScreenshot());

        return $image;
    }

    protected function processResponse($response)
    {
        $json = json_decode($response->getBody());
        return $json->data->link;
    }

    protected function sendToImgur($image)
    {
        $guzzle = new GuzzleHttp\Client([]);
        try {
            $clientID = $this->getImgurClientID();
            $requestParams = [
                'headers' => [
                    'Authorization' => sprintf(
                        'Client-ID %s',
                        $clientID
                    )
                ],
                'body' => [
                    'image' => $image,
                    'title' => 'buildscreenshot',
                    'type' => 'URL'
                ]
            ];
            $response = $guzzle->post(
                'https://api.imgur.com/3/image',
                $requestParams
            );
        } catch (RequestException $requestException) {
            var_dump($requestException->getResponse());
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        return $response;
    }

    /**
     * @AfterStep
     */
    public function takeAScreenshot(AfterStepScope $scope)
    {
        $isPassed = $scope->getTestResult()->isPassed();
        if ($isPassed) {
            return;
        }

        $clientID = $this->getImgurClientID();
        if(empty($clientID)) {
            throw new \Exception('You need to inform your imgur client ID to take screenshots');
        }

        $image = $this->getScreenshot($scope);
        $response = $this->sendToImgur($image);
        $link = $this->processResponse($response);

        echo $link;
    }
}
