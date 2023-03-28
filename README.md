## Overview

This simple Silverstripe CMS project provides a way to review everyone who has access to repos on a specific GitHub org.

## Setup

- Pull down the local repo and set it up like any Silverstripe CMS site.
- You need a `GITHUB_TOKEN` for this to work.
  - If you're only going to access public repos, you only need `public_repo`
  - If you're going to look at private repos, you need the `repo` "Full control of private repositories" option.

## Fetching the data
- Log into the CMS
- Under `/a/github/organisation` create all the orgs you plan to query. You only need to specify the name.
- Run `sake dev/tasks/fetch-repos` to fetch all your data.

This will fetch all the repos for the provided origanisations and let you know which users has access to them. You can review the data in the CMS in the GitHub ModelAdmin.
