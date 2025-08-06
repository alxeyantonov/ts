<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmailIndexRequest;
use App\Http\Requests\Api\EmailStoreRequest;
use App\Http\Requests\Api\EmailUpdateRequest;
use App\Http\Resources\Api\EmailResource;
use App\Models\Email;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class EmailsController extends Controller
{
    public function index(EmailIndexRequest $request): AnonymousResourceCollection
    {
        $email = Email::query()
            ->byUserId($request->input('userId'))
            ->paginate()
            ->appends($request->validated());

        return EmailResource::collection($email);
    }

    public function store(EmailStoreRequest $request): EmailResource
    {
        $email = Email::create([
            'user_id' => $request->input('userId'),
            'email' => $request->input('email'),
        ]);

        return EmailResource::make($email);
    }

    public function show(Email $email): EmailResource
    {
        return EmailResource::make($email);
    }

    public function update(EmailUpdateRequest $request, Email $email): EmailResource
    {
        $email->update([
            'email' => $request->input('email'),
        ]);
        return EmailResource::make($email);
    }

    public function destroy(Email $email): Response
    {
        $email->delete();

        return response()->noContent();
    }
}
