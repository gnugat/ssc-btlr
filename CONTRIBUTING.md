# How to contribute

Everybody should be able to help. Here's how you can make this project more
awesome:

1. [Fork it](https://github.com/gnugat/ssc-btlr/fork_select)
2. improve it
3. submit a [pull request](https://help.github.com/articles/creating-a-pull-request)

Your work will then be reviewed as soon as possible (suggestions about some
changes, improvements or alternatives may be given).

Here's some tips to make you the best contributor ever:

* [Green tests](#green-tests)
* [Keeping your fork up-to-date](#keeping-your-fork-up-to-date)

## Green tests

Run the tests using the following script:

    bin/test.sh

To stop the tests at the first failure, use:

    bin/test.sh -u

If there are any Coding Style issues, fix them using:

    bin/test.sh -f

## Keeping your fork up-to-date

To keep your fork up-to-date, you should track the upstream (original) one
using the following command:

    git remote add upstream https://github.com/gnugat/ssc-btlr.git

Then get the upstream changes:

    git checkout main
    git pull --rebase origin main
    git pull --rebase upstream main
    git checkout <your-branch>
    git rebase main

Finally, publish your changes:

    git push -f origin <your-branch>

Your pull request will be automatically updated.
