<?php

namespace MBMigration\Layer\MB;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Core\Config;


class MonkcmsAPI
{/**
 * Default config values to fall back to when a value isn't set.
 *
 * @var array
 */
    private static array $defaultConfig = [
        'request'    => null,
        'siteId'     => null,
        'siteSecret' => null,
        'cmsCode'    => 'EKK',
        'cmsType'    => 'CMS',
        'url'        => ''
    ];

    /**
     * Config values.
     *
     * @var array
     */
    private array $config;

    /**
     * Request Options values.
     *
     * @var array
     */
    private array $requestOptions;

    /**
     * Guzzle client instance.
     *
     * @var Client
     */
    private Client $client;

    /**
     * Constructor.
     *
     * @param  array $config Config values.
     * @param  array $requestOptions Request options.
     */
    public function __construct(array $config = array(), array $requestOptions = array())
    {
        self::$defaultConfig['url'] = Config::$MB_MONKCMS_API ?? $config['url'];

        $this->setConfig($config);
        $this->setRequestOptions($requestOptions);
        $this->client = new Client(); // Создаем новый клиент Guzzle
    }

    /**
     * Set the default config values to fall back to when a value isn't set.
     *
     * @param  array $defaultConfig
     * @return array New default config values.
     */
    public static function setDefaultConfig(array $defaultConfig): array
    {
        self::$defaultConfig = array_merge(self::$defaultConfig, $defaultConfig);
        return self::$defaultConfig;
    }

    /**
     * Set the config values.
     *
     * @param  array $config
     * @return self
     */
    public function setConfig(array $config): MonkcmsAPI
    {
        $this->config = array_merge(self::$defaultConfig, $config);
        return $this;
    }

    /**
     * Get the config values.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set the request option values.
     *
     * @param  array $requestOptions
     * @return self
     */
    public function setRequestOptions(array $requestOptions): MonkcmsAPI
    {
        $this->requestOptions = array_merge($this->buildRequestAuth(), $requestOptions);
        return $this;
    }

    /**
     * Get the request options.
     *
     * @return array
     */
    public function getRequestOptions(): array
    {
        return $this->requestOptions;
    }

    /**
     * Build the HTTP basic authentication option for Guzzle.
     *
     * @return array Authentication option.
     */
    private function buildRequestAuth(): array
    {
        $config = $this->getConfig();
        return ['auth' => [$config['siteId'], $config['siteSecret']]];
    }

    /**
     * Build query params in the format required by the API.
     *
     * @param  array $queryParams Param name => value associative array.
     * @return array
     */
    private static function buildRequestQueryParams(array $queryParams): array
    {
        $query = [];
        $count = 0;

        foreach ($queryParams as $key => $value) {
            if ($key === "show" && is_array($value)) {
                foreach ($value as $showValue) {
                    $queryParam = "{$key}_:_{$showValue}";
                    $query["arg{$count}"] = $queryParam;
                    $count++;
                }
            } else {
                $queryParam = "{$key}_:_{$value}";

                if ($key == 'module') {
                    $queryParam = $value;
                } elseif ($value === true) {
                    $queryParam = $key;
                }
                $query["arg{$count}"] = $queryParam;
                $count++;
            }
        }

        return $query;
    }

    /**
     * Build a request query string with query params.
     *
     * @param  array $queryParams Param name => value associative array.
     * @return string
     */
    private function buildRequestQueryString(array $queryParams): string
    {
        $config = $this->getConfig();

        $NR = (is_array($queryParams) ? count($queryParams) : 0)
            + (isset($queryParams['show']) && is_array($queryParams['show']) ? count($queryParams['show']) - 1 : 0);

        $query = [
            'SITEID' => $config['siteId'],
            'CMSCODE' => $config['cmsCode'],
            'CMSTYPE' => $config['cmsType'],
            'NR' => $NR
        ];
        $query = array_merge($query, self::buildRequestQueryParams($queryParams));

        return http_build_query($query);
    }

    /**
     * Build a request URL with query params.
     *
     * @param  array $queryParams Param name => value associative array.
     * @return string
     */
    private function buildRequestUrl(array $queryParams): string
    {
        $config = $this->getConfig();
        $queryString = $this->buildRequestQueryString($queryParams);
        return "{$config['url']}/Clients/ekkContent.php?{$queryString}";
    }

    /**
     * Make a request to the API.
     *
     * @param  array $queryParams Param name => value associative array.
     * @return array JSON-decoded associative array.
     * @throws Exception If the request fails.
     */
    private function request(array $queryParams): array
    {
        $url = $this->buildRequestUrl($queryParams);
        $options = $this->getRequestOptions();

        try {
            $response = $this->client->request('GET', $url, $options);
            $responseBody = substr($response->getBody(), 10); // Remove unwanted prefix
            try {
                return json_decode($this->fixJsonFormat($responseBody), true);
            } catch (Exception $exception) {
                return json_decode('{}', true);
            }

        } catch (Exception $e) {
            throw new Exception("Request failed: " . $e->getMessage());
        }
    }

    /**
     * Replace placeholder values with the expected values.
     *
     * @param string $body
     * @return string
     */
    private function replacePlaceholderValues($body): string
    {
        $body = str_replace('<mcms-interactive-answer>', '{{', $body);
        $body = str_replace('</mcms-interactive-answer>', '}}', $body);
        $body = str_replace('<mcms-interactive-free-form>', '{##', $body);
        $body = str_replace('</mcms-interactive-free-form>', '##}', $body);
        $body = str_replace('<\/mcms-interactive-answer>', '}}', $body);
        $body = str_replace('<\/mcms-interactive-free-form>', '##}', $body);

        if (array_key_exists('HTTP_ACCEPT', $_SERVER) && in_array('image/webp', explode(',', $_SERVER['HTTP_ACCEPT']))) {
            $body = str_replace('MONK_IMAGE_FORMAT_REPLACE_ME', 'webp', $body);
        } else {
            $body = str_replace('?fm=MONK_IMAGE_FORMAT_REPLACE_ME', '', $body);
        }

        return $body;
    }

    private function fixJsonFormat($response) {
        $response = trim($response);

        if (empty($response)) {
            return json_encode([]);
        }

        $response = rtrim(ltrim($response, ','), ',');

        $response = "[$response]";

        $decoded = json_decode($response, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode($decoded);
        }

        return json_encode([]);
    }

    /**
     * Request content from the API.
     *
     * @return array JSON-decoded associative array.
     * @throws Exception If the request fails.
     */
    public function get(): array
    {
        $queryParams = self::buildQueryParamsFromArgs(func_get_args());
        return $this->request($queryParams);
    }

    /**
     * Build query params from function arguments.
     *
     * @param  array $args Function arguments to parse.
     * @return array Param name => value associative array.
     */
    private static function buildQueryParamsFromArgs(array $args): array
    {
        $queryParams = $args[0];

        if (is_string($queryParams)) {
            $queryParams = explode('/', $queryParams);
            $queryParams = [
                'module' => $queryParams[0],
                'display' => $queryParams[1],
                'find' => $queryParams[2] ?? null
            ];

            if (isset($args[1])) {
                $queryParams = array_merge($queryParams, $args[1]);
            }
        }

        return array_filter($queryParams);
    }

    public function getSeriesGroupBySlug(): array
    {
        try {
            $response = $this->get([
                'module' => 'sermon',
                'order' => 'title',
                'groupby' => 'series',
                'display' => 'list',
                'show' => '{"slug":"__categoryslug__", "series":"__series__"},'
            ]);
        } catch (Exception $e) {
            $response = [];
        }

        return $this->groupBySlug($response);
    }


    public function groupBySlug($data): array
    {
        $grouped = [];

        foreach ($data as $item) {
            $slug = $item['slug'];
            $series = $item['series'];

            if (!isset($grouped[$slug])) {
                $grouped[$slug] = [];
            }

            if (!in_array($series, $grouped[$slug])) {
                $grouped[$slug][] = $series;
            }
        }

        return $grouped;
    }

}
