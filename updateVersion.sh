#!/bin/bash

# THIS SCRIPT UPDATES THE HARDCODED VERSION
# IT WILL BE EXECUTED IN STEP "prepare" OF
# semantic-release. SEE package.json

# version format: X.Y.Z
newversion="$1"

printf -v sed_script 's/return "[0-9]\+\.[0-9]\+\.[0-9]\+"/return "%s"/g' "${newversion}"
sed -i -e "${sed_script}" src/APIClient.php
