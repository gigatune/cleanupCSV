#!/bin/bash

./node_modules/.bin/browser-sync start --proxy localhost:1080/cleanupcsv --files "dist/*.php, dist/*.css"
