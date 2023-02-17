<?php

namespace Bentericksen\StreamdentServices;

use Carbon\Carbon;
use App\Business;
use App\User;
use App\StreamdentToken;
use App\StreamdentUser;
use App\StreamdentClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StreamdentAPIService
{
		public function __construct() {

				$this->apiBaseUrl = config('streamdent.api');
				$this->username = config('streamdent.username');
				$this->password = config('streamdent.password');
				$this->apiKey = config('streamdent.api_key');
		}

		public function parseUserName(string $email)
		{
			$username = explode("@", $email, 2);
			$username = Str::slug(str_replace('+', '-', substr($username[0], 0, 20)), '-');
			return $username;
		}
		/**
		 * Authenticate and set an access token
		 */
		public function getAccessToken() {

			if (empty($this->username) || empty($this->password)) {
				throw new \Exception('Invalid streamdent credentials');
			}

			$token = StreamdentToken::firstOrNew();

			if($token->token_expiration > Carbon::now()) {
			    return $token->access_token;
            }

			try {
    		$response = Http::withHeaders([
					'ApiKey: ' . $this->apiKey
				])->post($this->apiBaseUrl . 'sessions', [
					'Login' => $this->username,
					'Password' => $this->password,
					'ApiKey' => $this->apiKey
				]);
			} catch (ClientErrorResponseException $exception) {
					throw new \Exception($exception->getResponse()->getBody(true));
			}

			$data = json_decode($response->body())->data;

			if (empty($data->access_token)) {
				throw new \Exception('No access token');
			}

			$accessToken = $data->access_token;

			$token = StreamdentToken::firstOrNew();

			//$token->token_expiration = Carbon::parse($data->ref_val); this sets to two weeks from now when should be 2 hours.

			$token->token_expiration = Carbon::now()->addHours(2);

			$token->access_token = $accessToken;

			$token->save();

			return $accessToken;
		}

		/**
		 * Get client by business ID
		 *
		 * @param $businessId
		 *
		 * @return array
		 */
		public function getClientByBusinessId($businessId): ?array
    {
				$business = Business::where('id', $businessId)->with('streamdentClient')->first();

				if (empty($business)) {
					throw new \Exception('Invalid business_id passed to update user!');
				}

				$streamdentClient = $business->streamdentClient;

        if ($streamdentClient) {
          try {
            $response = Http::withHeaders($this->getHeaders())
              ->get("{$this->apiBaseUrl}clients/{$streamdentClient->streamdent_id}");
            
            $data = json_decode($response->body(), true);

            return $data['data']['clients'][0];
          } catch (ClientErrorResponseException $exception) {
            Log::info("streamdent client error response: {$exception->getMessage()}");
          }
        }
				

        try {
					$response = Http::withHeaders($this->getHeaders())
						->get("{$this->apiBaseUrl}clients?business_id=$businessId");

          $body = $response->body();
          $data = json_decode($body, true);
          
          if ( !$data['success'] || !count($data['data']['clients']) ) {
            Log::info("steamdent client error response 01:getClientByBusinessId]: {$body}");
            return null;
          }

          Log::info("Found client by business id $businessId|{$data['data']['clients'][0]['ID']}: $body");

          return $data['data']['clients'][0];
				} catch (ClientErrorResponseException $exception) {
          Log::info("steamdent client error response: {$exception->getMessage()}");
					throw new \Exception($exception->getResponse()->getBody(true));
				}
		}

		/**
		 * Update a client
		 *
		 * @param Array $updates
		 *
		 * @return Response
		 */
		public function updateClient($updates) {
			  if (!isset($updates['business_id'])) {
			  	throw new \Exception('Invalid business_id passed to update user!');
			  }

				$businessId = $updates['business_id'];

				$business = Business::where('id', $businessId)
					->with('streamdentClient')
					->first();

				if (empty($business)) {
					throw new \Exception('Invalid business_id passed to update user!');
				}

				if (empty($business->streamdentClient)) {
					throw new \Exception('Streamdent client not created to update!');
				}

				$url = sprintf('%sclients/%s', $this->apiBaseUrl, $business->streamdentClient->streamdent_id);
        
        Log::info('api update url: '.$url);

				$name = $business->name;

				try {
					$response = Http::withHeaders($this->getHeaders())
							->patch($url, [
							'Name' => $name,
							'Description' => 'Business client',
							'is_active' => isset($updates['is_active']) ? $updates['is_active'] : 1,
              'business_id' => $business->id
					]);
				} catch (ClientErrorResponseException $exception) {
            Log::debug('update exception: ' .json_encode($data));
						throw new \Exception($exception->getResponse()->getBody(true));
				}

				$data = json_decode($response->body());

				if (!$data->success) {
          Log::debug('non success return: '.json_encode($data));

          
          Log::debug('request info ' .json_encode([
            'headers' => $this->getHeaders(),
            'body' => [
							'Name' => $name,
							'Description' => 'Business client',
							'is_active' => isset($updates['is_active']) ? $updates['is_active'] : 1,
              'business_id' => $business->id
					  ]
          ]));

					throw new \Exception($this->errorMessage($data, $response));
				}

				return [
					'success' => true,
					'message' => 'Client updated successfully!',
					'client' => $data->data->clients[0]
				];
		}

		/**
		 * Create client using an existing business
		 *
		 * @param Array $updates
		 *
		 * @return array|null
		 */
		public function createClient($updates): ?array
    {
				if (!isset($updates['business_id'])) {
					throw new \Exception('Invalid business_id passed to update user!');
				}

				$business = Business::where('id', $updates['business_id'])->first();

				if (empty($business)) {
					throw new \Exception('Invalid business_id passed to update user!');
				}

        if ($existingClient = $this->getClientByBusinessId($updates['business_id'])) {
          $encoded = json_encode($existingClient);
          
          Log::info("Found client {$updates['business_id']} -> {$encoded}");

          $this->createStreamdentClient($business, $existingClient['ID']);
          $this->updateClient([
            'business_id' => $business->id,
            'is_active' => 1
          ]);
          return null;
        }

				$name = $business->name;

				try {
					$response = Http::withHeaders($this->getHeaders())
						->post($this->apiBaseUrl . 'clients', [
							'Name' => ($business->streamdent_name_increment === 0)
                                ? $name
                                : $name . '+' . strval($business->streamdent_name_increment),
							'Description' => "Business: $name",
              'business_id' => $business->id
						]);
				} catch (ClientErrorResponseException $exception) {
						throw new \Exception($exception);
				}

				$data = json_decode($response->body());

				if (!$data->success) {
					throw new \Exception($this->errorMessage($data, $response));
				}

        $this->createStreamdentClient($business, $data->data->client[0]->ID);

				return [
						'success' => true,
						'client_id' => $data->data->client[0]->ID
				];
		}

		/**
		 * Get users
		 *
		 * @param $clientId
		 *
		 * @return Response
		 */
		public function getUsers($clientId): array
    {
      try {
        $response = Http::withHeaders($this->getHeaders())
          ->get("{$this->apiBaseUrl}users?client_id={$clientId}");
      } catch (ClientErrorResponseException $exception) {
          throw new \Exception($exception->getResponse()->getBody(true));
      }

      $data = json_decode($response->body());

      if (!$data->success) {
        throw new \Exception($this->errorMessage($data, $response));
      }

      return [
        'success' => true,
        'users' => $data->data->users
      ];
		}

		/**
		 * Get Streamdent user by Streamdent or Bentericksen user ID
		 *
		 * @param $businessId
		 *
		 * @return array
		 */
		public function getUserById(StreamdentUser $user): array
    {
				try {
					$response = Http::withHeaders($this->getHeaders())
						->get("{$this->apiBaseUrl}users/{$user->streamdent_id}");
          
          $data = json_decode($response->body(), true);

          if (!$data['success']) {
            throw new \Exception($this->errorMessage($data, $response));
          }
  
          return [
            'success' => true,
            'user' => $data['data']['users'][0]
          ];
				} catch (ClientErrorResponseException $exception) {
          Log::info("steamdent client error response [getuser]: {$exception->getMessage()}");
          throw new \Exception($exception->getResponse()->getBody(true));
				}
		}

    /**
     * Get a Streamdent user by email.
     *
     * @param string $email
     * @return null|array
     */
    public function getUserByEmail(string $email): ?array
    {
      try {
        Log::info("Looking up user $email");
        $email = urlencode ( $email );
        $response = Http::withHeaders($this->getHeaders())
          ->get("{$this->apiBaseUrl}users?email={$email}");

        $data = json_decode($response->body(), true);

        if ( !$data['success'] || !count($data['data']['users']) ) {
          Log::info("steamdent client error response 01:[getUserByEmail]: {$response->body()}");
          return null;
        }

        $encoded = json_encode($data['data']['users'][0]);

        Log::info("Found user $email -> {$encoded}");        

        return $data['data']['users'][0];

      } catch (ClientErrorResponseException $exception) {
        Log::info("steamdent client error response 02:[getUserByEmail]: {$exception->getMessage()}");
        return null;
      }
    }

    
		/**
		 * Create a new user
		 *
		 * @param Array $userUpdates
		 *
		 * @return Response|void
		 */
		public function createUser($userUpdates) {
				$username = null;
				$user = null;
				$isAdmin = false;
				$isManager = false;

				if (!isset($userUpdates['business_id'])) {
					throw new \Exception('No Business ID Provided');
				}

				$business = Business::where('id', $userUpdates['business_id'])->with('streamdentClient')->first();

				unset($userUpdates['business_id']);

				$streamdentClient = $business->streamdentClient;

        if (!$business->sop_active) {
          throw new \Exception('Business SOP not enabled');
        }
        
        if (empty($streamdentClient)) {
          throw new \Exception('Business missing Streamdent client');
        }

				$userUpdates = array_merge($userUpdates, [
					'Client_ID' => $streamdentClient->streamdent_id,
				]);

				if (isset($userUpdates['user_id'])) {
					$user = User::where('id', $userUpdates['user_id'])->first();

          $username = (!$user->streamdent_login_increment)
              ? $this->parseUsername($user->email)
              : $this->parseUsername($user->email) . '+' . strval($user->streamdent_login_increment);
          Log::info('Creating with username: '.$username);

					$userUpdates = array_merge($userUpdates, [
						'Login' => $username,
						'Fname' => $user->first_name,
						'Lname' => $user->last_name,
						'Email' => $user->email,
						'Phone' => $user->phone1,
						'Mobile' => $user->phone2
					]);

					$isAdmin = $user->hasRole('owner') || $user->hasRole('consultant');
					$isManager = $user->hasRole('manager') && $user->business->permissions->m300 == 1;

					unset($userUpdates['user_id']);
				} elseif (isset($userUpdates['Email'])) {
					$username = $this->parseUsername($userUpdates['Email']);

					$userUpdates = array_merge($userUpdates, [
						'Login' => $username
					]);
				}

				$password = Crypt::encryptString(isset($userUpdates['Password']) ?
					substr($userUpdates['Password'], 0, 16) :
					substr(md5(rand()), 0, 16));

        if ($existingUser = $this->getUserByEmail($userUpdates['Email'])) {
          $userUpdates['streamdent_id'] = $existingUser['id'];
          $this->createStreamdentUser($user ?? null, $business, $userUpdates, $password, $existingUser['id']);
          unset($userUpdates['Client_ID']);
          $userUpdates = array_merge($userUpdates, [
            'Password' => Crypt::decryptString($password),
            'is_editor' => $isAdmin || $isManager ? 1 : 0,
            'is_active' => 1
          ]);
          $this->updateUser($userUpdates);
          return;
        }

				$userUpdates = array_merge($userUpdates, [
					'Password' => Crypt::decryptString($password),
					'is_editor' => $isAdmin || $isManager ? 1 : 0,
					'is_active' => 1
				]);
        
				try {
					$response = Http::withHeaders($this->getHeaders())
						->post($this->apiBaseUrl . 'users', $userUpdates);

				} catch (ClientErrorResponseException $exception) {
					throw new \Exception($exception->getResponse()->getBody(true));
				}

				$data = json_decode($response->body());

				if (!isset($data->data->users[0]->id)) {
					throw new \Exception($this->errorMessage($data, $response));
				}
        
        $this->createStreamdentUser($user ?? null, $business, $userUpdates, $password, $data->data->users[0]->id);
        
        return [
          'success' => true
        ];
		}

		/**
		 * Update a user
		 *
		 * @param Array $userUpdates
		 *
		 * @return Response
		 */
		public function updateUser($userUpdates) {
				$user = null;
				$isAdmin = false;
				$isManager = false;

				$streamdentId = isset($userUpdates['streamdent_id']) ? $userUpdates['streamdent_id'] : null;

				unset($userUpdates['streamdent_id']);

				if (isset($userUpdates['user_id'])) {

					$user = User::where('id', $userUpdates['user_id'])
						->with('streamdentUser')
						->first();

					$streamdentId = $user->streamdentUser ? $user->streamdentUser->streamdent_id : null;

					$userUpdates = array_merge($userUpdates, [
						'Fname' => $user->first_name,
						'Lname' => $user->last_name,
						'Email' => $user->email,
						'Phone' => $user->phone1,
						'Mobile' => $user->phone2
					]);

					$isAdmin = $user->hasRole('owner') || $user->hasRole('consultant');
					$isManager = $user->hasRole('manager') && $user->business->permissions->m300 == 1;

					unset($userUpdates['user_id']);
				}

				if (!$streamdentId) {
					throw new \Exception('No Streamdent ID provided.');
				}

				$userUpdates = array_merge($userUpdates, [
					'Login' => $this->parseUsername($userUpdates['Email']),
					'is_editor' => $isAdmin || $isManager ? 1 : 0
				]);

				if (empty($userUpdates['Password'])) {
					unset($userUpdates['Password']);
				} else {
					StreamdentUser::where('streamdent_id', $streamdentId)
						->update(['password' => Crypt::encryptString($userUpdates['Password']) ]);
				}

				try {
					$response = Http::withHeaders($this->getHeaders())
						->patch($this->apiBaseUrl . 'users/' . $streamdentId, $userUpdates);

				} catch (ClientErrorResponseException $exception) {
					throw new \Exception($exception->getResponse()->getBody(true));
				}

				$data = json_decode($response->body(), true);

				if (!$data['success']) {
					throw new \Exception($this->errorMessage($data, $response));
				}

				return [
					'success' => true,
					'message' => 'User updated successfully!',
					'response' => $response->getBody()
				];
		}


    /**
     * Create record for streamdent link from user to user
     *
     * @param [type] $user
     * @param [type] $userUpdates
     * @param [type] $password
     * @param [type] $data
     * @return StreamdentUser
     */
    protected function createStreamdentUser($user, $business, $userUpdates, $password, $id): StreamdentUser
    {
      if (isset($user) && $user->streamdentUser) {
        return $user->streamdentUser;
      }

      $streamdentUser = new StreamdentUser;
      $streamdentUser->login = $userUpdates['Login'];
      $streamdentUser->user_id = isset($user) ? $user->id : null;
      $streamdentUser->business_id = $business->id;
      $streamdentUser->streamdent_id = $id;
      $streamdentUser->password = $password;
      $streamdentUser->save();

      return $streamdentUser;
    }

    /**
     * Create record for streamdent link from clients to business
     *
     * @param User $user
     * @param integer $clientId
     * @return StreamdentClient
     */
    protected function createStreamdentClient(Business $business, $clientId): StreamdentClient
    {
      Log::info('creating streamdentClient: '.$business->id. ' : '.$clientId);

      if ($business->streamdentClient) {
        return $business->streamdentClient;
      }

      $streamdentClient = new StreamdentClient;
      $streamdentClient->business_id = $business->id;
      $streamdentClient->streamdent_id = $clientId;
      $streamdentClient->save();

      return $streamdentClient;
    }
		/**
		 * Login a user
		 *
		 * @return Response
		 */
		public function login($username, $password) {

			try {
				$response = Http::withHeaders($this->getHeaders())->post($this->apiBaseUrl . 'sessions', [
					'ApiKey' => $this->apiKey,
					'Login' => $username,
					'Password' => $password,
				]);
			} catch (ClientErrorResponseException $exception) {
				throw new \Exception($exception->getResponse()->getBody(true));
			}

			$data = json_decode($response->body(), true);

			if (!$data['success']) {
				throw new \Exception($this->errorMessage($data, $response));
			}

			return [
				'user' => $data['data']['User_ID'],
				'token' => $data['data']['access_token'],
				'session_id' => $data['data']['session_id']
			];
		}

		public function logout($sessionId) {
			$url = sprintf('%s/sessions/%s', $this->apiBaseUrl, $sessionId);

			try {
				$response = Http::withHeaders($this->getHeaders())->delete($url, [
					'ApiKey' => $this->apiKey
				]);
			} catch (ClientErrorResponseException $exception) {
				throw new \Exception($exception->getResponse()->getBody(true));
			}

			$response = json_decode($response->body(), true);

			return $response;
		}

		protected function errorMessage($data, $response) {
			 $rawData = sprintf('Unknown Streamdent Update error. Streamdent Service message: %s', strip_tags($response->body()));

			 if (is_array($data)) {
			 	return $data['messages'][0] ?? $rawData;
			 }

			 $message = $data->messages[0] ?? $rawData;

			 return "Streamdent: $message";
		}

		private function getHeaders() {
            $token = $this->getAccessToken();

            return [
                'Authorization' => $token,
                'Content-Type' => 'application/json'
            ];
        }
}
