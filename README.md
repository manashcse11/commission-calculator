## Commission calculator

### How to run
After cloning the repo copy .env.example & rename to .env then run below commands in the project root

`composer install`

`php artisan serve`

Project is ready to open in the browser, if port 8000 is free then you can just use http://127.0.0.1:8000/

Now you will get an upload CSV form.

### Input data
Operations are given in a CSV file. In each line of the file the following data is provided:

1. operation date in format Y-m-d
2. user's identificator, number
3. user's type, one of private or business
4. operation type, one of deposit or withdraw
5. operation amount (for example 2.12 or 3)
6. operation currency, one of EUR, USD, JPY

### Test
Copy .env.testing.example & rename it to .env.testing

Then run below command to test

`php artisan test --env=testing`
