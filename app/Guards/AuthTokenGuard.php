<?php

namespace App\Guards;

use App\Models\HrmsUser;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum;

class AuthTokenGuard implements Guard
{
    use GuardHelpers;

    protected $request;
    protected $hrmsApiUrl;

    public function __construct(Request $request)
    {
        $this->hrmsApiUrl = config()->get('services.url.hrms_api_url');
        $this->request = $request;
    }

    public function user()
    {

        if ($this->user !== null) {
            return $this->user;
        }
        $token = $this->request->bearerToken();

        $response = Http::acceptJson()->throw()->withToken($token)->get($this->hrmsApiUrl . 'api/session');

        Log::info($response);
        if (!$response->successful()) {
            return null;
        }

        if ($response->json()) {
            $this->user = new HrmsUser();
            $this->user->id = $response->json()['id'];
            $this->user->name = $response->json()['name'];
            $this->user->email = $response->json()['email'];
            $this->user->type = $response->json()['type'];
        }
        return $this->user;
    }
    public function validate(array $credentials = [])
    {
    }
}
