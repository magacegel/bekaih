#!/bin/bash

echo "==================================================================="
echo "                       DEPLOYING : $CI_JOB_STAGE                   "
echo "==================================================================="

# Get servers list
set -f

shell=(ssh -o StrictHostKeyChecking=no "${SSH_USER_STAGING}@${SSH_STAGING}")
git_token=$DEPLOY_TOKEN

if [ "dev_pull" = $CI_JOB_STAGE ]; then
    ssh -o StrictHostKeyChecking=no "${SSH_USER_STAGING}@${SSH_STAGING}" \
          "CI_PROJECT_NAME='$CI_PROJECT_NAME' \
          CI_PROJECT_PATH='$CI_PROJECT_PATH' \
          token_pull='$token_pull' \
          bash -s" < docker/dev_pull.sh

elif [ "dev_container" = $CI_JOB_STAGE ]; then
    ssh -o StrictHostKeyChecking=no "${SSH_USER_STAGING}@${SSH_STAGING}" \
          "CI_PROJECT_NAME='$CI_PROJECT_NAME' \
          CI_PROJECT_PATH='$CI_PROJECT_PATH' \
          token_pull='$token_pull' \
          bash -s" < docker/dev_container.sh

elif [ "prod_pull" = $CI_JOB_STAGE ]; then
    ssh -o StrictHostKeyChecking=no "${SSH_USER_PRD_INT}@${SSH_PRD_INT}" \
          "CI_PROJECT_NAME='$CI_PROJECT_NAME' \
          CI_PROJECT_PATH='$CI_PROJECT_PATH' \
          token_pull='$token_pull' \
          bash -s" < docker/prod_pull.sh

elif [ "prod_container" = $CI_JOB_STAGE ]; then
    ssh -o StrictHostKeyChecking=no "${SSH_USER_PRD_INT}@${SSH_PRD_INT}" \
          "CI_PROJECT_NAME='$CI_PROJECT_NAME' \
          CI_PROJECT_PATH='$CI_PROJECT_PATH' \
          token_pull='$token_pull' \
          bash -s" < docker/prod_container.sh

fi
