<?php

namespace App\Services;

use App\DTO\CurlResult;

class CurlHttpClient
{

    /**
     * Отправка HTTP запросов.
     * Заголовки могут быть в виде ['Key' => 'Value'] || ['Key: Value']
     * @param string $url
     * @param string $method
     * @param array $headers
     * @param array $data
     * @param array $query
     * @param int $connectTimeout
     * @param int $timeout
     * @return CurlResult
     */
    public function request(
        string $url,
        string $method,
        array $headers = [],
        array $data = [],
        array $query = [],
        int $connectTimeout = 15,
        int $timeout = 60
    ): CurlResult
    {
        if(!empty($query)){
            $url .= (str_contains($url, '?') ? '&' : '?') . http_build_query($query);
        }

        $curl = curl_init();
        if($curl === false){
            throw new \RuntimeException('Curl init failed');
        }

        $body = null;
        $needContentTypeJson = false;
        if(!empty($data)){
            $body = json_encode($data);
            $needContentTypeJson = true;
        }

        $formattedHeaders = $this->formatHeaders($headers, $needContentTypeJson);

        $method = strtoupper($method);

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_CONNECTTIMEOUT => $connectTimeout,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => $formattedHeaders,
        ]);

        if($body !== null){
            curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        }

        $result = curl_exec($curl);
        $errNo = curl_errno($curl);
        $err = curl_error($curl);
        $info = curl_getinfo($curl);
        $httpCode = intval($info['http_code'] ?? 0);

        if(!$result){
            return new CurlResult(
                $httpCode,
                [],
                null,
                sprintf('Curl error (%d): %s', $errNo, $err)
            );
        }

        $decodedResult = json_decode($result, true);

        return new CurlResult(
            $httpCode,
            $decodedResult,
            $result,
            $errNo !== 0 ? sprintf('Curl error (%d): %s', $errNo, $err) : null
        );
    }

    /**
     * Приведёт заголовки в нужный формат
     * @param array $headers
     * @param bool $needContentTypeJson
     * @return array
     */
    private function formatHeaders(array $headers, bool $needContentTypeJson = false): array
    {
        $formatHeaders = [];
        $hasContentTypeJson = false;

        foreach($headers as $k => $i){
            if(is_int($k)){
                $formatHeaders[] = $i;
                $hasContentTypeJson = str_starts_with(strtolower($i), 'content-type:');
                continue;
            }

            $formatHeaders[] = sprintf('%s: %s', $k, $i);
            $hasContentTypeJson = strtolower($k) == 'content-type';
        }

        if($needContentTypeJson && !$hasContentTypeJson){
            $formatHeaders[] = 'Content-Type: application/json';
        }

        return $formatHeaders;
    }
}
