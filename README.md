# [Clippings.com](https://clippings.com) invoicing command challange

Clipping's challanges you to create a PHP console command, that lets you sum invoice documents in different currencies via a file.

This is a small task to evaluate potential hires.

## The task

We have a **CSV** file, containing a list of invoices and credit notes in different currencies. Create a ClI command taking the CSV file as an input, a list of currency and exchnage rates, an output currency, an optional param to specify and filter a specific customer and return the sum of all the documents.

If the optional param is passed, the command should return the summed documents, only for that specified customer.

Note, that if we have a credit note, it should subtract from the total of the invoice and if we have a debit note, it should add to the sum of the invoice.

## Set-up

For this project composer is needed, so that we can load the autoloader. Run
`composer install` and `composer dump-autoload -o`.

## My approach
I have assumed that the data inside the CSV will be in the required format and that
CSV structure will be as in the example. As I was short of time, the unit tests cover
do not cover the whole project -> `../../vendor/bin/phpunit nameOfTest` to run tests

## To improve
I would further improve my calculation function to be more compact and would 
think of ways to move to some sort of DB set-up, which will ease the process of 
defining if statements and looping through tmp arrays. Furthermore, I would test 
every function presented.
