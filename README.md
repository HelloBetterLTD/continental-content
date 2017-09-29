# Continental Content Module

A SilverStripe module which allows you to have contents specific for locations, and serve these contents for users by checking their location. 

# Installing 

Use composer to install the module. 

`composer require silverstripers/continental-content dev-master`

# Configuring 

After installing the module on your SilverStripe site, you have to manually specify which data objects you want the module to decorate with in order to have different contents. 

```
SiteTree:
  extensions:
    - ContinentalContent
```

You have the freedom to decorate any object, in this YAML i am decorating the SiteTree object, which is the base object of SilverStripe pages. 

### Setting up location

Once you've done the above you can set up the locations which you want to have different contents for. 

```
ContinentalContent:
  continents:
    BC:
      Country: CA
      SubDivision: BC
    AB:
      Country: CA
      SubDivision: AB
    ON:
      Country: CA
      SubDivision: ON
```

If you want to use contents for a group of location then you can group them by.

```
ContinentalContent:
  continents:
    Europe:
      Country:
        - GB
        - GE
        - FR
    NZ:
        Country: NZ
    AU:
        Country: AU
```

In this example Europe will be used for GB, GE, FR countries.

## What if you dont want to have multiple fields

If there are fields which you dont wish to customise for each of the locations, you can specify them as configs

```
ContinentalContent:
  exclude_field_names:
    - SiteTree.Title
    - URLSegment
```

The above removes URLSegment from any of the data objects you have a field called URLSegment, but it removes Title only from SiteTree.


## Seperate URLS 

If you want to have separate urls for each location eg: site.com/uk/home, site.com/nz/home etc. You can allow that with another config. 

```
ContinentalContent:
  custom_urls: 'Y'
```

## Webserver cant read the visitors IP ? (Higher Level Customizations)

Sometimes this can happen, if you are using several load balancers to and have your website behind them and your load balancers
wont pass the end clients IP. In this can use can set up a form and ask your users to select the location they are coming from.

```
ContinentalContent:
  proxy_ip: '0.0.0.0'
```

set up the IP which your webserver gets all the time. 

make a function like the following in Page_Controller class. 

```
function LocationDetected(){
  return !(ContinentalContent::IsViewingThroughProxy() && ContinentalContent::CurrentContinent() == CONTINENTAL_DEFAULT);
}
```

If the above returns true you can draw a location selector to select the visitor's location. 

# Setting up IP database

The module only supports max mind, upload the max mind City data base from the site config.


# Debug options

There are several options to debug the configs. You can pass three get vars

1. FAKE_IP -- fake the ip address
2. CLEAR_IP -- clear the ip from the session
3. debug_location -- Displays a debug message on screen for the locations.


