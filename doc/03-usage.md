# Usage

Available commands:

* [dependency:inject](#the-dependencyinjection-command)
* [doc](#the-doc-command)
* [doc:library](#the-doclibrary-command)
* [doc:bundle](#the-docbundle-command)

## The `dependency-injection` command

You can see the synopsis of this command by running:

    fossil dependency:inject --help | less

### Arguments

* `fully-qualified-classname`: the full namespace with the classname of the
  class to inject
* `filename`: the path to the class's file where the dependency should be injected

### Example:

    fossil d:i 'Gnugat\Fossil\Dependency' ./src/Gnugat/Fossil/Class.php

## The `doc` command

The documentation commands use [skeletons](../skeletons) to create the
following files:

* `CHANGELOG.md`
* `CONTRIBUTING.md`
* `README.md`
* `VERSIONING.md`
* `bin/installer.sh`
* `doc` (or `Resources/doc` if the project is a bundle) directory:
    - `01-introduction.md`
    - `02-installation.md`
    - `03-usage.md`
    - `04-tests.md`

By default, if the file already exists it is not overwritten, which means you
can safely run **fossil** on existing projects. However if you want to replace
the existing files, use the `-f` option.

You can see the synopsis of this command by running:

    fossil doc --help

### Arguments

* `github-repository`: used to create links to github,
  should be in the following format: `username/repository-name`
* `author`: used for the license's copyright

### Options

* `--path` (or `-p`): used to write the files,
  the default is the current directory
* `--force-overwrite` (or `-f`): use it if you want to overwrite existing files

### Example:

    fossil doc 'acme/demo' 'The ACME company'

## The `doc:library` command

You can see the synopsis of this command by running:

    fossil doc:library --help

### Arguments

* `github-repository`: used to create links to github,
  should be in the following format: `username/repository-name`
* `author`: used for the license's copyright

### Options

* `--path` (or `-p`): used to write the files,
  the default is the current directory
* `--force-overwrite` (or `-f`): use it if you want to overwrite existing files
* `--composer-package` (or `-c`): used for the installation's documentation and
  script, by default is the same as the `github-repository` argument

### Heads up!

You can run this command using its shortcut: `fossil d:l`

Example:

    fossil d:l 'acme/demo-lib' 'The ACME company'

## The `doc:bundle` command

You can see the synopsis of this command by running:

    fossil doc:bundle --help

### Arguments

* `github-repository`: used to create links to github,
  should be in the following format: `username/repository-name`
* `author`: used for the license's copyright
* `fully-qualified-classname`: used to register the bundle in the application's
  kernel (put this argument inside quotes, the shell won't like the `\`)

### Options

* `--path` (or `-p`): used to write the files,
  the default is the current directory
* `--force-overwrite` (or `-f`): use it if you want to overwrite existing files
* `--composer-package` (or `-c`): used for the installation's documentation and
  script, by default is the same as the `github-repository` argument
* `--is-development-tool` (or `-d`): used to register the bundle in the
  application's in development and test environments

### Heads up!

You can run this command using its shortcut: `fossil d:b`

Example:

    fossil d:b 'acme/demo-bundle' 'The ACME company' 'Acme\DemoBundle\AcmeDemoBundle'

## Previous readings

* [introduction](01-introduction.md)
* [installation](02-installation.md)
