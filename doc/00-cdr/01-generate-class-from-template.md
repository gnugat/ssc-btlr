## Generate Class From Template

TODO:

* `cdr:generate-class-from-template`:
  * [x] Find class filename based on the given FQCN and composer's autoload config
  * [x] Retrieve namespace placeholder value from the given FQCN
  * [x] Retrieve class name placeholder value from the given FQCN
  * [x] Read template file content and replace its placeholders, to generate code
  * [x] Write generated code in filename

Usage:

```
./btlr cdr:generate-class-from-template \
  --project-filename ./ \
  --composer-config-filename composer.json \
  --composer-parameter-namespace-path-map '$.autoload-dev.psr-4.*[0]' \
  --class-template-filename ./templates/cdr/btlr/cli-test-class.php.tpl \
  --class-fqcn 'tests\'
```

This will print:

```

  [SUCCESS] Created file <classFilename>

```
