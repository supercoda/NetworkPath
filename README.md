# NetworkPathTest

## Usage

    > php networkPath <csvfilepath>

## CSV File Structure

csv file structure should contain the following data in this order:
```
<device from>,<device to>,<latency>
```

## Example CSV Content
```
A,B,10
A,C,20
B,D,100
C,D,30
D,E,10
E,F,1000
```

Some sample csv file can be found in /sample folder

## Test
Firstly, download all the dependencies

    > php composer.phar install

Run tests!

    > vendor/bin/phpunit tests/NetworkPathTest.php