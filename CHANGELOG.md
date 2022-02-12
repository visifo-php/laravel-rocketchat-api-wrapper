# Changelog

All notable changes to `laravel-rocketchat-api-wrapper` will be documented in this file.

## 0.3.2 - 2022-02-12
- users.setAvatar does not throw exception if the user not exists

## 0.3.1 - 2022-02-12
- improved error handling
- added configurable debug output on requests and responses
- users.info & channels.info return null if not exists
- users.create & channels.create return the object if it exists
- users.delete & channels.delete does not throw exception if the target not exists

## 0.2.3 - 2022-02-11
- send confirmRelinquish in users.delete only if it is set to true

## 0.2.2 - 2022-02-10
- fixed users.setAvatar post request

## 0.2.1 - 2021-12-09
- added laravel-enum ^3.3 support

## 0.2.0 - 2021-11-26
- added more api methods
- added enums for channel types
- fixed min test dependencies

## 0.1.0 - 2021-11-14
- created base functionality
- setup most necessary endpoints and methods
- created documentation

## 0.0.0 - 2021-11-05
- initial release
- initial configuration
