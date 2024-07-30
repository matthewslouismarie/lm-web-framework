<?php

declare(strict_types=1);

namespace LM\WebFramework\Form\Transformer;

use LM\WebFramework\Form\Exceptions\ExtractionException;
use LM\WebFramework\Form\FormFactory;
use LM\WebFramework\Model\Type\IModel;

final class ListTransformer implements IFormTransformer
{
    public function __construct(
        private IModel $nodeModel,
        private array $nodeConfig,
        private FormFactory $formFactory,
        private string $name,
    ) {
    }

    public function extractValueFromRequest(array $requestParsedBody, array $uploadedFiles): array {
        $data = $requestParsedBody[$this->name] ?? null;
        if (null === $data) {
            return [];
        }
        if (!is_array($data)) {
            throw new ExtractionException('Une erreur s’est produite.');
        }
        $value = [];
        foreach ($data as $name => $element) {
            if (null !== $this->nodeModel->getArrayDefinition()) {
                $value[] = $this->formFactory
                    ->createTransformer($this->nodeModel, $this->nodeConfig, csrf: false)
                    ->extractValueFromRequest($element, $uploadedFiles)
                ;
            } else {
                $value[] = $this->formFactory
                    ->createTransformer($this->nodeModel, $this->nodeConfig, name: $name)
                    ->extractValueFromRequest($element, $uploadedFiles)
                ;
            }
            
        }

        return $value;
    }
}