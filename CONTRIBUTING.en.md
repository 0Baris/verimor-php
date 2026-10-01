# Contributing

This repository contains the distributable PHP SDK. Do not edit generated files by hand; OpenAPI and proxy-generation changes belong in the private generator and arrive through the guarded export.

1. Preserve PHP 7.4 compatibility; avoid newer syntax such as union types, attributes, and named arguments.
2. Add a failing regression test before changing behavior.
3. Run `composer validate --strict`, `composer check`, and the clean consumer test.
4. Never use real credentials or the live Verimor service in tests; use localhost fixtures only.
5. Update both Turkish and English documentation for public API changes.

When reporting generated-code defects, include the product, operation ID, expected request, and observed request after removing secrets.
