LockerHelper
============
A variation on [MagicMirror](https://github.com/MichMich/MagicMirror) by Michael Teeuw and [Smart Mirror](https://github.com/maxbbraun/mirror) by Max Braun.

![LockerHelper](https://raw.githubusercontent.com/wiki/cmcinroy/LockerHelper/images/IMG_20160429_063851_sm.jpg)

##Objective
Build a “smart” student locker mirror.
The mirror doubles as an information portal that displays an aggregate of useful data for an individual student.

An example of [physical](https://en.wikipedia.org/wiki/Physical_computing) or [ubiquitous computing](https://en.wikipedia.org/wiki/Ubiquitous_computing).

##Modules
- [x] Time/day
- [x] Weather
- [x] Quote of the Day
- [x] Student Timetable/Schedule
- [ ] Daily School Announcements
- [x] Student Assignments
- [x] School Twitter Feed
- [ ] News headlines

##Features
- Flexible layout and styling
- Configurable data sources
  - Weather location can be based on geolocation or geocoding
  - Quotes and News can use any RSS feeds
  - Schedule pulls from a Google spreadsheet that can have timetables for multiple classes
  - Assignments pulls from a public Google calendar that can be maintained by teacher(s)
  - Twitter can pull from any user's timeline
- All modules update on independent intervals (also configurable)
- Application code can auto-update from github

##Technologies
###Hardware
- [Raspberry Pi Zero](https://www.raspberrypi.org/blog/raspberry-pi-zero/)
- legacy small (15") flat screen LCD [monitor](http://www.cnet.com/products/nec-multisync-lcd1545v-lcd-monitor-15-series/specs/#p=nec-multisync-lcd1545v-lcd-monitor-15-lcd1545vbk/)
- reflective-tinted acrylic "[mirror](http://www.metcalfglassltd.ca/)" (10"x13" x 1/8")

###Software
- Apache web server
- PHP (with Composer for dependency management)
- Javascript
- HTML/CSS
- JSON, RSS and XML parsing

###Services
- JSON [Weather](http://forecast.io/), [Geolocation](http://geoip.nekudo.com/) and [Geocoding](https://developers.google.com/maps/documentation/geocoding/intro) feeds
- RSS [Quote of the Day](http://www.brainyquote.com/quotes_of_the_day.html) feed
- Google Sheets [API](https://developers.google.com/google-apps/spreadsheets/) (with OAuth 2.0 API) to access student timetable info stored in a spreadsheet (tabs = classes)
- Google Calendar [API](https://developers.google.com/google-apps/calendar/) to access a public calendar where Class assignments are stored as events
- Good ol' HTTP feed (school web site) provides the latest morning announcements
- Twitter [API] (https://dev.twitter.com/rest/reference/get/statuses/user_timeline) provides JSON for the timeline of the school twitter account

##Specifications
The Project Team provided the following specification:

![Spec sheet](https://raw.githubusercontent.com/wiki/cmcinroy/LockerHelper/images/IMG_20160406_165511_sm.jpg)

##Future
- data caching
- Word of the [day](http://wordnik.com/)
- schedule lookup by student (instead of specific class)
- Facial recognition (camera required)
  - Automatically wake up the display
  - mood-specific compliments
  - student-specific schedule
- Voice commands (microphone required)
- "Lockerize" the hardware
  - battery [pack](https://www.adafruit.com/products/1944)
  - [screen](https://www.adafruit.com/categories/336)


##License
LockerHelper is free software: you can redistribute it and/or modify it under the terms of the [GNU General Public License](http://www.gnu.org/licenses/gpl.html) as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

LockerHelper is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
