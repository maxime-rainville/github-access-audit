## Overview

This simple Silverstripe CMS project provides a way to review everyone who has access to repos on a specific GitHub org or packages on Packagist.

This is not meant to be some long live project. Basically you just use it to get the data you need and blast it afterwards.

## Setup

- Pull down the local repo and set it up like any Silverstripe CMS site.
- You need a `GITHUB_TOKEN` for this to work.
  - If you're only going to access public repos, you only need `public_repo`
  - If you're going to look at private repos, you need the `repo` "Full control of private repositories" option.

## Fetching GitHub data
- Log into the CMS
- Under `/a/github/organisation` create all the orgs you plan to query. You only need to specify the name.
- Run `sake dev/tasks/fetch-repos` to fetch all your data.

This will fetch all the repos for the provided organisations and let you know which users have access to them. You can review the data in the CMS in the GitHub ModelAdmin.

## Fetching Packagist data
- Log into the CMS
- Under `/a/packagist/organisation` create all the orgs you plan to query. You only need to specify the name.
- Run `sake dev/tasks/fetch-packages` to fetch all your data.

This will fetch all the packages for the provided organisations and let you know which maintainers have access to them. You can review the data in the CMS in the GitHub ModelAdmin.

## Clearing data

Some user data is preserve between runs: users' review access status and users' notes. You probably don't want to keep personal data sitting there in-between audit.

You can clear all the user and repo data by running `sake dev/tasks/reset-audit-data`.

Note that Organisation data will be preserved.
