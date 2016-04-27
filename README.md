LockerHelper
============
A variation on [MagicMirror](https://github.com/MichMich/MagicMirror) by Michael Teeuw and [Smart Mirror](https://github.com/maxbbraun/mirror) by Max Braun.

##Objective
Build a “smart” student locker mirror.
The mirror doubles as an information portal that displays an aggregate of useful data for an individual student.

An example of [physical](https://en.wikipedia.org/wiki/Physical_computing) or [ubiquitous computing](https://en.wikipedia.org/wiki/Ubiquitous_computing).

##Modules
- [ ] Time/day
- [ ] Weather
- [ ] Quote of the Day
- [ ] Student Timetable/Schedule
- [ ] Daily School Announcements
- [ ] Student Assignments
- [ ] School Twitter Feed

##Technologies
###Hardware
- Raspberry Pi Zero
- legacy small (15") flat screen LCD monitor
- reflective-tinted acrylic "mirror" (10"x13" x 1/8")

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
- Twitter [API] (https://dev.twitter.com/rest/reference/get/statuses/user_timeline) provides the timeline of the school twitter account

##License
LockerHelper is free software: you can redistribute it and/or modify it under the terms of the [GNU General Public License](http://www.gnu.org/licenses/gpl.html) as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

LockerHelper is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
