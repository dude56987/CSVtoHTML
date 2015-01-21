csvTohtml
=========

Convert CSV documents made in a spreadsheet program into HTML webpages.

##Spreadsheet Format

In the first cell of each row identify the date you want the row used on.

	The format is 1/2/1970 for January first 1970

The second cell on a row must be the time identifier. Valid identifyers are as follows...
- BREAKFAST
- LUNCH
- DINNER
- LATEMEAL

In the following cells you can add content. Content can be the following...
- Items
 - Items may be added by simply using text
- Headers
 - Use a # at the begining of the frame, what follows will be a header
- Blank Space
 - Put a single # into the frame to add two blank lines

The amount of content that can be added is unlimited. You may also add html and css inside items.

##Example
https://raw.githubusercontent.com/dude56987/CSVtoHTML/master/example.csv
