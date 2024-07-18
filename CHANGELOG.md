# Changes between versions

## 0.8.0 Introducing `cdr:generate-prompt-from-template`

* `cdr:generate-prompt-from-template`:
  * Retrieve test class code example content from given filename
  * Retrieve corresponding class code example content from given filename
  * Retrieve test classe code content given from filename
  * Read template file content and replace its placeholders, to generate prompt
  * Display generated prompt in terminal

## 0.7.0 Introducing `cdr:generate-class-from-template`

* `cdr:generate-class-from-template`:
  * Find class filename based on the given FQCN and composer's autoload config
  * Retrieve namespace placeholder value from the given FQCN
  * Retrieve class name placeholder value from the given FQCN
  * Read template file content and replace its placeholders, to generate code
  * Write generated code in filename

## 0.6.1: `cht:message` BTLR updated its Augmented Prompt

* `cht:message`:
  * added `--config-last-messages-size` option
  * whitespace prompt optimisation
  * used BTLR's `augmented_prompt` template modifications

## 0.6.0: `cht:message` YAML

* `cht:message`:
  * switched from JSON to YAML

## 0.5.0: `cht:message` with Memory Extract

* `cht:message`:
  *  summarize and archive old messages
  *  include old message summaries in augmented prompt (all)

## 0.4.0: `cht:message` refactoring

* `cht:message`:
  * log user prompt
  * create augmented prompt
  * include latest logs in augmented prompt
  * log augmented prompt
  * send augmented prompt to LLM for model completion (cli)
  * log model completion

Removed:
* `cht:augment`: use `cht:message` instead

## 0.3.0: `cht:augment` with Last Messages

* `cht:augment`:
  * include latest logs in augmented prompt

## 0.2.0: Introducing `cht`

Infinite Memory for LLMs, by augmenting prompts.

* `cht:augment`:
  * log user prompt
  * create augmented prompt
  * log augmented prompt
  * send augmented prompt to LLM for model completion (cli)
  * log model completion

## 0.1.0: Introducing `lck`

Keep your secrets safe.

* `lck:generate-keys`:
  * generates new pair of encrypting/decrypting keys 

## 0.0.0: Introducing `btlr`

You own personal assistant.

* `list-commands`:
  * lists available commands
