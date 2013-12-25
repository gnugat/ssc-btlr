# Fossil: bootstrap your project documentation

Are you fed up at creating the same markdown files to document your new FOSS
projects? You're in luck: fossil will help you bootstrap them!

Read more about this project in [its introduction](doc/01-introduction.md).

## Features

The `fossil doc` command will create the documentation of your project with the
following skeletons:

* `CHANGELOG.md`
* `CONTRIBUTING.md`
* `README.md`
* `VERSIONING.md`
* `doc` (or `Resources/doc` if the project is a bundle) directory:
  - `01-introduction.md`
  - `02-installation.md`
  - `03-usage.md`
  - `04-tests.md`

Find out how to use it with the [usage guide](doc/03-usage.md).

## Installation

To download and install this project, run the following command:

    curl -sS https://raw.github.com/gnugat/fossil/master/bin/installer.sh | sh

Learn more about the steps followed by the script by reading its [documentation](doc/02-installation.md).

## Further documentation

You can see the current and past versions using one of the following:

* the `git tag` command
* the [releases page on Github](https://github.com/gnugat/fossil/releases)
* the file listing the [changes between versions](CHANGELOG.md)

You can find more documentation at the following links:

* [copyright and MIT license](LICENSE)
* [versioning and branching models](VERSIONING.md)
* [contribution instructions](CONTRIBUTING.md)
* [documentation directory](doc)
