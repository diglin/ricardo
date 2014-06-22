### HOW TO

To configure the test, please create a php file `config.ini` with the following content:
Pay attention, you need two different configuration for each language. At the moment, the tests support only one language at one time


// Ricardo API Config for German version (for example)
```
[GERMAN]
host = ws.betaqxl.com
partnership_id = YOUR_PARTNER_ID
partnership_passwd = YOUR_PARTNER_PASS
partner_url = YOUR_WEBSITE_URL
allow_authorization_simulation = true
customer_username =
customer_password = ''
debug = true

[FRENCH]
host = ws.betaqxl.com
partnership_id = YOUR_PARTNER_ID
partnership_passwd = YOUR_PARTNER_PASS
partner_url = YOUR_WEBSITE_URL
allow_authorization_simulation = true
customer_username =
customer_password = ''
debug = true
```