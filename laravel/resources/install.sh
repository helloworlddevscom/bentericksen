#!/bin/bash

yarn install 2> >(grep -v warning 1>&2)
