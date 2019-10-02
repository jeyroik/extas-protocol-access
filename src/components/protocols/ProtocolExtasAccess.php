<?php
namespace extas\components\protocols;

use extas\interfaces\access\IAccess;
use Psr\Http\Message\RequestInterface;

/**
 * Class ProtocolExtasAccess
 *
 * @package extas\components\protocols
 * @author jeyroik@gmail.com
 */
class ProtocolExtasAccess extends Protocol
{
    const HEADER__PREFIX = 'x-extas-';

    /**
     * @param array $args
     * @param RequestInterface $request
     */
    public function __invoke(array &$args = [], RequestInterface $request = null)
    {
        if ($request) {
            $accessEntities = [
                IAccess::FIELD__SECTION, IAccess::FIELD__SUBJECT, IAccess::FIELD__OPERATION
            ];

            $fromHeaders = $this->grabHeaders($request, $accessEntities);
            $fromParameters = $this->grabParameters($request, $accessEntities);
            $defaults = $this->getDefaults($accessEntities);

            foreach ($accessEntities as $entity) {
                if (!isset($args[$entity])) {
                    $args[$entity] = $fromParameters[$entity] ?? ($fromHeaders[$entity] ?? ($defaults[$entity] ?? ''));
                }
            }
        }
    }

    /**
     * @param array $accessEntities
     *
     * @return array
     */
    protected function getDefaults(array $accessEntities)
    {
        $prefix = 'EXTAS__PROTOCOL_ACCESS__DEFAULT__';

        $defaults = [];

        foreach ($accessEntities as $entity) {
            $defaults[$entity] = getenv($prefix . strtoupper($entity)) ?: '';
        }

        return $defaults;
    }

    /**
     * @param RequestInterface $request
     * @param array $accessEntities
     *
     * @return array
     */
    protected function grabHeaders(RequestInterface $request, array $accessEntities)
    {
        $headerPrefix = getenv('EXTAS__PROTOCOL_ACCESS__HEADER_PREFIX') ?: static::HEADER__PREFIX;

        $args = [];
        foreach ($accessEntities as $entity) {
            $headerName = $headerPrefix . $entity;
            $headers = $request->getHeader($headerName);
            if (count($headers)) {
                $args[$entity] = array_shift($headers);
            }
        }

        return $args;
    }

    /**
     * @param RequestInterface $request
     * @param array $accessEntities
     *
     * @return array
     */
    protected function grabParameters(RequestInterface $request, array $accessEntities): array
    {
        parse_str($request->getUri()->getQuery(), $queryParams);

        $args = [];

        foreach ($accessEntities as $entity) {
            if (isset($queryParams[$entity])) {
                $args[$entity] = $queryParams[$entity];
            }
        }

        return $args;
    }
}
