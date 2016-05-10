LockerHelper
============
A variation on [MagicMirror](https://github.com/MichMich/MagicMirror) by Michael Teeuw and [Smart Mirror](https://github.com/maxbbraun/mirror) by Max Braun.

![LockerHelper](https://raw.githubusercontent.com/wiki/cmcinroy/LockerHelper/images/IMG_20160429_063851_sm.jpg)

##Objective
Build a “smart” student locker mirror.
The mirror doubles as an information portal that displays an aggregate of useful data for an individual student.

Just like the precursor projects, the objective is accomplished by putting a computer screen behind a partially reflective ("two-way") mirror. A computer with internet access pulls information from various sources and renders an aggregate, single screen  view. By displaying light text on a dark background, the information blends with the image reflected by the mirror.  Thus, the computer augments the utility of the traditional mirror.

The information sources implemented in this project are meant to augment a mirror found in a typical student's locker.

This is an example of [physical](https://en.wikipedia.org/wiki/Physical_computing) or [ubiquitous computing](https://en.wikipedia.org/wiki/Ubiquitous_computing).

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
- Raspberry Pi Zero
- legacy small (15") flat screen LCD monitor
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

##How It Works
The Raspberry Pi computer boots into its operating system and automatically logs in to the graphical user interface, with the display orientation configured for portrait mode (to be consistent with a typical locker mirror).  An autostart script opens a web browser in fullscreen mode.  The web page displays information provided by the web server software, also running on the computer.  The web server retrieves the raw data from various internet sources and provides it in a consistent format to the web page.  The page automatically updates the data by making requests for the latest data from the server/internet on configurable time intervals.

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
