#!/bin/bash

# THIS SCRIPT UPDATES THE HARDCODED VERSION
# IT WILL BE EXECUTED IN STEP "prepare" OF
# semantic-release. SEE package.json

# version format: X.Y.Z
newversion="$1";

sed -i "s/return \"[0-9]\+\.[0-9]\+\.[0-9]\+\"/return \"${newversion}\"/g" src/APIClient.php
