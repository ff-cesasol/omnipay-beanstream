<?php namespace Omnipay\Beanstream\Message;

class CreateProfileCardRequest extends AbstractProfileRequest
{
    public function getEndpoint()
    {
        return $this->endpoint . '/' . $this->getProfileId() . '/cards';
    }

    public function getData()
    {
        $data = array(
            'comment' => $this->getComment()
        );

        if ($this->getCard()) {
            $this->getCard()->validate();

            $data['card'] = array(
                'number' => $this->getCard()->getNumber(),
                'name' => $this->getCard()->getName(),
                'expiry_month' => $this->getCard()->getExpiryDate('m'),
                'expiry_year' => $this->getCard()->getExpiryDate('y'),
                'cvd' => $this->getCard()->getCvv(),
            );
        }

        if ($this->getToken()) {
            $data['token'] = $this->getToken();
        }

        return $data;
    }

    public function getHttpMethod()
    {
        return 'POST';
    }
}
