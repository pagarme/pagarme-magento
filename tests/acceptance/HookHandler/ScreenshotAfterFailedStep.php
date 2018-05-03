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

    protected function getScreenshot()
    {
        $driver = $this->getSession()->getDriver();
        $image = base64_encode($driver->getScreenshot());

        return $image;
    }

    protected function processResponse($response)
    {
        $link = '';
        if ($response->getStatusCode() == '200') {
            $json = json_decode($response->getBody());
            $link = $json->data->link;
        }

        return $link;
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
            $response = $requestException->getResponse();
            $statusCode = $response->getStatusCode();
            $reason = $response->getReasonPhrase();
            var_dump($response);
            echo sprintf(
                "I can't upload the screenshot. %s - %s",
                $statusCode,
                $reason
            );
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }

        return $response;
    }

    /**
     * @return string
     */
    public function takeAScreenshot()
    {
        $clientID = $this->getImgurClientID();
        if(empty($clientID)) {
            throw \Exception('You need to inform your imgur client ID to take screenshots');
        }

        $image = $this->getScreenshot();
        $response = $this->sendToImgur($image);
        $link = $this->processResponse($response);

        return $link;
    }

    /**
     * @AfterStep
     */
    public function takeAScreenshotFromStep(StepScope $scope)
    {
//        $isPassed = $scope->getTestResult()->isPassed();
//        if ($isPassed) {
//            return;
//        }
        echo $this->takeAScreenshot();
    }
}
