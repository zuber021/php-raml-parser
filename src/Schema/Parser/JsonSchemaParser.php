<?php

namespace Raml\Schema\Parser;

use Raml\Exception\InvalidJsonException;
use Raml\Schema\SchemaParserAbstract;
use Raml\Schema\Definition\JsonSchemaDefinition;
use JsonSchema\Uri\UriRetriever;
use JsonSchema\RefResolver;

class JsonSchemaParser extends SchemaParserAbstract
{

    /**
     * List of known JSON content types
     *
     * @var array
     */
    protected $compatibleContentTypes = [
        'application/json',
        'text/json',
        'application/(.*?)+json',
    ];

    // ---

    /**
     * Create a new JSON Schema definition from a string
     *
     * @param $schemaString
     *
     * @throws InvalidJsonException
     *
     * @return \Raml\Schema\Definition\JsonSchemaDefinition
     */
    public function createSchemaDefinition($schemaString)
    {
        $retriever = new UriRetriever;
        $jsonSchemaParser = new RefResolver($retriever);

        $data = json_decode($schemaString);

        if (! $data) {
	        echo 'Warning: Not valid json or file not found.'.PHP_EOL;
            echo 'JSON:'. var_export($schemaString, true).PHP_EOL;
            throw new InvalidJsonException(json_last_error());
        }

        $jsonSchemaParser->resolve($data, $this->getSourceUri());

        return new JsonSchemaDefinition($data);
    }
}
