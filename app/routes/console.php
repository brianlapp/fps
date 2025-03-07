<?php

use Illuminate\Support\Facades\Schedule;

// MaxMind GeoIP DB updating
Schedule::command('geoip:update')->lastDayOfMonth();
Schedule::command('sitemap:generate')->daily();

