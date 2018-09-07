# Redis Backup

This command will backup your redis database. You can specify the storage type and the interval the command runs. Under the hood the command uses redis-dump (a small ruby gem). Your files will be saved in this format: d-m-Y_H-i-s.json.

Type `stool redis:backup`

### Options

|Name|Description|
|---|---|
|Storage|If you set up digitalocean spaces you can save the backups there. Otherwise they will be saved on your local disk|
|Cronjob|If you enable this, the command will be run on a daily basis|