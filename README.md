# Rainbow's Discount Service

Requirements: php 7.4, ext-json, composer

Usage:

	$ composer install
	$ ./serve.sh
	$ curl localhost:9000/apply-discounts -X POST -d '{"id": "1", "customer-id": "1", "items": [], "total": "0.00"}'

Running tests:

	$ vendor/bin/phpunit
	$ vendor/bin/phpstan a

## Notes

### Questions & Assumptions

These are things I'd get answers to before starting, if blocking for this exercise I just made assumptions:

* Are some discounts mutually exclusive? (Assumed no.)
* What other sorts of discounts could I expect in the future?
* Will there be very large orders or very large numbers of discount rules? (Assumed no.)
* Should discount rules be configurable/manageable via some UI?
* Do we need to persist the discounts, or is this a pure/functional service? (Assumed pure/functional.)

### Framework setup

I set up a clean app with symfony framework.
Disabled those parts of the framework we don't use, especially `session` since we're making a stateless API.

Also installed and set up phpstan & phpunit.
I skipped setting up a code style checker/fixer but that should definitely be included in a 'real' project.

### Architecture

I split the code up in three major layers.

* `Application` contains controllers & listeners which I try to keep very lean.
Parsing and serializing requests/responses also belongs in this layer.
* `Domain` contains the meat of the app: models, value objects, etc.
This layer should be strictly decoupled from symfony framework (though using components in isolation is ok), HTTP, persistence, etc.
* Finally `Infrastructure` contains concrete implementations for interfaces the domain defines that involve persistence, I/O, etc.
There shouldn't be any business logic here, except what is made explicit by those interfaces.

### Modeling a solution

I focused on modeling discount rules using composable parts which I called selectors & effects.
Selectors are used to select which subset(s) of an order the rule applies to.
Effects specify how to apply the discount on each subset of the order.
The desired outcome was that each object has very little responsibility, can be tested individually, and reused.
It did make the model more complex. Whether this is a good trade-off depends on the use case, but for this exercise I thought it would be fun to do :).

### Infrastructure

Since it doesn't make any difference for the domain code, I didn't bother writing realistic implementations for the repositories.

### Tests

My preferred way to test an app like this are integration tests like `DiscountServiceTest`.
I set up a symfony `test` env and replace infrastructure service definitions with stubs.
In this case the only implementations I wrote are stubby enough.

### What would I do next?

* Write missing unit tests, more integration tests.
* Harden parsing and error handling: add error codes, etc.
* Write real implementations for repositories.
* Add some logging, especially a warning when a product can't be found.
* Test for rounding errors.
* ...
