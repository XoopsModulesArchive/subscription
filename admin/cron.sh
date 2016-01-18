#!/bin/sh

WGET_PATH=/usr/local/bin
XOOPS_USER={user}
XOOPS_PASS={pass}
XOOPS_URL={url}
TMP_PATH={path}

${WGET_PATH}/wget --post-data="uname=${XOOPS_USER}&pass=${XOOPS_PASS}&op=login" -O - --save-cookies=${TMP_PATH}/cookies.txt --keep-session-cookies ${XOOPS_URL}/user.php
${WGET_PATH}/wget --load-cookies=${TMP_PATH}/cookies.txt -O ${TMP_PATH}/out.txt ${XOOPS_URL}/modules/subscription/admin/cron.php?cron=true
