<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil;

use Gnugat\Medio\Command\InjectDependencyCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class DependencyInjectCommand extends Command
{
    const RETURN_SUCCESS = 0;

    /**
     * @var InjectDependencyCommand
     */
    protected $injectDependencyCommand;

    /**
     * @param InjectDependencyCommand $injectDependencyCommand
     */
    public function __construct(InjectDependencyCommand $injectDependencyCommand)
    {
        $this->injectDependencyCommand = $injectDependencyCommand;

        parent::__construct();
    }

    /** {@inheritdoc} */
    protected function configure()
    {
        $this->setName('dependency:inject');
        $this->setDescription('Edits a PHP class to add a new class dependency');

        $this->addArgument(
            'fully-qualified-classname',
            InputArgument::REQUIRED,
            'the full namespace with the classname of the dependency to inject'
        );
        $this->addArgument(
            'filename',
            InputArgument::REQUIRED,
            'the path to the service\'s file where the dependency should be injected'
        );
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fullyQualifiedClassname = $input->getArgument('fully-qualified-classname');
        $filename = $input->getArgument('filename');

        return $this->injectDependencyCommand->run($fullyQualifiedClassname, $filename);
    }

    /** {@inheritdoc} */
    public function getHelp()
    {
        return <<<'EOD'
# Inject dependencies

Consider the following class:

```php
<?php
// File: fixture/Gnugat/Medio/Service.php

namespace fixture\Gnugat\Medio;

class Service
{
    public function __construct()
    {
    }
}
```

Fossil provides an easy way to inject its first dependency:

    fossil dependency:inject 'fixture\Gnugat\Medio\SubDir\Dependency' ./fixture/Gnugat/Medio/Service.php

> **Note**: You use the shortcut `d:i` instead of the full name `dependency:inject`.

It will edit the class and leave it as follows:

```php
<?php
// File: fixture/Gnugat/Medio/Service.php

namespace fixture\Gnugat\Medio;

use fixture\Gnugat\Medio\SubDir\Dependency;

class Service
{
    private $dependency;

    public function __construct(Dependency $dependency)
    {
        $this->dependency = $dependency;
    }
}
```

Fossil can also inject dependencies when existing ones are already present:

    fossil d:i 'fixture\Gnugat\Medio\OtherDependency' ./fixture/Gnugat/Medio/Service.php

> Note: `OtherDependency` is located in the same directory as `Service`, Medio
> will detect it and won't insert its unnecessary use statement.

```php
<?php
// File: fixture/Gnugat/Medio/Service.php

namespace fixture\Gnugat\Medio;

use fixture\Gnugat\Medio\SubDir\Dependency;

class Service
{
    private $dependency;

    private $otherDependency;

    public function __construct(Dependency $dependency, OtherDependency $otherDependency)
    {
        $this->dependency = $dependency;
        $this->otherDependency = $otherDependency;
    }
}
```
EOD
        ;
    }
}
