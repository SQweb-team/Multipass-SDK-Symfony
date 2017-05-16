#!/usr/bin/env bash

# SQWEB - SYMFONY SDK - RELEASE NOTIFIER
# ------------------------------------------------------------------------------
# Let the Slack team know that the release was successful.

curl -X "POST" "https://hooks.slack.com/services/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" \
	 -H "Content-Type: application/x-www-form-urlencoded; charset=utf-8" \
	 --data-urlencode "payload={\"text\": \"$TRAVIS_TAG released on GitHub + Packagist.\"}"
