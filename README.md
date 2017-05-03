# NetworkPathTest

**Description**

Usage:
php networkPath *\<csv filepath\>*

csv file structure should contain the following data in this order:

*\<device from\>*,*\<device to\>*,*\<latency\>*

Example:
```
A,B,10
A,C,20
B,D,100
C,D,30
D,E,10
E,F,1000
```

Sample csv input can be found in /sample folder