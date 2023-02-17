#!/bin/bash
# Name: genTestCase.sh
# Purpose: build new E2E test from base and seed tests  at-base-bonusPro-seed
# Operation:  ./genTestCase.sh -ave <2,3,4,6> -
# EXAMPLE:  ./getTestCase.sh -m 2 -p 15 -d hours -delete true
# ---> at-bonusPro-2m-15p-hours and at-bonusPro-2m-15p-hours-seed will be generated in proper folders
# NOTES:  You may need to change the file permission to executable first.   chmod +x genTestCase.sh
#
# Deletion:   To remove test E2E tests, use:
# ./genTestCase.sh -x true -n <file name>
# EXAMPLE:  ./genTestCase.sh -x true -n 4m-25p-hours-seed
# --> This will delete at-bonusPro-4m-25p-hours-seed.js and at-bonusPro-4m-25p-hours.js from their respective dir
# ----------------------------------------------------

# Current Date string for branch strategies
DATE_NOW=`date '+%Y-%m-%d-%H-%M-%S'`

# default environment
DELETE='false';
FILEBASE='gen';
MONTH=2;
PERCENT=15;
DISTRIBUTION='hours';
MOCKDATA_DIR="../mock-data"
E2ETEST_DIR="../../../../../tests/E2E";

print_usage() {
  printf "\nNOTICE: You can select the following options to change \n";
  printf " (month)             -m 2,3,4,6 \n";
  printf " (bonus percent)     -p 15,20,25 \n";
  printf " (distribution mode) -d 'hours', 'salary', 'equal_share' \n";
  printf " (delete testcase) -x 'true' -n <file name>\n";
}
### Get user inputs
while getopts ":m:p:d:x:n:" flag
do
    case "${flag}" in
        m) MONTH=${OPTARG} ;;
        p) PERCENT=${OPTARG} ;;
        d) DISTRIBUTION=${OPTARG} ;;
        x) DELETE=${OPTARG} ;;
        n) FILENAME=${OPTARG} ;;
        *) print_usage
       exit 1 ;;
    esac
done

if [ -n "${FILENAME}" ]; then
  echo "found -${FILENAME}-  using...";
  FILEBASE="${FILENAME}";
else
  FILEBASE="${FILEBASE}-${MONTH}m-${PERCENT}p-${DISTRIBUTION}";
fi


if [[ $DELETE = "true" ]]
then
### DELETE OLD TESTCASES
     while true; do
      echo "Do you want to delete testcase:";
      echo " ${FILEBASE} ";
      read -p " Proceed?(Y/n)?  " yn2
      case $yn2 in
          [Yy]* ) break;;
          [Nn]* ) exit 1;;
          * ) echo "Please answer Y or n";;
      esac
     done
     echo "Deleting:  ${MOCKDATA_DIR}/${FILEBASE}-seed.js";
     echo "Deleting:  ${E2ETEST_DIR}/${FILEBASE}.js";
     rm  "${MOCKDATA_DIR}/${FILEBASE}-seed.js";
     rm  "${E2ETEST_DIR}/${FILEBASE}.js";
     echo "..checking for any backup copies to also delete..."
     rm  "${MOCKDATA_DIR}/${FILEBASE}-seed.js.bu";
     rm  "${E2ETEST_DIR}/${FILEBASE}.js.bu";
     exit 1;
fi

### Generic first level testing SCRIPT BELOW ####
if [ -z ${MOCKDATA_DIR} ]
then
  echo "EXITING:  Can't open dir:  Please check location for ${MOCKDATA_DIR}..."
  exit 1;
else
  echo "using mock data location: ${MOCKDATA_DIR}"
fi

### Generic first level testing SCRIPT BELOW ####
if [ -z ${E2ETEST_DIR} ]
then
  echo "EXITING:  Can't open dir:  Please check location for ${E2ETEST_DIR}..."
  exit 1;
else
  echo "using E2E data location: ${E2ETEST_DIR}"
fi

## Copy files
cp ${MOCKDATA_DIR}/at-base-bonusPro-seed.js ${MOCKDATA_DIR}/${FILEBASE}-seed.js;
echo "Creating .. ${MOCKDATA_DIR}/${FILEBASE}-seed.js";

cp ${E2ETEST_DIR}/../templates/at-base-bonusPro.js ${E2ETEST_DIR}/${FILEBASE}.js;
echo "Creating .. ${E2ETEST_DIR}/${FILEBASE}.js";

## Swap template values
sed -i.bu 's@$PERCENT@'"$PERCENT"'@' ${E2ETEST_DIR}/${FILEBASE}.js
sed -i.bu 's@$FILEBASE@'"$FILEBASE"'@' ${E2ETEST_DIR}/${FILEBASE}.js
sed -i.bu 's@$FILEBASE@'"$FILEBASE"'@' ${MOCKDATA_DIR}/${FILEBASE}-seed.js
sed -i.bu 's@$MONTH@'"$MONTH"'@' ${MOCKDATA_DIR}/${FILEBASE}-seed.js
sed -i.bu 's@$DISTRIBUTION@'"$DISTRIBUTION"'@' ${MOCKDATA_DIR}/${FILEBASE}-seed.js

## Clean up backups
rm  "${MOCKDATA_DIR}/${FILEBASE}-seed.js.bu";
rm  "${E2ETEST_DIR}/${FILEBASE}.js.bu";
