# Prompt Templates

Here's some research done to write the template for the augmented prompt.

## Credits

None of the work here would have been possible without the brilliant
[videos](https://www.youtube.com/@DavidShapiroAutomator),
[repos](https://github.com/daveshap),
and [books](https://github.com/daveshap?tab=repositories&q=book)
from David Shapiro ([check his Patreon](https://www.patreon.com/daveshap)).

Everything here is inspired and borrowed from his tutorials, essays and code.

## The Minimum Goal

The augmented prompt needs at the very least to fulfill the following goals:

* provide the LLM with an identity
  (AI chatbot named BTLR)
* provide BTLR with guiding ethical principles,
  see [Heuristic Imperatives](https://github.com/daveshap/HeuristicImperatives)
* provide BTLR with conversational guidance
  (oriented towards curiosity, proactivity, communicativity and informativity)

The augmented prompt needs to include in some capacity the user prompt,
and invite the LLM to complete it in the style of a conversation reply,
which follows the above guidance.

The initial ambition is to additionally include:

* recent messages from the conversation,
  to provide temporal memory
* older summarised relevant messages from the conversation,
  to provide associative memory

Then the next step would be to include access to external resources.

## History

The evolution of the different prompt templates is documented in the following
file: `./doc/02-cht/01-augment/01-prompt_templates/01-history.md`.
