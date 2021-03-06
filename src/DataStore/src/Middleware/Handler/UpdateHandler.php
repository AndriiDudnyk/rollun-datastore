<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

namespace rollun\datastore\Middleware\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

/**
 * Class UpdateHandler
 * @package rollun\datastore\Middleware\Handler
 */
class UpdateHandler extends AbstractHandler
{
    /**
     * {@inheritdoc}
     */
    public function canHandle(ServerRequestInterface $request): bool
    {
        $canHandle = $request->getMethod() === "PUT";

        $primaryKeyValue = $request->getAttribute('primaryKeyValue');
        $canHandle = $canHandle && isset($primaryKeyValue);

        $row = $request->getParsedBody();
        $canHandle = $canHandle && isset($row) && is_array($row)
            && array_reduce(
                array_keys($row),
                function ($carry, $item) {
                    return $carry && !is_integer($item);
                },
                true
            );

        return $canHandle && $this->isRqlQueryEmpty($request);
    }

    /**
     * {@inheritdoc}
     */
    protected function handle(ServerRequestInterface $request): ResponseInterface
    {
        $primaryKeyValue = $request->getAttribute('primaryKeyValue');
        $primaryKeyIdentifier = $this->dataStore->getIdentifier();
        $item = $request->getParsedBody();

        $item = array_merge([$primaryKeyIdentifier => $primaryKeyValue], $item);
        $overwriteMode = $request->getAttribute('overwriteMode');
        $isItemExist = !empty($this->dataStore->read($primaryKeyValue));

        $newItem = $this->dataStore->update($item, $overwriteMode);

        $response = new Response();
        $response = $response->withBody($this->createStream($newItem));

        if ($overwriteMode && !$isItemExist) {
            $response = $response->withStatus(201);
        }

        return $response;
    }
}
