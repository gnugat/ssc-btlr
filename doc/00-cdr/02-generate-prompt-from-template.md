## Generate Class From Template

TODO:

* `cdr:generate-prompt-from-template`:
  * [x] Retrieve test class code example content from given filename
  * [x] Retrieve corresponding class code example content from given filename
  * [x] Retrieve test classe code content given from filename
  * [x] Read template file content and replace its placeholders, to generate prompt
  * [x] Display generated prompt in terminal

Usage:

```
./btlr cdr:generate-prompt-from-template \
    --prompt-template-filename ./templates/cdr/btlr/prompts/generate-code-corresponding-to-test-class.md.tpl \
    --test-class-code-example-filename ./tests/ \
    --corresponding-class-code-example-filename ./src/ \
    --test-class-code-filename ./tests/
```

This will print:

```

  [SUCCESS] Prompt:

<prompt>
```
