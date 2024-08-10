# cdr: Code Generation

Bootstrap your code, for maximum efficiency.

## Why?

Honestly? I just miss PHPspec and Fossil.

[PHPspec](http://gnugat.github.io/2015/08/03/phpspec.html) allows you to
bootstrap a spec class through a command that only takes a
Fully Qualified Classname as an argument. Once the test written, you'd run the
specs and phpspec would bootstrap the missing class or method.

It's brilliant but requires you to use it as your test framework, so projects
that don't use it miss out on its great code generation features.

[Fossil](http://gnugat.github.io/2014/01/15/bootstrap-markdown-files-of-your-FOSS-project.html)
was a pet project that'd bootstrap new projects and libraries for me,
by copying an example folder containing template documentation files,
and replacing some of the placeholders with given values such as the project's
name and author.

It was great but kinda fell in disrepair... Fun fact: it got renamed to Btlr!
My goal has always been to bring it back.

That's where the `cdr` commands come in handy.

## How?

By using templates.

Project creation is done by having a base folder to copy, containing template
documentation files such as README, LICENSE, VERSIONING, etc.

The command copies it, then replaces the placeholders:

* project name
* author

Once the project is bootstrapped, it's time to write the first test which is
done by having an application (API endpoint, CLI command or Web page) test
class template file.

The command copies it, then replaces the placeholders:

* based on the test class FQCN (Fully Qualified Class Name, that's the class
  name complete with its full namespace)

Next it's the turn of the code corresponding to that application test to be
written, which is done by having an application class template file.

The command copies it, then replaces the placeholders:

* based on the test error message: it should provide us with the test filename
  as well as the FQCN of the missing class!

After that, we need a Service test, which is done again with a test class
template file. The command copies it and replaces the placeholder based on the
test class FQCN.

Finally, the code for that gets written using a service class template file,
and placeholders are replaced based on the test failure message.

### Simple Placeholders

In the most simple use case, being given a FQCN
(`tests\Author\Project\Folder\MyClassTest`), using a template with only the two
following placeholders can do the trick:

* namespace (eg `tests\Author\Project\Folder)
* class name (eg `MyClassTest`)

Here's what the template could look like:

```
<?php

namespace %namespace%;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class %class_name% extends TestCase
{
    #[Test]
    public function it_()
    {
        $this->assertSame(true, true);
    }
}
```

### LLM code generation

Another possibility would be to have a command that generates prompts for LLMs
(Large Language Model).

The idea would be to have a template prompt with placeholders for code snippets:

* test class code example
* code class code example
* current test code class

Here's what the template prompt could look like:

> Your task is to generate the code corresponding to a given test, using PHP 8.4
> and Symfony 2.3.
> 
> For example, here's a test class:
> 
> ```php
> %test_class_code_example%
> ```
>
> And here's its the corresponding code:
>
> ```php
> %test_class_code_example%
> ```
>
> Now, write the code for the following test class:
> 
> ```php
> %test_class_code%
> ```

## What?

You can find more information in the `./doc/00-cdr/` directory.
