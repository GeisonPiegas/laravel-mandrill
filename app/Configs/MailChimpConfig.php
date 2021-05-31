<?php

namespace App\Configs;

class MailChimpConfig {
    private $url;
    private $endpoint;
    private $key;
    private $from_email;

    public function __construct()
    {
        $this->url = trim(env("MAILCHIMP_URL"));
        $this->endpoint = trim(env("MAILCHIMP_ENDPOINT"));
        $this->key = trim(env("MAILCHIMP_KEY"));
        $this->from_email = trim(env("MAILCHIMP_FROM_EMAIL"));
    }

    /**
     * URL api mailchimp
     *
     * @return string
     */
    public function URL() {
        return $this->url;
    }

    /**
     * ENDPOINT api mailchimp
     *
     * @return string
     */
    public function ENDPOINT() {
        return $this->endpoint;
    }

    /**
     * KEY api mailchimp
     *
     * @return string
     */
    public function KEY() {
        return $this->key;
    }

    /**
     * FROM_EMAIL api mailchimp
     *
     * @return string
     */
    public function FROM_EMAIL() {
        return $this->from_email;
    }
}
