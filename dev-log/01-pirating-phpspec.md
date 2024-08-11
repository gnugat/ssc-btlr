# Pirating phpspec

> Arrr, PHP be a cunning pirate, pilferin' ideas from other languages to stay ahead o' the fleet.
> 'Tis true, PHP is no bonny innovator, but it be masterful at plunderin' and adaptin' features,
> keepin' its sails full and its reputation mighty in the tech seas.
>
> [Pádraic Brady. 2012. PHP: a Pillagin Pirate](https://web.archive.org/web/20120430232221/http://blog.astrumfutura.com/2012/04/php-innocent-villagefolk-or-a-pillagin-pirate/)

[phpspec](https://github.com/phpspec/phpspec) is a PHP testing framework
crafted to help developers write clean, maintainable, and testable code
(even if they’d rather be swashbuckling through bug-infested seas).
It insists on specifying the behavior of your code before you even start coding
-- because sometimes, you need a little push to keep your code in shipshape
and Bristol fashion!

[It is highly opinionated on purpose](https://inviqa.com/blog/my-top-ten-favourite-phpspec-limitations),
with the intent to teach its users good design, whether they like it or not.
If something's not possible or hard to do with phpspec, it’s because that
thing is likely a dubious idea in need of some serious reconsideration.

Amongst its key features is a code generation tool, that integrates seamlessly
into the specBDD workflow, providing the following benefits:

- as you write and run your test, it bootstraps classes and methods
  for the system under test (SUT)
- it also bootstraps code for the service dependencies used by the SUT
  (though as interfaces)
- it's only one way, meaning it's not possible to generate test for a given code
  (hence promoting a test-first approach -- wait, is that a benefit?)

It's just brilliant. But what if you find yourself stranded in a codebase
that’s been cursed with PHPUnit instead?

As Pádraic did back in 2007, finding himself wondering if there were
[any Behaviour-Driven Development Tools](https://web.archive.org/web/20111113081429/http://blog.astrumfutura.com/2007/09/any-behaviour-driven-development-tools-for-php/)
for his PHP projects, and [ending up plundering Ruby's Rspec to create phpspec](https://web.archive.org/web/20120108072940/http://blog.astrumfutura.com/2007/11/the-phpspec-0-2-0devel-api/),
so too am I in 2024, about to purloin from... _check notes_ PHP?

I guess I be true PHPirate now, me buckle!

## phpspec's code generation analysis

> What to call your test is easy:
> it’s a sentence describing the next behaviour in which you are interested.
> How much to test becomes moot:
> you can only describe so much behaviour in a single sentence.
> When a test fails, simply work through the process described above:
> - either you introduced a bug (so fix the code),
> - the behaviour moved (update the test),
> - or the test is no longer relevant (delete the test).
>
> [...]

> I found that a really useful way to stay focused was to ask:
> What’s the next most important thing the system doesn’t do?
>
> This question requires you to identify the value of the features you haven’t
> yet implemented and to prioritize them.
>
> Dan North. 2006. [Introducing BDD](https://dannorth.net/introducing-bdd/)

There are two sides to BDD, the first one being specBDD which really is just
TDD with a couple of rules, mainly about the test method name, to make the
tests **expressive**:

- it should be a sentence
- it should follow a simple and consistent template
  (eg "it should do <action> for <condition>")
- it should describe the intended behaviour

This helps you shift your thinking away from "testing" and towards "specifying".

The other side is storyBDD, which really is just User Stories with a couple of
rules, mainly about vocabulary, to capture the scope of a feature alongside its
acceptance criteria:

- `Feature:`
- `  As a <actor>`
- `  I want a <feature>`
- `  So that <benefit>`
- `  Scenario:`
- `    Given <context>`
- `    When <event>`
- `    Then <expected outcome>`

We're already in too deep, so let's try to keep up and indulge in this
methodology by defining what we're trying to achieve here, using
[Gherkin](https://cucumber.io/docs/gherkin/reference/).

```gherkin
Feature: Study phpspec's code generation workflow

  As a developer
  I want to understand phpspec's code generation tool
  And how it integrates with a TDD (more specifically specBDD) methodology
  So that I can create a similar tool that'd integrate with another testing tool (eg PHPUnit)
  And that would work without being installed / tied to the application it'll be used in.
```

It's official. There's no turning back now.

## analysis

```
  Scenario: Analyze PHPSpec's code generation process
    Given a new `ssc/btlr` project
    And the creation of a specification for a `DescribeClass` class
    When reviewing the inputs of the `phpspec describe` command
    And the output generated file and code
    Then I should identify the key components and patterns used in the process
```

### project initialization

Let's create a new `ssc/btlr` project, by running:

```console
$ composer init --no-interaction --name 'ssc/btlr' --type project --autoload 'src'
```

We'll install phpspec for the purpose of the study, by running:

```console
$ composer require --dev phpspec/phpspec
```

For more powerful code generation, we'll also install the extension
[spec-gen](http://memio.github.io/spec-gen/):

```console
$ composer require --dev memio/spec-gen:^0.9
```

This requires us to create the following `phpspec.yml.dist` configuration file:

```yaml
suites:
   ssc_btlr_suite:
       namespace: 'Ssc\Btlr'
       psr4_prefix: 'Ssc\Btlr'

extensions:
    Memio\SpecGen\MemioSpecGenExtension: ~
```

We'll also install phpunit and prophecy, for the second part where we'll create our tool, by running:

```console
$ composer require --dev phpunit/phpunit phpspec/prophecy-phpunit
```

Then create the `phpunit.xml.dist` configuration file:

```xml
<?xml version="1.0" encoding="UTF-8"?>

<!-- http://phpunit.de/manual/current/en/appendixes.configuration.html -->
<phpunit backupGlobals="false" colors="true" bootstrap="vendor/autoload.php">
    <testsuites>
        <testsuite name="Tests">
            <directory>tests</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

Finally we'll configure autoloading by editing the `composer.json` as follow:

```json
{
    "name": "ssc/btlr",
    "type": "project",
    "autoload": {
        "psr-4": {
            "Ssc\\Btlr\\": "src"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "spec\\Ssc\\Btlr\\": "spec",
            "tests\\Ssc\\Btlr\\": "tests"
        }
    },
    "require-dev": {
        "php": "^8.3",
        "memio/spec-gen": "^0.9",
        "phpspec/phpspec": "^7.5",
        "phpunit/phpunit": "^11.2",
        "phpspec/prophecy-phpunit": "^2.2"
    }
}
```

> Note the use of PHP 8.3,
> which will give us access to the most recent language features, such as:
> - constructor property promotion
> - typed class constants
> - typed class properties
> - readonly class properties

### Generating test

Let's run:

```console
$ phpspec describe 'Ssc\Btlr\Spc\DescribeClass\GenerateTest'
Specification for Ssc\Btlr\Spc\DescribeClass\GenerateTest created in ./spec/Spc/DescribeClass/GenerateTestSpec.php.
```

This generates the following `./spec/Spc/DescribeClass/GenerateTestSpec.php` file:

```php
<?php

namespace spec\Ssc\Btlr\Spc\DescribeClass;

use PhpSpec\ObjectBehavior;
use Ssc\Btlr\Lck\DescribeClass\GenerateTest;

class GenerateTestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(GenerateTest::class);
    }
}
```

Here are the analysis notes:

> **Analysis Notes**:
> 
> - the input parameter is the "targeted class FQCN" (Fully Qualified Class Name): `Ssc\Btlr\Spc\DescribeClass\GenerateTest`
> - the internal logic seems to be something along the lines of:
>   - from the "targeted class FQCN", a "corresponding test class FQCN" is extrapolated: `spec\Ssc\Btlr\Spc\DescribeClass\GenerateTestSpec`
>     - this is done by prefixing the "targeted class FQCN" with `spec` and suffixing it with `Spec`
>   - the "targeted class FQCN" is split to extract:
>     - the "targeted class namespace": `Ssc\Btlr\Spc\DescribeClass`
>     - the "targeted class name": `GenerateTest`
>   - the "corresponding test class FQCN" is split to extract:
>     - the "corresponding test class namespace": `spec\Ssc\Btlr\Spc\DescribeClass`
>     - the "corresponding test class name": `GenerateTestSpec`
>   - from the "corresponding test class FQCN", a "corresponding test class filename" is extrapolated: `spec/Spc/DescribeClass/GenerateTestSpec.php`
>     - this is done by looking up in the project's Composer Configuration file `composer.json`:
>       - iterating through the `autoload-dev.psr-4` parameters
>         - if the parameter key ("namespace prefix", `spec\Ssc\Btlr\`) matches with the "corresponding test class FQCN"
>           - then replace the match with the parameter value ("path", `spec/`), and suffix with `.php`
>   - in the "corresponding test class filen", the "corresponding test class code" is built as follow:
>     - the "corresponding test class namespace"
>     - a use statement for `PhpSpec\ObjectBehavior`
>     - a use statement for the "targeted class FQCN"
>     - a class with the "corresponding test class name" that extends from `ObjectBehavior`, with methods:
>       - a test method named `it_is_initializable" without any arguments, with body:
>         - a call to `ObjectBehavior`'s method `shouldHaveType`, with as arguments:
>           - a call to "targeted class name"'s constant `class`
>   - then the "corresponding test class filen" is written on the filesystem
> - the output is the new "corresponding test class file"

### Writing the test

We need to rewrite the test first:

```php
<?php

namespace spec\Ssc\Btlr\Spc\DescribeClass;

use PhpSpec\ObjectBehavior;
use Ssc\Btlr\Spc\DescribeClass\GenerateTest;

class GenerateTestSpec extends ObjectBehavior
{
    function let(
        WriteFile $writeFile,
    ) {
        $this->beConstructedWith(
            $writeFile,
        );
    }

    function it_generates_a_test_class_for_the_given_fully_qualified_class_name(
        WriteFile $writeFile,
    ) {
        // Fixtures - Input parameters
        $fqcn = 'Ssc\Btlr\DescribeClass\GenerateTest';

        // Stubs & Mocks - Specification
        $fileExists->in($privateKeyFilename)
            ->willReturn(false);

        $keyPair = 'key pair, used as private key, and to generate public key';
        $cryptoBox->keyPair()
            ->willReturn($keyPair);
        $publicKey = 'public key';
        $cryptoBox->publicKey($keyPair)
            ->willReturn($publicKey);

        $privateKey = $keyPair;
        $writeFile->in($privateKeyFilename, $privateKey)
            ->shouldBeCalled();
        $writeFile->in($publicKeyFilename, $publicKey)
            ->shouldBeCalled();

        // Assertion - Usage
        $this->saveOnFilesystem(
            $privateKeyFilename,
            $publicKeyFilename,
        );
    }
}
```

Then run:

```console
$ phpspec run
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      collaborator does not exist: Ssc\Btlr\App\Sodium\CryptoBox

                                      100%                                       1
1 specs
1 example (1 broken)
17ms

                                                                                
  Would you like me to generate an interface `Ssc\Btlr\App\Sodium\CryptoBox`    
  for you?                                                                      
                                                                         [Y/n] 
Y
Interface Ssc\Btlr\App\Sodium\CryptoBox created in ./src/App/Sodium/CryptoBox.php.

Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      collaborator does not exist: Ssc\Btlr\App\Filesystem\FileExists

                                      100%                                       1
1 specs
1 example (1 broken)
18ms

                                                                                
  Would you like me to generate an interface                                    
  `Ssc\Btlr\App\Filesystem\FileExists` for you?                                 
                                                                         [Y/n] 
Y
Interface Ssc\Btlr\App\Filesystem\FileExists created in ./src/App/Filesystem/FileExists.php.

Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      collaborator does not exist: Ssc\Btlr\App\Filesystem\WriteFile

                                      100%                                       1
1 specs
1 example (1 broken)
18ms

                                                                                
  Would you like me to generate an interface                                    
  `Ssc\Btlr\App\Filesystem\WriteFile` for you?                                  
                                                                         [Y/n] 
Y
Interface Ssc\Btlr\App\Filesystem\WriteFile created in ./src/App/Filesystem/WriteFile.php.

Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      method `Double\FileExists\FileExists\P2::in()` is not defined.

                                      100%                                       1
1 specs
1 example (1 broken)
20ms

                                                                                
  Would you like me to generate a method signature                              
  `Ssc\Btlr\App\Filesystem\FileExists::in()` for you?                           
                                                                         [Y/n] 
Y
  Method signature Ssc\Btlr\App\Filesystem\FileExists::in() has been created.
  
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      method `Double\CryptoBox\CryptoBox\P1::keyPair()` is not defined.

                                      100%                                       1
1 specs
1 example (1 broken)
21ms

                                                                                
  Would you like me to generate a method signature                              
  `Ssc\Btlr\App\Sodium\CryptoBox::keyPair()` for you?                           
                                                                         [Y/n] 
Y
  Method signature Ssc\Btlr\App\Sodium\CryptoBox::keyPair() has been created.
  
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      method `Double\CryptoBox\CryptoBox\P1::publicKey()` is not defined.

                                      100%                                       1
1 specs
1 example (1 broken)
21ms

                                                                                
  Would you like me to generate a method signature                              
  `Ssc\Btlr\App\Sodium\CryptoBox::publicKey()` for you?                         
                                                                         [Y/n] 
Y
  Method signature Ssc\Btlr\App\Sodium\CryptoBox::publicKey() has been created.
  
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      method `Double\WriteFile\WriteFile\P3::in()` is not defined.

                                      100%                                       1
1 specs
1 example (1 broken)
21ms

                                                                                
  Would you like me to generate a method signature                              
  `Ssc\Btlr\App\Filesystem\WriteFile::in()` for you?                            
                                                                         [Y/n] 
Y
  Method signature Ssc\Btlr\App\Filesystem\WriteFile::in() has been created.
  
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      class Ssc\Btlr\Lck\GenerateKeys\UsingSodium does not exist.

                                      100%                                       1
1 specs
1 example (1 broken)
22ms

                                                                                
  Do you want me to create `Ssc\Btlr\Lck\GenerateKeys\UsingSodium` for you?     
                                                                         [Y/n] 
Y
Class Ssc\Btlr\Lck\GenerateKeys\UsingSodium created in ./src/Lck/GenerateKeys/UsingSodium.php.

Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      method Ssc\Btlr\Lck\GenerateKeys\UsingSodium::__construct not found.

                                      100%                                       1
1 specs
1 example (1 broken)
22ms

                                                                                
  Do you want me to create                                                      
  `Ssc\Btlr\Lck\GenerateKeys\UsingSodium::__construct()` for you?               
                                                                         [Y/n] 
Y
  Method Ssc\Btlr\Lck\GenerateKeys\UsingSodium::__construct() has been created.
  
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      method Ssc\Btlr\Lck\GenerateKeys\UsingSodium::saveOnFilesystem not found.

                                      100%                                       1
1 specs
1 example (1 broken)
22ms

                                                                                
  Do you want me to create                                                      
  `Ssc\Btlr\Lck\GenerateKeys\UsingSodium::saveOnFilesystem()` for you?          
                                                                         [Y/n] 
Y
  Method Ssc\Btlr\Lck\GenerateKeys\UsingSodium::saveOnFilesystem() has been created.
  
Ssc/Btlr/Lck/GenerateKeys/UsingSodium                                           
  25  - it generates keys using sodium and saves them on filesystem
      some predictions failed:
      Double\WriteFile\WriteFile\P3:
        No calls have been made that match:
            Double\WriteFile\WriteFile\P3->in(exact("./config/private_key"), exact("key pair, used as private key, and to generate public key"))
          but expected at least one.
        No calls have been made that match:
            Double\WriteFile\WriteFile\P3->in(exact("./config/public_key"), exact("public key"))
          but expected at least one.

                                      100%                                       1
1 specs
1 example (1 failed)
22ms
```

This generated `./src/App/Sodium/CryptoBox.php`:

```php
<?php

namespace Ssc\Btlr\App\Filesystem;

interface WriteFile
{

    public function in($argument1, $argument2);
}
```

As well as `./src/App/Filesystem/FileExists.php`:

```php
<?php

namespace Ssc\Btlr\App\Filesystem;

interface FileExists
{

    public function in($argument1);
}
```

Also `./src/App/Filesystem/WriteFile.php`:

```php
<?php

namespace Ssc\Btlr\App\Filesystem;

interface WriteFile
{

    public function in($argument1, $argument2);
}
```

And finally `./src/Lck/GenerateKeys/UsingSodium.php`:

```php
<?php

namespace Ssc\Btlr\Lck\GenerateKeys;

class UsingSodium
{
    public function __construct($argument1, $argument2, $argument3)
    {
        // TODO: write logic here
    }

    public function saveOnFilesystem($argument1, $argument2)
    {
        // TODO: write logic here
    }
}
```

## Now with PHPUnit

Let's rerecreate `lck:generate-keys`.

### Installing phpunit


### Generating test

Run:

```console
$ btlr cdr:describe \
    --project-filename ./ \
    --composer-config-filename composer.json \
    --fully-qualified-classname 'Ssc\Btlr\Lck\GenerateKeys\UsingSodium'

Specification for Ssc\Btlr\Lck\GenerateKeys\UsingSodium created in ./tests/Lck/GenerateKeys/UsingSodiumTest.php.
```

This should generate `./tests/Lck/GenerateKeys/UsingSodiumTest.php`:

```php
<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Lck\GenerateKeys;

use Ssc\Btlr\Lck\GenerateKeys\UsingSodium;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class UsingSodiumTest extends TestCase
{
    use ProphecyTrait;

    #[Test]
    function it_is_initializable()
    {
        // Fixtures - Input parameters
        // $fixture = 42;

        // Dummies - Service dependencies
        // $dependency = $this->prophesize(Dependency::class);

        // Stubs & Mocks - Specification
        // $value = 23;
        // $dependency->subcall($fixture)
        //     shouldBeCalled()->willReturn($value);

        // Assertion - Usage
        $usingSodium = new UsingSodium(
            // $dependency->reveal(),
        );
        $this->assertSame(true, $usingSodium instanceof UsingSodium::class));
        // $this->assertSame($value, $usingSodium->call(
        //     $fixture,
        // ));
    }
}
```

  Scenario: Identify integration points for PHPUnit
    Given I understand PHPSpec's code generation workflow
    When I explore PHPUnit's structure and testing methodology
    Then I should identify the areas where code generation can be integrated
    And I should determine how to adapt PHPSpec's approach for PHPUnit

  Scenario: Design a code generation tool for PHPUnit
    Given I have analyzed PHPSpec's workflow and identified integration points
    When I design a code generation tool for PHPUnit
    Then it should generate code stubs compatible with PHPUnit's testing framework
    And it should support creating classes and methods based on test specifications

  Scenario: Prototype and test the new code generation tool
    Given I have designed the code generation tool for PHPUnit
    When I implement a prototype of the tool
    Then I should test it with sample PHPUnit test cases
    And it should correctly generate the necessary PHP class files and method stubs

  Scenario: Evaluate and refine the tool
    Given I have a working prototype of the PHPUnit code generation tool
    When I gather feedback from other developers
    Then I should evaluate its effectiveness and usability
    And I should refine the tool based on feedback to improve performance and user experience

```
