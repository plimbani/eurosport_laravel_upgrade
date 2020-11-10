<?php 

namespace Laraspace\Api\Services\TournamentAPI\Client;

use GuzzleHttpClient;

/**
* Class that uses http client to make requests
* to Tournament API.
*/
class HttpClient
{
	/**
     * Create a new client instance.
     *
     * @return void
     */
    function __construct()
    {
        $this->client = new GuzzleHttpClient();
    }

    /**
     * Get the default query parameters required by the Favicon API.
     * This includes the API token which is to be sent in all 
     * the requests.
     * 
     * @return array
     */
    protected function getDefaultQueryParameter() {
        return [
        ];
    }

    /**
     * Get the default header parameters required by the Favicon API.
     * This includes the API token which is to be sent in all 
     * the requests.
     * 
     * @return array
     */
    protected function getDefaultHeaderParameter() {
        return [
        ];
    }

    /**
     * Get the base tournament API URL from the config file.
     * 
     * @return string 
     */
    protected function getBaseUrl() {
        return config('config-variables.tournament.apiurl');
    }

    /**
     * Make a GET request to the tournament API.
     * 
     * @param  string $url    The endpoint of the API (without the base URL).
     * @param  array  $params Additional query parameters to be included.
     * @return array         Results array
     */
    public function get($url, $headers = [], $params = [])
    {
        $result = $this->client->request('GET', $this->buildUrl($url), [
            'query' => $this->buildQueryParameters($params),
            'headers' => $this->buildHeaderParameters($headers),
        ]);

        return $result->getBody();
    }

    /**
     * Make a POST request to the tournament API.
     * 
     * @param  string $url    The endpoint of the API (without the base URL).
     * @param  array  $params Additional JSON data to be included.
     * @return array         Results array
     */
    public function post($url, $headers = [], $json = [])
    {
        $result = $this->client->request('POST', $this->buildUrl($url), [
            'query' => $this->buildQueryParameters(),
            'headers' => $this->buildHeaderParameters($headers),
            'json' => $json,
        ]);        

        return $result->getBody();
    }

    /**
     * Get the full tournament URL.
     * 
     * @param  string $endpoint endpoint of the resource
     * @return string Full tournament URL with the base URL prepended
     */
    protected function buildUrl($endpoint = "") {
        return (starts_with($endpoint, '/')) ? $this->getBaseUrl() . $endpoint : $this->getBaseUrl() . '/' . $endpoint;        
    }

    /**
     * Build the query parameters to be sent to tournament.
     * 
     * @param  array  $query The additional query parameters to be included
     * @return array  Full query parameters to be sent to tournament.
     */
    protected function buildQueryParameters($query = []) {
        return count($query) ? array_merge($this->getDefaultQueryParameter(), $query) : $this->getDefaultQueryParameter();
    }

    /**
     * Build the header parameters to be sent to tournament.
     * 
     * @param  array  $headers The additional header parameters to be included
     * @return array  Full header parameters to be sent to tournament.
     */
    protected function buildHeaderParameters($headers = []) {
        return count($headers) ? array_merge($this->getDefaultHeaderParameter(), $headers) : $this->getDefaultHeaderParameter();
    }

    public function login()
    {
    	return $this->post('/auth/login', [], ['email' => 'rstenson@aecordigital.com', 'password' => 'password']);
    }
}