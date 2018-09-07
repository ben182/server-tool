# MySQL Backup

This command will backup your mysql database(s). You can specify the storage type, the database(s) and the interval the command runs. Under the hood the command uses mysqldump. Your files will be saved in this format: {alldatabases}/{databasename}_d-m-Y_H-i-s.sql

Type `stool mysql:backup`

### Options

|Name|Description|
|---|---|
|All databases|If you enable this, all your databases will be dumped|
|Database Name|If you disabled All databases you will asked for a specific database name|
|Storage|If you set up digitalocean spaces you can save the backups there. Otherwise they will be saved on your local disk|
|Cronjob|If you enable this, the command will be run on a daily basis|