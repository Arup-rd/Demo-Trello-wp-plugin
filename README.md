=== My Plugin - Best WP Plugin for WordPress ===

## Step to make your own plugin
* Clone this repository on your local `plugin folder`

Now just setup for your own plugin, it's very easy using node auto command.
##### 1. Auto setup:
Just run `node setup`
it will ask for a plugin name, type your plugin name and hit enter.

NB: Please write your plugin name in lowercase
example:  my todo

Your plugin is ready to use.

Now run
`composer install`

`npm i`

`npm run watch`
Then activate the plugin from your WP admin dashboard.

If you want to make setup by hand you can do it also. But auto setup is the best option for you.

<details><summary>Or Manual Setup</summary>

## Step to make your own plugin

##### Open with an IDE (Vscode, sublime, PhpStorm etc)
  and use case sensitive search and replace bellow keys

* Change all the   `my_plugin` to your_plugin
* Change all the   `my-plugin`  to your-plugin
* Change all the   `MYPLUGIN`  to YOURPLUGIN   (Upper case)
* Change all the   `MY_PLUGIN`  to YOUR_PLUGIN
* Change all the   `myplugin` to yourplugin
* Change all the   `myPlugin`  to yourPlugin (lowerCamel case)
* Change all the   `MyPlugin`  to YourPlugin. (UpperCamel case)


</details>

Then you will see the plugin on your WP plugin page.

## For further Production build
`npm run production`

Then you can delete these files:

node-modules

resources

package.json

package-lock.json

setup.js

webpack.mix.js

webpack.config.js

and Facker from Database dirrectory


