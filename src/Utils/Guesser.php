<?php

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qsomazzi\Particle\Utils;

use Symfony\Component\String\Inflector\EnglishInflector;

class Guesser
{
    public static function getLabels(string $entityClass): array
    {
        $parts = explode('\\', $entityClass);

        $guessedDomain = $parts[0];
        $entityIndex   = array_search('Entity', $parts);
        if ($entityIndex !== false && isset($parts[$entityIndex + 1])) {
            $guessedDomain = $parts[$entityIndex - 1] ?? $parts[0];
        }

        $entityName = end($parts);
        $inflector  = new EnglishInflector();

        return [
            'shortName'     => $entityName,
            'domain'        => $guessedDomain,
            'singularLabel' => $inflector->singularize($entityName)[0],
            'pluralLabel'   => $inflector->pluralize($entityName)[0],
        ];
    }
}
