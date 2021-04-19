<?php

namespace Omnipay\Beanstream\Message;

use Omnipay\Tests\TestCase;

class UpdateProfileCardRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new UpdateProfileCardRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize();
    }

    public function testSendSuccess()
    {
        $this->request->setProfileId('9ba60541d32648B1A3581670123dF2Ef');
        $this->request->setCardId('1');
        $card = $this->getValidCard();
        $this->assertSame($this->request, $this->request->setCard($card));
        $this->setMockHttpResponse('UpdateProfileCardSuccess.txt');
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(1, $response->getCode());
        $this->assertSame('Operation Successful', $response->getMessage());
        $this->assertSame('9ba60541d32648B1A3581670123dF2Ef', $response->getCustomerCode());
    }

    public function testSendError()
    {
        $this->request->setProfileId('9ba60541d32648B1A3581670123dF2Ef');
        $this->request->setCardId('1');
        $card = $this->getValidCard();
        $this->assertSame($this->request, $this->request->setCard($card));
        $this->setMockHttpResponse('UpdateProfileCardFailure.txt');
        $response = $this->request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertSame(19, $response->getCode());
        $this->assertSame(3, $response->getCategory());
        $this->assertSame('Customer information failed data validation', $response->getMessage());
    }

    public function testEndpoint()
    {
        $this->assertSame($this->request, $this->request->setProfileId('1'));
        $this->assertSame($this->request, $this->request->setCardId('2'));
        $this->assertSame('1', $this->request->getProfileId());
        $this->assertSame('2', $this->request->getCardId());
        $this->assertSame('https://www.beanstream.com/api/v1/profiles/' . $this->request->getProfileId() . '/cards/' . $this->request->getCardId(), $this->request->getEndpoint());
    }

    public function testComment()
    {
        $this->assertSame($this->request, $this->request->setComment('test'));
        $this->assertSame('test', $this->request->getComment());
    }

    public function testCard()
    {
        $this->request->setProfileId('8F10Ab54FC434b71972cF2E442c0fb4f');
        $this->request->setCardId('1');
        $card = $this->getValidCard();
        $this->assertSame($this->request, $this->request->setCard($card));
        $data = $this->request->getData();
        $this->assertSame($card['number'], $data['card']['number']);
        $this->assertSame($card['cvv'], $data['card']['cvd']);
        $this->assertSame(sprintf("%02d", $card['expiryMonth']), $data['card']['expiry_month']);
        $this->assertSame(substr($card['expiryYear'], -2), $data['card']['expiry_year']);
        $this->assertSame($card['firstName'] . ' ' . $card['lastName'], $data['card']['name']);
    }

    public function testToken()
    {
        $this->request->setProfileId('8F10Ab54FC434b71972cF2E442c0fb4f');
        $this->request->setCardId('1');
        $token = array(
            'name' => 'token-test-name',
            'code' => 'token-test-code'
        );
        $this->assertSame($this->request, $this->request->setToken($token));
        $this->assertSame($token, $this->request->getToken());
        $data = $this->request->getData();
        $this->assertSame($token, $data['token']);
    }

    public function testHttpMethod()
    {
        $this->assertSame('PUT', $this->request->getHttpMethod());
    }
}
