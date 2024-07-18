# cdr: Workflow Example

**Warning**: This is a WIP documentation page.

### Test failures

When running the tests, the following failures can happen.

#### Specifying a new Dependency

When writing a test, and adding a new dependency, we'll be creating the
following Dummy Creation:

```
// File: tests/Cdr/Generate/ClassFromTemplateTest.php

        // Dummy
        $buildPath = $this->prophesize(BuildPath::class);
```

Running the tests will result in a failure with the following Prophecy
Exception, complaining that the class cannot be found:

```
Prophecy\Exception\Doubler\ClassNotFoundException: Cannot prophesize class Ssc\Btlr\App\Filesystem\BuildPath, because it cannot be found.

~/Projects/gnugat/ssc-btlr/vendor/phpspec/prophecy/src/Prophecy/Prophet.php:96
~/Projects/gnugat/ssc-btlr/vendor/phpspec/prophecy-phpunit/src/ProphecyTrait.php:56
~/Projects/gnugat/ssc-btlr/tests/Cdr/Generate/ClassFromTemplateTest.php:36
```

This should prompt us to create a Service test for it:

```
<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\App\Filesystem;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Filesystem\BuildPath;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class BuildPathTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_(): void
    {
        $buildPath = new BuildPath();

        self::assertSame(true, true);
    }
}
```

Now there should be a second error, complaining about the class not being found:

```
Error: Class "Ssc\Btlr\App\Filesystem\BuildPath" not found

~/Projects/gnugat/ssc-btlr/tests/App/Filesystem/BuildPathTest.php:17
```

So it's time to create the actual class:

```
<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem;

class BuildPath
{
}
```

Now the tests should run smoothly. Time to write some Stubs and Mocks:

```
// File: tests/Cdr/Generate/ClassFromTemplateTest.php

        // Stubs & Mocks
        $buildPath->joining('.', 'composer.json')
            ->shouldBeCalled()->willReturn('./composer.json');
```

This will result in the following Prophecy Exception:

```
Prophecy\Exception\Doubler\MethodNotFoundException: Method `Double\Ssc\Btlr\App\Filesystem\BuildPath\P7::joining()` is not defined.

~/Projects/gnugat/ssc-btlr/vendor/phpspec/prophecy/src/Prophecy/Prophecy/MethodProphecy.php:72
~/Projects/gnugat/ssc-btlr/vendor/phpspec/prophecy/src/Prophecy/Prophecy/ObjectProphecy.php:267
~/Projects/gnugat/ssc-btlr/tests/Cdr/Generate/ClassFromTemplateTest.php:44
```

Time to write tests for that method I guess:

```
// File: tests/App/Filesystem/BuildPathTest.php

    #[Test]
    public function it_builds_a_path_by_joining_sub_paths(): void
    {
        $buildPath = new BuildPath();

        self::assertSame(true, $buildPath->joining());
    }
```

This will raise an Error:

```
Error: Call to undefined method Ssc\Btlr\App\Filesystem\BuildPath::joining()

/home/loic-faugeron/Projects/gnugat/ssc-btlr/tests/App/Filesystem/BuildPathTest.php:17
```

Time to add the `joining` method to `BuildPath`:

```
// File: src/App/Filesystem/BuildPath.php

    public function joining()
    {
    }
```

New errors:

```
1) tests\Ssc\Btlr\App\Filesystem\BuildPathTest::it_builds_a_path_by_joining_sub_paths
Failed asserting that null is identical to true.

~/Projects/gnugat/ssc-btlr/tests/App/Filesystem/BuildPathTest.php:17

2) tests\Ssc\Btlr\Cdr\Generate\ClassFromTemplateTest::it_generates_class_from_template
Some predictions failed:
Double\Ssc\Btlr\App\Filesystem\BuildPath\P7:
  No calls have been made that match:
      Double\Ssc\Btlr\App\Filesystem\BuildPath\P7->joining(exact("."), exact("composer.json"))
    but expected at least one.

~/Projects/gnugat/ssc-btlr/vendor/phpspec/prophecy-phpunit/src/ProphecyTrait.php:72
```

This calls for injecting the dependency:

```
// File: tests/Cdr/Generate/ClassFromTemplateTest.php


        // Assertion
        $classFromTemplate = new ClassFromTemplate(
            $buildPath->reveal(),
        );
```

New Error:

```
1) tests\Ssc\Btlr\Cdr\Generate\ClassFromTemplateTest::it_generates_class_from_template
TypeError: Ssc\Btlr\Cdr\Generate\ClassFromTemplate::__construct(): Argument #1 ($get) must be of type Ssc\Btlr\App\JsonPath\Get, Double\Ssc\Btlr\App\Filesystem\BuildPath\P7 given, called in ~/Projects/gnugat/ssc-btlr/tests/Cdr/Generate/ClassFromTemplateTest.php on line 80

~/Projects/gnugat/ssc-btlr/src/Cdr/Generate/ClassFromTemplate.php:16
~/Projects/gnugat/ssc-btlr/tests/Cdr/Generate/ClassFromTemplateTest.php:80
```

Fix it:

```
// File: src/Cdr/Generate/ClassFromTemplate.php

use Ssc\Btlr\Filesystem\BuildPath;

    public function __construct(
        private BuildPath $buildPath,
    ) {
    }

        $composerConfigPath = $this->buildPath->joining($projectFilename, $composerConfigFilename);
```

Error:
