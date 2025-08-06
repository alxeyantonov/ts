<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\NotifyUserEmailsJob;
use App\Models\User;
use Illuminate\Http\Response;

class UsersController extends Controller
{

    public function sendEmails(User $user): Response
    {
        NotifyUserEmailsJob::dispatch($user);

        return response()->noContent();
    }
}
