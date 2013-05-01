# sfQuickCommands

Scripts allowing to speed up your workflow/process by running sets of daily
Symfony2 commands (project creation, database re-creation, etc).

## Installation

You should first clone this repository, go inside it
and then run the initialization script:

    git clone git://github.com/gnugat/sfQuickCommands.git
    cd sfQuickCommands
    sh init

The initialization script will download [composer](http://getcomposer.org/)
(the PHP dependency manager), and set the execution rights on the scripts.

## Usage

You can run the scripts from your project directory, except for the one that
creates a new Symfony2 project for which you need to go inside the
sfQuickCommands root directory.

To learn more about the available commands and their usage,
read the [documentation](doc/01-index.md).

## Commands

### new

This command allows you to create a new Symfony2 project by:

* creating the project directory and installing Symfony2 in it;
* creating the virtual hosts;
* creating the log files;
* reloading the apache2 configuration;
* creating the hostname;
* setting the rights for the `app/cache` and `app/logs` directories.

Usage:

    new absolute-path project-name

### rights

Sets the ACL rights on the given directory.

Usage:

`rights directory-path`

### db

This command re-creates your database by:

 * dropping the database;
 * creating the database;
 * creating the schema.

Usage:

    db

## Further documentation

You can find more documentation at the following links:

* Copyright and MIT license: [LICENSE.md](LICENSE.md);
* version and change log:
  [VERSION.md](VERSION.md) and [CHANGELOG.md](CHANGELOG.md);
* versioning, branch and public API models: [VERSIONING.md](VERSIONING.md).

## Contributing

1. [Fork it](https://github.com/gnugat/sfQuickCommands/fork_select) ;
2. create a branch (``git checkout -b my_branch``);
3. commit your changes (``git commit -am "Changes description message"``);
4. push to the branch (``git push origin my_branch``);
5. [create an Issue](https://github.com/gnugat/sfQuickCommands/issues)
   with a link to your branch and a description of what have been done;
6. wait for it to be accepted/argued.
