# nojs-ga-php-tracking

Alternate server-side Google-Analytics-Tracking without JavaScript and Cookies.
Uses PHP and [Google Measurement Protocoll](https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters).

## Advantages
- Take control of the data to be transmitted to Google Analytics
- Track visitors with disabled JavaScript
- Accelerates page load and site performance

> Note: This script doesn't track all data of the original Google Analytics code, especially no data that can only be determined with JavaScript, e.g. the screen resolution. The recognition of returning visitors is only as good as the server-side detection (e.g. login). Currently only the visitor is recognized within a session or by the IP. 

## Installation

Save stat.php in web project folder, e. g. 'inc/stat.php'

## Usage

- Change the variables to be transferred according to your wishes
- Include script in all pages to track (index.php, ...)

```php
 include('YOURPATH/stat.php');
```

## Examples

### Event tracking with jquery 

```js
function ga(s,t,ec,ea,el,ev){
	$.ajax({ type:"POST", url:"YOURPATH/stat.php", data:{"type":t,"ec":ec,"ea":ea,"el":el,"ev":ev} })
}

ga('send','event','CATEGORIE','ACTION','LABEL','VALUE')
```

## Options

For all parameters look at [Google Measurement Protocoll](https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters) 
