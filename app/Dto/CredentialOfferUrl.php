<?php

declare(strict_types=1);

namespace App\Dto;

use InvalidArgumentException;

class CredentialOfferUrl
{
    public function __construct(
        protected string $url,
    ) {
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Extract Credential Offer URI from the openid credential offer url.
     * @return string Credential Offer URI of the openid credential offer.
     */
    public function getCredentialOfferUri(): string
    {
        # Issue is that waltid now returns credential-offer://?query-string,
        # which is not a valid url according to parse_url. which is why we
        # now add ://foo? as a dummy hostname
        $url = $this->url;
        if (strpos($url, '://?') !== false) {
            $url = preg_replace('~://\?~', '://foo?', $url, 1);
        }
        $query = parse_url((string)$url, PHP_URL_QUERY);
        if (!is_string($query)) {
            throw new InvalidArgumentException('Invalid URL: No query string found.');
        }

        parse_str($query, $queryParams);
        $uri = $queryParams['credential_offer_uri'] ?? null;

        if (!is_string($uri)) {
            throw new InvalidArgumentException('Invalid URL: No credential offer URI found.');
        }
        return $uri;
    }
}
