# Project Name - Publications Caching API

-------------------------------------------------------------------------------------------------------------------------------------

## Author - Ravi Kiran Allipilli

-------------------------------------------------------------------------------------------------------------------------------------

## Overview
This project is a simple publications/DOI caching API built with Laravel. It aims to provide a single source of truth for a variety of publication sources by allowing third parties to query using a correctly formatted full or partial DOI. If the record exists in the cache server, the record or records are returned; otherwise, the API will poll the publications data provider (CrossRef) and store the result in the cache.

-------------------------------------------------------------------------------------------------------------------------------------

## Requirements
- PHP 8.3.6
- Laravel 11.11.1
- MYSQL (default configuration, can be switched to another database if needed)

-------------------------------------------------------------------------------------------------------------------------------------

## Installation
1. Clone the repository:
   ```sh
   git clone https://github.com/raviallipilli/publications-cache-api.git
   cd publications-cache-api

2. Install dependencies:
 ```sh
composer install

3. Set up environment variables:
 ```sh
cp .env.example .env
php artisan key:generate

4. Set up the database:
 ```sh
php artisan migrate

-------------------------------------------------------------------------------------------------------------------------------------

### Usage
1. Start the development server:
 ```sh
php artisan serve

2. Use the following endpoint to fetch publication data:

HTTP Methods: GET /publication/works/?doi={DOI}/agency
 ```bash
3. Replace {DOI} with the full or partial DOI of the publication.
Example Requests
Fetch a publication:
 ```bash
HTTP GET /publication/works/?doi=10.1038/nature12373/agency

-------------------------------------------------------------------------------------------------------------------------------------

### Running Tests

To run the tests, use:
 ```sh
php artisan test

-------------------------------------------------------------------------------------------------------------------------------------

### Code Overview
Controller: PublicationController.php
Handles incoming requests to fetch publication data by DOI. It first checks the local cache and, if not found, fetches the data from CrossRef and stores it in the cache.

Model: Publication.php
Represents the publications table in the database.

Migration: create_publications_table.php
Defines the structure of the publications table with columns for the DOI, publication data (stored as JSON), and timestamps.

Tests: PublicationControllerTest.php
Contains feature tests to ensure the functionality of fetching publication data from the cache and CrossRef API.

-------------------------------------------------------------------------------------------------------------------------------------
## Follow-up Questions
# Detailed Explanation

Libraries and Usage:
    Laravel HTTP Client: 
        Used to make HTTP requests to the CrossRef API. This library is built into Laravel, making it straightforward to use and integrate within the Laravel ecosystem. It provides a fluent interface for making HTTP requests, handling responses, and managing errors.
    PHPUnit: 
        Utilized for writing and executing tests. PHPUnit is the standard testing framework for PHP and integrates seamlessly with Laravel. It provides tools to write unit and feature tests, ensuring that the application behaves as expected.

Improvements:
    Error Handling:
        Improve error handling for various edge cases, such as network failures, invalid DOI formats, and unexpected API responses.
        Implementing custom exception handling and more descriptive error messages would enhance the API's robustness and user experience.
    Caching Strategy:
        Implement a more robust caching strategy, possibly with an external caching service like Redis for better performance and scalability.
        This would help manage cache invalidation and expiration policies more effectively, ensuring that the cache stays up-to-date without overloading the CrossRef API with frequent requests.

Key Areas Which Worked Well:
    API Design:
        The API is designed to be simple yet effective, handling both cache checks and fetching from CrossRef seamlessly.
        The clear separation of concerns in the controller ensures that the logic for checking the cache, fetching from the API, and storing data is well-organized and maintainable.
    Testing:
        Comprehensive tests ensure the API works as expected and that publications are correctly fetched and stored.
        Mocking the HTTP client during tests ensures that tests are isolated and do not rely on the external CrossRef API, which improves test reliability and speed.

Time Spent and Challenges:
    HTTP Client Integration:
        Integrating the HTTP client with the CrossRef API and handling its responses was a bit challenging. This involved configuring the HTTP client to handle SSL verification issues and ensuring that the responses were correctly parsed and handled.
    Testing:
        Writing tests that accurately simulate the behavior of the CrossRef API and ensure the caching mechanism works correctly took some time.
        This required mocking HTTP responses and ensuring that the tests covered both cache hits and misses, validating that the application behaves correctly in both scenarios.

-------------------------------------------------------------------------------------------------------------------------------------

## Documentation:
The project is fully documented with comments where necessary.

-------------------------------------------------------------------------------------------------------------------------------------

## Sources:
https://github.com/CrossRef/rest-api-doc

The API will only work for Crossref DOIs. You can test the registration agency for a DOI using the following route:

https://api.crossref.org/works/{doi}/agency

Testing the following Crossref DOI:

10.1037/0003-066X.59.1.29

Using the URL:

https://api.crossref.org/works/10.1037/0003-066X.59.1.29/agency

Will return the following result:

-- json : 
        {
            status: "ok",
            message-type: "work-agency",
            message-version: "1.0.0",
            message: {
                DOI: "10.1037/0003-066x.59.1.29",
                agency: {
                id: "crossref",
                label: "Crossref"
                }
            }
        }

--------------------------------------------------------END TASK---------------------------------------------------------------------