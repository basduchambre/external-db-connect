# External DB Connect - Laravel package

This module will connect through an external database to periodically pull in data stored in another database. The initial idea behind this is to count the records for reporting purposes within the Visitor Interaction Platform.

## Requirements

- Visitor Interaction Platform Core API
- Locations must be activated in order to set location ID
- Remote access has to be enabled on the database that will be called upon

## Prerequisites for package development

- Allow access from source server to target database server.

## Package includes the following

- Service provider
- Migration file (Artisan command generated)
    - Metrics
    - Measured at
    - Timezone(?)
- Config file
    - External DB connect settings
    - Measurement interval in minutes, e.g. 1 hour will be (int) 60
    - Metrics to pull identified by column name as array, e.g. ['email_address', 'zip_codes', 'location']
- Artisan command to generate migration based on config
- Artisan command, e.g. `php artisan external-db:fetch`
- Controllers

## Deliverables

By creating a composer package it will be easy to add this to the core API, or leave it out when not needed.
