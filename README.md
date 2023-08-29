# Symfony and Angular Test


## Installation

You have 2 folders: 
# "import" (which is angular) and "testimport" (which is symfony).



## Usage

1) start both apps - symfony and angular
2) run symfony migration (testimport)
2) go to http://localhost:8000/import which will take the spreadsheet from testimport/import folder and extract the data which will be shown in a table and then import the data (just a sample from it) into database.
3) after that, in angular go to http://localhost:4200/bands to see the list of bands imported from the spreadsheet and an input for adding the name of a band.