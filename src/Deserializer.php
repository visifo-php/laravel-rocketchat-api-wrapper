<?php

namespace visifo\Rocket;

use Exception;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionProperty;

class Deserializer
{
    /**
     * @throws RocketException
     */
    public static function deserialize(object $response, string $class): mixed
    {
        rocketChat()->checkResponse($response);

        $classInstance = new $class();
        $objectOrArray = self::getObjectOrArrayFromResponse($response);
        self::fillObject($objectOrArray, $classInstance);

        return $classInstance;
    }

    /**
     * @throws RocketException
     */
    private static function fillObject($response, $classInstance): void
    {
        try {
            $reflectionClass = new ReflectionClass($classInstance);
        } catch (Exception $e) {
            throw new RocketException($e->getMessage(), $e->getCode(), $e);
        }
        $classProperties = $reflectionClass->getProperties();

        foreach ($classProperties as $property) {
            self::processPropertyMap($property, $response);

            $propertyName = $property->getName();
            $propertyType = $property->getType();

            if (is_object($response)) {
                $doc = $property->getDocComment();

                $isNullable = false;

                if ($doc && preg_match('/@nullable/i', $doc, $matches)) {
                    $isNullable = true;
                }

                if (!property_exists($response, $propertyName)) {
                    if ($isNullable) {
                        $classInstance->{$propertyName} = null;

                        continue;
                    }

                    throw new RocketException("property: '$propertyName' must exist in response");
                }

                if (!$propertyType) {
                    throw new RocketException("property: '$propertyName' needs to be typed");
                }

                if (is_object($response->$propertyName)) {
                    $class = self::getObjectClass($property, $classInstance);
                    self::fillObject($response->$propertyName, $class);
                } elseif (is_array($response->$propertyName)) {
                    self::fillArray($property, $response, $classInstance);
                } else {
                    $classInstance->{$propertyName} = $response->$propertyName;
                }
            } elseif (is_array($response)) {
                self::fillArray($property, $response, $classInstance);
            } else {
                throw new RocketException("Response must be type object or array");
            }
        }
    }

    /**
     * @throws RocketException
     */
    private static function getObjectOrArrayFromResponse(object|array $response): object|array
    {
        $properties = get_object_vars($response);
        $objects = array_filter($properties, fn($prop) => is_object($prop) || is_array($prop));

        if (empty($objects)) {
            throw new RocketException("Didnt find any objects/arrays inside RocketChat response");
        }

        if (count($objects) != 1) {
            throw new RocketException("You can only receive 1 object/array from RocketChat");
        }

        return reset($objects);
    }

    /**
     * @throws RocketException
     */
    private static function processPropertyMap(ReflectionProperty $property, object|array $response)
    {
        $propertyName = $property->getName();
        $doc = $property->getDocComment();

        if ($doc && preg_match('/(?<=@replace\s).*(?= )/i', $doc, $matches)) {
            $mappedName = $matches[0];
            if (property_exists($response, $mappedName)) {
                $response->$propertyName = $response->$mappedName;
            } else {
                throw new RocketException("The mapped property for '$propertyName' does not exist in RocketChat Response");
            }
        }
    }

    /**
     * @throws RocketException
     */
    private static function getObjectClass(ReflectionProperty $property, &$classInstance)
    {
        $propertyType = $property->getType();
        $propertyName = $property->getName();

        if ($propertyType instanceof ReflectionNamedType) {
            $propertyType = $propertyType->getName();
            $subClass = new $propertyType();
            $classInstance->{$propertyName} = $subClass;

            return $subClass;
        }
        // See: https://github.com/phpstan/phpstan/issues/3937
        // https://phpstan.org/r/afe6be72-823b-42b6-9241-43f578bdfc2f
        throw new RocketException('Wrong ReflectionType!');
    }

    /**
     * @throws RocketException
     */
    private static function fillArray(ReflectionProperty $property, object|array $response, &$classInstance)
    {
        $propertyName = $property->getName();
        preg_match('/(?<=@hint\s).*(?=\b)/i', $property->getDocComment(), $matches);
        $objectType = $matches[0];

        if ($objectType) {
            $classInstance->{$propertyName} = [];

            if (is_object($response)) {
                foreach ($response->$propertyName as $index => $obj) {
                    $arrayObject = new $objectType();
                    static::fillObject($obj, $arrayObject);
                    $classInstance->{$propertyName}[$index] = $arrayObject;
                }
            } else {
                foreach ($response as $index => $obj) {
                    $arrayObject = new $objectType();
                    static::fillObject($obj, $arrayObject);
                    $classInstance->{$propertyName}[$index] = $arrayObject;
                }
            }
        } else {
            is_object($response) ? $classInstance->{$propertyName} = $response->$propertyName : $classInstance->{$propertyName} = $response;
        }
    }
}
