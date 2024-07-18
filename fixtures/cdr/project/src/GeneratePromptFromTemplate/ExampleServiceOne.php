<?php

declare(strict_types=1);

namespace SscBtlr\Cdr\GeneratePromptFromTemplate;

use Ssc\Btlr\App\Code\Model\FullyQualifiedName;
use Ssc\Btlr\App\Filesystem\BuildPath;
use Ssc\Btlr\App\Filesystem\Format\ReadJsonFile;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\JsonPath\Get;
use Ssc\Btlr\App\Template\Replace;

class ExampleServiceOne
{
    public function __construct(
        private BuildPath $buildPath,
        private Get $get,
        private ReadFile $readFile,
        private ReadJsonFile $readJsonFile,
        private Replace $replace,
        private WriteFile $writeFile,
    ) {
    }

    public function using(
        string $projectFilename,
        string $composerConfigFilename,
        string $composerParameterNamespacePathMap,
        string $classTemplateFilename,
        string $classFqcn,
    ): string {
        $composerConfigPath = $this->buildPath->joining($projectFilename, $composerConfigFilename);
        $composerConfig = $this->readJsonFile->in($composerConfigPath);

        // Find Class filename
        $namespacePathMap = $this->get->in($composerConfig, $composerParameterNamespacePathMap);
        $namespacePrefix = key($namespacePathMap);
        $testsRootDir = current($namespacePathMap);
        $classFqcnWithoutNamespacePrefix = str_replace($namespacePrefix, '', $classFqcn);
        $classnameWithoutNamespacePrefix = str_replace('\\', '/', $classFqcnWithoutNamespacePrefix);

        $classFilename = $this->buildPath->joining($projectFilename, $testsRootDir, "{$classnameWithoutNamespacePrefix}.php");

        // Create Class Content
        $classTemplateContent = $this->readFile->in($classTemplateFilename);
        $fullyQualifiedName = new FullyQualifiedName($classFqcn);

        $classContent = $this->replace->in($classTemplateContent, [
            'fully_qualified_name.namespace' => $fullyQualifiedName->getNamespace(),
            'fully_qualified_name.name' => $fullyQualifiedName->getName(),
        ]);

        // Save
        $this->writeFile->in($classFilename, $classContent);

        return $classFilename;
    }
}
