# Application Install

The application:install Command installs your application on your server and binds it to a vHost. First of all you need a [vHost](addvhost.md) installed on your system.

Then type `stool application:install`

### Options

|Name|Description|
|---|---|
|Domain|Set the domain you want to bind to. Without http/s or www. Bad: http://example.com Good: example.com|
|Root or Subdirectory|Install the application in a subdirectory of your domain. Root = http://example.com. Subdirectory = http://example.com/yourdirectory|
|Directory or Symlink|If you need a symlink select symlink here (for example in laravel the public directory)|
|Git|The Git repository that will be cloned|
|Laravel specific config|This will install the composer dependencies, copy the env file, set the APP_URL to your configured domain and set a application key|
| - Create Database|This will create a MySQL database and a user that has access to it. Additional it will set the database name and credentials in the env file|
| - Migrate Or Seed|This will migrate and/or seed the database|
| - Schedule through cronjob|This will create a cronjob than runs the schedule artisan command each minute|
|Composer install|Run Composer install in the application folder. This will only be asked if you don't activate Laravel specific config|
|NPM install|Run npm install in the application folder|
|Git post pull|This will put a post-merge file in the .git/hooks folder that you can customize to execute commands after git pull|
|Git auto deploy|This will enable git auto deploy for you application|
|Hard Reset|This will pull from git with hard reset|