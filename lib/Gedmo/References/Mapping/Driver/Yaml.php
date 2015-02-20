<?php

namespace Gedmo\References\Mapping\Driver;

use Gedmo\Exception\InvalidMappingException;
use Gedmo\Mapping\Driver\File;
use Gedmo\Mapping\Driver;
use Gedmo\ReferenceIntegrity\Mapping\Validator;

/**
 * This is an yaml mapping driver for References
 * behavioral extension.
 *
 * @author patrick <p@wetzelbemm.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Yaml extends File implements Driver
{
    private $validTypes = array(
        'referenceOne',
        'referenceMany',
        'referenceManyEmbed',
    );
    private $validProperties = array(
        'type',
        'class',
        'identifier',
        'mappedBy',
        'inversedBy',
    );

    /**
     * {@inheritDoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        $mapping = $this->_getMapping($meta->name);
        if (isset($mapping['fields'])) {
            foreach ($mapping['fields'] as $field => $fieldMapping) {
                if (isset($fieldMapping['gedmo'])) {
                    foreach ($this->validTypes as $type) {
                        $config[$type] = array();
                        if(isset($fieldMapping['gedmo'][$type])) {
                            $config[$type][$field] = array(
                                'field' => $field,
                            );
                            foreach ($this->validProperties as $property) {
                                if(isset($fieldMapping['gedmo'][$type][$property])) {
                                    $config[$type][$field][$property] = $fieldMapping['gedmo'][$type][$property];
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function _loadMappingFile($file)
    {
        return \Symfony\Component\Yaml\Yaml::parse($file);
    }

}
