<?php

declare(strict_types=1);

namespace tests\SscBtlr\Cdr\GeneratePromptFromTemplate;

use Ssc\Btlr\App\Code\Model\FullyQualifiedName;
use Ssc\Btlr\App\Filesystem\BuildPath;
use Ssc\Btlr\App\Filesystem\Format\ReadJsonFile;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\JsonPath\Get;
use Ssc\Btlr\App\Template\Replace;
use SscBtlr\Cdr\GeneratePromptFromTemplate\ExampleServiceOne;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ExampleServiceOneTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_generates_class_from_template(): void
    {
        // Fixtures
        $root = __DIR__.'/../../../';
        $varTests = "{$root}var/tests/";

        $projectFilename = "{$varTests}fixtures/cdr/project/";
        $composerConfigFilename = 'composer.json';
        $composerParameterNamespacePathMap = '$.autoload.psr-4.*[0]';
        $classTemplateFilename = "{$root}templates/cdr/btlr/cli-test-class.php.tpl";
        $classFqcn = 'tests\\SscBtlr\\Cdr\\GenerateClassFromTemplate\\NewCliTest';

        // Dummies
        $get = $this->prophesize(Get::class);
        $buildPath = $this->prophesize(BuildPath::class);
        $readFile = $this->prophesize(ReadFile::class);
        $readJsonFile = $this->prophesize(ReadJsonFile::class);
        $replace = $this->prophesize(Replace::class);
        $writeFile = $this->prophesize(WriteFile::class);

        // Stubs & Mocks
        $composerConfigPath = "{$projectFilename}{$composerConfigFilename}";
        $buildPath->joining($projectFilename, $composerConfigFilename)
            ->shouldBeCalled()->willReturn($composerConfigPath);
        $composerConfig = \json_decode(
            file_get_contents($composerConfigPath),
            flags: \JSON_OBJECT_AS_ARRAY | \JSON_THROW_ON_ERROR,
        );
        $readJsonFile->in("{$projectFilename}{$composerConfigFilename}")
            ->shouldBeCalled()->willReturn($composerConfig);

        // Find Class filename
        $namespacePrefix = key($composerConfig['autoload-dev']['psr-4']);
        $testsRootDir = current($composerConfig['autoload-dev']['psr-4']);
        $get->in($composerConfig, $composerParameterNamespacePathMap)
            ->shouldBeCalled()->willReturn([$namespacePrefix => $testsRootDir]);
        $classFqcnWithoutNamespacePrefix = str_replace($namespacePrefix, '', $classFqcn);
        $classnameWithoutNamespacePrefix = str_replace('\\', '/', $classFqcnWithoutNamespacePrefix);

        $classFilename = "{$projectFilename}{$testsRootDir}{$classnameWithoutNamespacePrefix}.php";
        $buildPath->joining($projectFilename, $testsRootDir, "{$classnameWithoutNamespacePrefix}.php")
            ->shouldBeCalled()->willReturn($classFilename);

        // Create Class content
        $classTemplateContent = file_get_contents($classTemplateFilename);
        $readFile->in($classTemplateFilename)
            ->shouldBeCalled()->willReturn($classTemplateContent);

        $fullyQualifiedName = new FullyQualifiedName($classFqcn);
        $classContent = file_get_contents("{$varTests}fixtures/cdr/expected/{$testsRootDir}{$classnameWithoutNamespacePrefix}.php");
        $replace->in($classTemplateContent, [
            'fully_qualified_name.namespace' => $fullyQualifiedName->getNamespace(),
            'fully_qualified_name.name' => $fullyQualifiedName->getName(),
        ])->shouldBeCalled()->willReturn($classContent);

        // Save
        $writeFile->in($classFilename, $classContent)
            ->shouldBeCalled();

        // Assertion
        $exampleServiceOne = new ExampleServiceOne(
            $buildPath->reveal(),
            $get->reveal(),
            $readFile->reveal(),
            $readJsonFile->reveal(),
            $replace->reveal(),
            $writeFile->reveal(),
        );
        self::assertSame($classFilename, $exampleServiceOne->using(
            $projectFilename,
            $composerConfigFilename,
            $composerParameterNamespacePathMap,
            $classTemplateFilename,
            $classFqcn,
        ));
    }
}
