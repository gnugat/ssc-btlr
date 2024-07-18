Your task is to generate the code corresponding to a given test class, using PHP 8.3.
 
For example, here's a test class:

```php
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
use Ssc\Btlr\Cdr\Generate\ClassFromTemplate;
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
        $classFqcn = 'tests\\SscBtlr\\CdrGenerateClassFromTemplate\\Folder\\NewCliTest';

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
        $classFromTemplate = new ClassFromTemplate(
            $buildPath->reveal(),
            $get->reveal(),
            $readFile->reveal(),
            $readJsonFile->reveal(),
            $replace->reveal(),
            $writeFile->reveal(),
        );
        self::assertSame($classFilename, $classFromTemplate->using(
            $projectFilename,
            $composerConfigFilename,
            $composerParameterNamespacePathMap,
            $classTemplateFilename,
            $classFqcn,
        ));
    }
}
```

And here's its corresponding code:

```php
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
```

Now, write the code correspnoding tor the following test class:
 
```php
<?php

declare(strict_types=1);

namespace tests\SscBtlr\Cdr\GeneratePromptFromTemplate;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cdr\Generate\PromptFromTemplate;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ServiceTwoTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_generates_prompt_from_template(): void
    {
        // Fixtures
        $root = __DIR__.'/../../../';
        $varTests = "{$root}var/tests/";

        $promptTemplateFilename = "{$root}templates/cdr/btlr/prompts/generate-code-corresponding-to-test-class.md.tpl";
        $projectFilename = "{$varTests}fixtures/cdr/project/";
        $testClassCodeExampleFilename = "{$projectFilename}tests/GeneratePromptFromTemplate/ExampleServiceOneTest.php";
        $correspondingClassCodeExampleFilename = "{$projectFilename}src/GeneratePromptFromTemplate/ExampleServiceOne.php";
        $testClassCodeFilename = "{$projectFilename}tests/GeneratePromptFromTemplate/ServiceTwoTest.php";

        // Dummies
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);

        // Stubs & Mocks
        $promptTemplateContent = file_get_contents($promptTemplateFilename);
        $readFile->in($promptTemplateFilename)
            ->shouldBeCalled()->willReturn($promptTemplateContent);

        $testClassCodeExampleContent = file_get_contents($testClassCodeExampleFilename);
        $readFile->in($testClassCodeExampleFilename)
            ->shouldBeCalled()->willReturn($testClassCodeExampleContent);

        $correspondingClassCodeExampleContent = file_get_contents($correspondingClassCodeExampleFilename);
        $readFile->in($correspondingClassCodeExampleFilename)
            ->shouldBeCalled()->willReturn($correspondingClassCodeExampleContent);

        $testClassCodeContent = file_get_contents($testClassCodeFilename);
        $readFile->in($testClassCodeFilename)
            ->shouldBeCalled()->willReturn($testClassCodeContent);

        $prompt = file_get_contents("{$varTests}fixtures/cdr/expected/prompts/generate-code-corresponding-to-test-class.md");
        $replace->in($promptTemplateContent, [
            'test_class_code_example' => $testClassCodeExampleContent,
            'corresponding_class_code_example' => $correspondingClassCodeExampleContent,
            'test_class_code' => $testClassCodeContent,
        ])->shouldBeCalled()->willReturn($prompt);

        // Assertion
        $promptFromTemplate = new PromptFromTemplate(
            $readFile->reveal(),
            $replace->reveal(),
        );
        self::assertSame($prompt, $promptFromTemplate->using(
            $promptTemplateFilename,
            $testClassCodeExampleFilename,
            $correspondingClassCodeExampleFilename,
            $testClassCodeFilename,
        ));
    }
}
```
