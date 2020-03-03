<?php

declare(strict_types=1);

namespace App\Controller\JsonApi;

use RuntimeException;
use Swaggest\JsonSchema\InvalidValue;
use Swaggest\JsonSchema\Schema as JsonSchema;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Json\JsonDecoder;

final class JsonApiValidator
{
    private JsonDecoder $decoder;

    public function __construct(JsonDecoder $decoder)
    {
        $this->decoder = $decoder;
    }

    public function validateRequest(string $schema, Request $request): object
    {
        if (!file_exists($schema)) {
            throw new RuntimeException("Schema file not found. {$schema}");
        }

        $schema = JsonSchema::import($this->decoder->decode(file_get_contents($schema)));

        $decodedJson = $this->decoder->decode($request->getContent());

        try {
            $schema->in($decodedJson);
        } catch (InvalidValue $exception) {
            $error = $exception->inspect();

            throw new RuntimeException($error->error);
        }

        return $decodedJson;
    }
}
