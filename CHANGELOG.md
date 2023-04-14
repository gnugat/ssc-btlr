# Changes between versions

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
