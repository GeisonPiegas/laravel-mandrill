<?php

namespace App\Helpers;

use \App\Configs as Configs;
use Illuminate\Support\Facades\Log;

class MailChimpClient {

    public function __construct(Configs\MailChimpConfig $mailchimpConfig)
    {
        $this->config = $mailchimpConfig;
    }

    public function __call($name, $arguments)
    {
        if (count($arguments) > 1) {
            throw new \Exception("Quantidade de argumentos inválida.");
        }

        $method = "GET";
        if (preg_match("/^[a-z]+/i", $name, $match)) {
            $method = $match[0];
            $name = preg_replace("/^[a-z]+/i", "", $name);
        }

        $url = preg_replace("/_(?=[^_])/i", "/", $name);
        $data = isset($arguments[0]) ? $arguments[0] : null;
        return $this->call($method, $url, $data, false);
    }

    public function call($method, $url, $data = [], $multpart = false) {
        try {
            $method = $method ? mb_strtolower($method) : "GET";
            $data = $data ? (array)json_decode(json_encode($data)) : [];
            
            $data['key'] = $this->config->KEY();

            $headers = [];

            $url = $this->config->URL().$this->config->ENDPOINT().$url;
            
            $options = [
                \GuzzleHttp\RequestOptions::HEADERS => $headers
            ];

            if ($data) {
                if (in_array($method, ["post", "put"])) {
                    if ($multpart) {
                        $options[\GuzzleHttp\RequestOptions::MULTIPART] = $data;
                    }
                    else {
                        $options[\GuzzleHttp\RequestOptions::JSON] = $data;
                    }
                }
                else {
                    $options[\GuzzleHttp\RequestOptions::QUERY] = $data;
                }
            }
            
            $client = new \GuzzleHttp\Client();
            /**
             * @var \Psr\Http\Message\ResponseInterface
             */
            $response = $client->$method($url, $options);
            $responseContents = $response->getBody()->getContents();
            $data = json_decode($responseContents);

            $this->has_erros($data);

            return $data;
        }
        catch(\Exception $e) {
            throw $e;
        }
    }

    private function has_erros($data){
        foreach ($data as $item){
            $item = (Object)$item;

            if(in_array($item->status, ["rejected"])){
                switch ($item->reject_reason) {
                    case 'invalid-sender':
                        throw new \Exception("Remetente inválido!", 400);
                    
                    case 'invalid':
                        throw new \Exception("Dados Inválidos!", 400);
    
                    case 'unsigned':
                        throw new \Exception("Remetente não assinado!", 400);

                    default:
                        break;
                }
            }
        }
    }
}
