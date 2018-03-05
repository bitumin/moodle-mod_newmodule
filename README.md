# Moodle mod plugin template

The following steps should get you up and running with this module template code.

### 1. Download repository

Unzip this repo or clone it within the Moodle directory `mod/newmodule`

### 2. Rename files and references

Pick a name for the new module. The module name MUST be lower case and can't contain underscores. You should check https://moodle.org/plugins/ to make sure that your name is not already used by an other module. Registering the plugin at https://moodle.org/plugins/registerplugin.php will secure it for you.

From the command line run the bash script `rename.sh`. For example, if the name you have chosen is "tictactoe" run:

```bash
sh rename.sh --name=tictactoe --copyright="2018 Mitxel Moriana <mitxel+moriana@my-email.com>"
```

If the script is successful it will create a new module folder with the provided module name and all the renaming operations done. Then you may delete this repository folder.  

If this script fails or if you prefer to do the renaming operations manually, follow this steps: 

* Rename the newmodule/ folder to the name of your module (eg "tictactoe").

* Edit all the files in this directory and its subdirectories and change
  all the instances of the string "newmodule" to your module name
  (eg "tictactoe"). If you are using Linux, you can use the following command
  $ find . -type f -exec sed -i 's/newmodule/tictactoe/g' {} \;
  $ find . -type f -exec sed -i 's/NEWMODULE/TICTACTOE/g' {} \;

  On a mac, use:
  $ find . -type f -exec sed -i '' 's/newmodule/tictactoe/g' {} \;
  $ find . -type f -exec sed -i '' 's/NEWMODULE/TICTACTOE/g' {} \;

* Rename the file lang/en/newmodule.php to lang/en/tictactoe.php
  where "tictactoe" is the name of your module

* Rename all files in backup/moodle2/ folder by replacing "newmodule" with
  the name of your module

  On Linux based systems you can perform this and previous steps by calling:
  $ find . -depth -name '*newmodule*' -execdir bash -c 'mv -i "$1" "${1//newmodule/tictactoe}"' bash {} \;

* Modify version.php and set the initial version of you module.

* Update the copyright information withing all the files.

### 3. Plugin installation

Visit `Settings > Site Administration > Notifications` and run the plugin installation/update. After the update check that the module's DB tables were successfully created.

Go to `Site Administration > Plugins > Activity modules > Manage activities` and check that the new module has been added to the list of installed modules.

### Happy coding!

You may now proceed to write and run your own code. You will probably want to modify `mod_form.php` and `view.php` as a first step.

Check `db/access.php` to add capabilities.

Go to `Settings > Site Administration > Development > XMLDB editor` and add and modify the module's DB tables.

We encourage you to share your code and experience in http://moodle.org

Good luck!
