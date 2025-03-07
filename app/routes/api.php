<?php

use App\Http\Controllers\SearchController;

Route::get('/request-status/{uuid}', [SearchController::class, 'requestStatus'])->name('requestStatus');
