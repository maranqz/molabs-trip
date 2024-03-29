# How to run

Domain: symfony.localhost

## Docker
`make init`

## To run tests

`make test`

In docker - `make test.docker`

# The requirements for the test project are:

- Write an REST API (using any Symfony version) for tracking trips to different countries in Europe and Asia
- Create a sync for all countries in Europe and Asia from https://restcountries.eu/ (Name, 3 Letter-Code, Region)
- Users need to create an account (username, password, email) and login
- Users should be able to update and delete their account
- Users should be able to create, update and delete trips to countries
- Trips are only visible to the user who created them
- A trip needs to have following informations: Country, Start date, End date and notes
- A trip can start at the end date of another trip, but they are not allowed to overlap
- Users should be able to search their trips by date range and/or country

# Bonus:
- Documentation
- Unit-Tests
