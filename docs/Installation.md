# Installation

To install stool open up a shell after a fresh ubuntu installation on your server and type the following

`wget -O - https://raw.githubusercontent.com/ben182/server-tool/master/scripts/get.sh | bash`

Thats basically it. stool will automatically install the LAMP Stack. After that it will ask you for optional modules.

stool will give you some output after the installation. There will be a welcome page bound to your domain. You get the exact URL at the end. After the installation has finished there are some things you should do that stool can not do for you automatically. First of all you should restart your shell. Then type `stool installation:test`. This will test each part of the installation for success. There should not be any erros. If so, open up an issue at github.