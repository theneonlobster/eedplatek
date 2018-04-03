[![Build Status](https://travis-ci.org/theneonlobster/eedplatek.svg?branch=master)](https://travis-ci.org/theneonlobster/eedplatek) [![Waffle.io - Columns and their card count](https://badge.waffle.io/theneonlobster/eedplatek.svg?columns=all)](https://waffle.io/theneonlobster/eedplatek)

# My Project

Welcome to the repo for my personal Drupal 8 websites, which use a multisite strategy and are hosted on Acquia Cloud. The continuous integration process leverages BLT, GitHub, Travis CI, Acquia Pipelines, and Cloud Hooks. Configuration Splits manage global configuration, profile-specific configuration, site-specific configuration, and optional features (such as blogs and event calendars).

## Getting Started

This project is based on BLT, an open-source project template and tool that enables building, testing, and deploying Drupal installations following Acquia Professional Services best practices.

To set up your local environment and begin developing for this project, complete the following steps (you may also refer to the [BLT onboarding documentation](http://blt.readthedocs.io/en/latest/readme/onboarding/)).

* Ensure that your computer meets the minimum installation requirements (and then install the required applications). See the [System Requirements](http://blt.readthedocs.io/en/8.x/INSTALL/)
* Fork the parent repository in GitHub
* Clone your fork
```
$ git clone git@github.com:<your repository>/eedplatek.git
```
* Add the parent repository as an upstream
```
$ git remote add upstream git@github.com:theneonlobster/eedplatek.git
```
* Install Composer Dependencies (warning: this can take some time based on internet speeds)
```
$ composer install
```
* Setup Virtual Machine (warning: this can take some time based on internet speeds)
```
$ blt vm
```
* Synchronize your local with the cloud
```
$ blt sync:all
```
* Access the site and do necessary work at http://local.eedplatek.com

Additional [BLT documentation](http://blt.readthedocs.io) may be useful. You may also access a list of BLT commands by running:
```
$ blt
```

Note the following properties of this project:
* Primary development branch: develop
* Local environment: DrupalVM
* Local drush alias:
  * @eedplatek.local
  * @drupalsherpa.local
  * @arrowdude.local
* Local site URLs:
  * http://local.eedplatek.com
  * http://local.drupalsherpa.com
  * http://local.arrowdude.com

## Working With BLT

This is the common workflow for this project.

* Locate a ticket that you are planning on working
* Ensure that your git is tracking the most current upstream
```
$ git fetch upstream
```
* Create a new branch off of upstream/master that is based on the ticket you are working (e.g. TNL-XXX)
```
$ git checkout -b TNL-XXX upstream/master
```
* Reset your local environment to ensure everything is inline with the new branch. WARNING: this is destructive
```
$ blt sync:all
```
* Do whatever work is required for the ticket
* Create new commit(s) as needed. All commit messages should follow the pattern: TNL-XXX: commit messages go here. They must include the Ticket Number (with a dash AND a colon), a message, and a period.
* Run Tests / Validation Scripts
```
$ blt validate
$ blt tests
```
* Ensure that no other changes have been made to the upstream/master branch. If they have, rebase your branch.
```
$ git fetch upstream
$ git rebase upstream/master
```
* Push your commit(s) to your origin
```
$  git push --set-upstream origin TNL-XXX
```
* Create a new Pull Request that mentions the original ticket in the body (#TNL-XXX)
* Ensure the build passes

## Resources

* [Waffle.io](https://waffle.io/theneonlobster/eedplatek)
* [GitHub](https://github.com/theneonlobster/eedplatek)
* [Acquia Cloud subscription](https://cloud.acquia.com/app/develop/applications/472ba0a8-c8c6-bfd4-ddf4-28adbedcfe23)
* [Travis CI](https://travis-ci.org/theneonlobster/eedplatek)
