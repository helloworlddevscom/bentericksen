#!/bin/bash

APP_URL=http://web BROWSER=$BROWSER BROWSER_STACK_KEY=$BROWSER_STACK_KEY BROWSER_STACK_USER=$BROWSER_STACK_USER yarn run test:e2e:local:ci $([[ -n "$SPEC" ]] && echo "--spec $SPEC")