<?php
namespace PagarMe\Magento\Test\HookHandler;

use Behat\Behat\Hook\Scope\StepScope;
use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7;

trait ScreenshotAfterFailedStep
{
    protected function getImgurClientID()
    {
        return 'c7aabd17e35f545';
    }

    protected function getScreenshot(StepScope $scope)
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
     * @BeforeStep
     */
    public function takeAScreenshot(StepScope $scope)
    {
        $isPassed = $scope->getTestResult()->isPassed();
        if ($isPassed) {
            return;
        }

        $clientID = $this->getImgurClientID();
        if(empty($clientID)) {
            throw \Exception('You need to inform your imgur client ID to take screenshots');
        }

        $image = $this->getScreenshot($scope);
        $response = $this->sendToImgur($image);
        $link = $this->processResponse($response);

        echo $link;
    }
}
