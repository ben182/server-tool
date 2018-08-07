## AddVhost

The vhost:add Command should be the starting point when you want to deploy your application. It will bind a domain to your server by creating a vHost.

First of all you should point your domain to the server.

Then type `stool vhost:add`

|Name|Description|
|---|---|
|Domain|Set the domain you want to bind. Without http/s or www. Bad: http://example.com Good: example.com|
|www Alias|Enable this if you want to set a www alias. This way your site can be accessed also by www.example.com|
|SSL|If enabled your site will automatically get an ssl certificate through let's encrypt|
|SSL Email|This is needed for let's encrypt since they email you when your certificate expires. But no worry. stool will automatically renew it|
|Redirect|Here you have different options to set up some redirections for your site. E.g. choose `Non SSL to SSL` to force the user to access your site with ssl encryption|