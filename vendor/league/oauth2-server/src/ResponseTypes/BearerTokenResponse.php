<?php
/**
 * OAuth 2.0 Bearer Token Response.
 *
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */

namespace League\OAuth2\Server\ResponseTypes;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Psr\Http\Message\ResponseInterface;

class BearerTokenResponse extends AbstractResponseType
{
    /**
     * {@inheritdoc}
     */
    public function generateHttpResponse(ResponseInterface $response,$dataUser)
    {
        $expireDateTime = $this->accessToken->getExpiryDateTime()->getTimestamp();

        $jwtAccessToken = $this->accessToken->convertToJWT($this->privateKey);

        $responseParams = [
            'session'=>[
                'token_type'   => 'Bearer',
                'expires_in'   => $expireDateTime - (new \DateTime())->getTimestamp(),
                'access_token' => (string) $jwtAccessToken,
            ]
        ];

        if ($this->refreshToken instanceof RefreshTokenEntityInterface) {
            $refreshToken = $this->encrypt(
                json_encode(
                    [
                        'client_id'        => $this->accessToken->getClient()->getIdentifier(),
                        'refresh_token_id' => $this->refreshToken->getIdentifier(),
                        'access_token_id'  => $this->accessToken->getIdentifier(),
                        'scopes'           => $this->accessToken->getScopes(),
                        'user_id'          => $this->accessToken->getUserIdentifier(),
                        'expire_time'      => $this->refreshToken->getExpiryDateTime()->getTimestamp(),
                    ]
                )
            );

            $responseParams['session']['refresh_token'] = $refreshToken;
        }

        $responseParams = array_merge($this->getExtraParams($this->accessToken), $responseParams);
        $responseParams = array_merge($dataUser, $responseParams);
        $dataResponse = [
            'code' => 200,
            'status' => 'success',
            'data'   => $responseParams
        ];

        $response = $response
            ->withStatus(200)
            ->withHeader('pragma', 'no-cache')
            ->withHeader('cache-control', 'no-store')
            ->withHeader('content-type', 'application/json; charset=UTF-8');

        $response->getBody()->write(json_encode($dataResponse));

        return $response;
    }

    /**
     * Add custom fields to your Bearer Token response here, then override
     * AuthorizationServer::getResponseType() to pull in your version of
     * this class rather than the default.
     *
     * @param AccessTokenEntityInterface $accessToken
     *
     * @return array
     */
    protected function getExtraParams(AccessTokenEntityInterface $accessToken)
    {
        return [];
    }
}
