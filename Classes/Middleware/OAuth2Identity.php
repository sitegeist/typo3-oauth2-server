<?php declare(strict_types = 1);
namespace FGTCLB\OAuth2Server\Middleware;

use FGTCLB\OAuth2Server\Server\ServerFactory;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;

/**
 * Handler for OAuth2 identity requests
 */
final class OAuth2Identity implements MiddlewareInterface
{
    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getUri()->getPath() !== '/oauth/identity') {
            return $handler->handle($request);
        }

        $factory = new ServerFactory();
        $server = $factory->buildResourceServer();

        try {
            $request = $server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $e) {
            return $e->generateHttpResponse(new Response());
        }

        $userName = $request->getAttribute('oauth_user_id');

        return new JsonResponse([
            'id' => $userName, // required
            'username' => $userName,
            'name' => ucfirst($userName) . ' User',
        ]);
    }
}