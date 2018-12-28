#!/usr/bin/env bash

grep -REl "(ak_test|ak_live|ek_test|ek_live)_([a-zA-Z0-9]*)" ./app ./tests ./skin ./js
LINES_FOUNDED=$?
if [ ! $LINES_FOUNDED -eq 1 ]; then
    # key was founded so exit 1
    exit 1
fi