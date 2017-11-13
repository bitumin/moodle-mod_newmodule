#!/usr/bin/env bash

set -e

# Printf with padding
_printf () {
    printf "%-50s" "$1"
}

# Dependencies validation
DEPENDENCIES=(rm dirname git mv php)
check_dependency () {
   command -v $1 >/dev/null 2>&1 || { echo "(ERROR) This script requires $1. Aborting." >&2; exit 1; }
}
for dependency in "${DEPENDENCIES[@]}"; do
   check_dependency ${dependency}
done

# Get name argument
for i in "$@"; do
    case ${i} in
        --name=*)
        NEWNAME="${i#*=}"
        shift
        ;;
        *)
        ;;
    esac
done
if ! [[ ${NEWNAME} =~ ^[a-z]+$ ]]; then
    echo "(ERROR) Name parameter is required and must be a letters only lowercase value. Eg: --name=widget"; exit 1
fi

# Get operative system
UNAMEOUT="`uname -s`"
case ${UNAMEOUT} in
  'Linux')      OS='Linux';;
  'Darwin')     OS='Mac';;
  *) ;;
esac
if [ -z ${OS+x} ]; then
    echo "(ERROR) This script is only compatible with Linux or OSX. Unable to recognise your operative system."; exit 1
fi

NEWVERSION=`date +%Y%m%d00`
NEWNAMEUPPERCASE=`echo ${NEWNAME} | tr '[:lower:]' '[:upper:]'`

cd "$(dirname "$0")"

# Rename all files in backup/moodle2/ folder.
mv backup/moodle2/backup_newmodule_activity_task.class.php backup/moodle2/backup_${NEWNAME}_activity_task.class.php
mv backup/moodle2/backup_newmodule_stepslib.php backup/moodle2/backup_${NEWNAME}_stepslib.php
mv backup/moodle2/restore_newmodule_activity_task.class.php backup/moodle2/restore_${NEWNAME}_activity_task.class.php
mv backup/moodle2/restore_newmodule_stepslib.php backup/moodle2/restore_${NEWNAME}_stepslib.php

# Rename the file lang/en/newmodule.php to lang/en/widget.php.
mv lang/en/newmodule.php lang/en/${NEWNAME}.php

# Update all references to the module name within all the plugin files.
if [ "$OS" = "Linux" ]; then
    find . -type f -exec sed -i 's/newmodule/'"$NEWNAME"'/g' {} \;
    find . -type f -exec sed -i 's/NEWMODULE/'"$NEWNAMEUPPERCASE"'/g' {} \;
else
    find . -type f -exec sed -i '' 's/newmodule/'"$NEWNAME"'/g' {} \;
    find . -type f -exec sed -i '' 's/NEWMODULE/'"$NEWNAMEUPPERCASE"'/g' {} \;
fi

# Modify version.php and set the initial version of you module.
if [ "$OS" = "Linux" ]; then
    sed -i 's/2014051200/'"$NEWVERSION"'/g' version.php
else
    sed -i '' 's/2014051200/'"$NEWVERSION"'/g' version.php
fi

# Rename the newmodule/ folder to the name of your module.
cd ..
mv newmodule ${NEWNAME}

# Delete rename.sh bash script, old readme and .git folder
cd ${NEWNAME}
rm rename.sh
rm README.md
rm -rf .git

# Create new README file
echo "# Moodle activity module ${NEWNAME}" > README.md

echo 'Done!'

exit 0
