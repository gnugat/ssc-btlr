# sfQuickCommands

Scripts allowing to speed up your workflow/process by running sets of daily
Symfony2 commands (composer, doctrine, cache, etc).

## Commands

### init

Basically, this command downloads composer and sets the execution rights.
You should run this command only when to install sfQuickCommands.

`init`

### new

This command allows you to create a new Symfony2 project by:

* creating the project directory and installing Symfony2 in it;
* creating the virtual hosts;
* creating the log files;
* reloading the apache2 configuration;
* creating the hostname;
* setting the rights for the `app/cache` and `app/logs` directories.

`new absolute-path project-name`

### rights

Sets the ACL rights on the given directory.

`rights directory-path`

## Documentation

You can find more documentation at the following links:

* Copyright and MIT license: `./LICENSE.md`;
* version and change log: `./VERSION.md` and `CHANGELOG.md`;
* versioning, branch and public API models: `./VERSIONING.md`.

## Contributing

1. [Fork it](https://github.com/gnugat/GnugatQuickCommandsBundle/fork_select) ;
2. create a branch (``git checkout -b my_branch``);
3. commit your changes (``git commit -am "Changes description message"``);
4. push to the branch (``git push origin my_branch``);
5. [create an Issue](https://github.com/gnugat/GnugatQuickCommandsBundle/issues)
   with a link to your branch and a description of what have been done;
6. wait for it to be accepted/argued.
