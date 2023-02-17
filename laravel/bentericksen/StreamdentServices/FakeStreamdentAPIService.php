<?php

namespace Bentericksen\StreamdentServices;

use Carbon\Carbon;
use Illuminate\Http\Request;

class FakeStreamdentAPIService
{
		/**
		 * Get client by business ID
		 *
		 * @param $businessId
		 *
		 * @return Response
		 */
		static function getClientByBusinessId($businessId) {
				if (isset($businessId)) {
						return ['success' => 'true'];
				} else {
						return ['success' => 'false'];
				}
		}

		/**
		 * Update a client
		 *
		 * @param Array $updates
		 *
		 * @return Response
		 */
		static function updateClient($updates) {
				if (isset($updates['business_id']) && isset($updates['is_active'])) {
						return ['success' => 'true'];
				} else {
						return ['success' => 'false'];
				}
		}

		/**
		 * Create client using an existing business
		 *
		 * @param Array $updates
		 *
		 * @return Response
		 */
		static function createClient($updates) {
				if (isset($updates['user_id'])) {
						return ['success' => 'true'];
				} else {
						return ['success' => 'false'];
				}
		}

		/**
		 * Get users
		 *
		 * @param $businessId
		 *
		 * @return Response
		 */
		static function getUsers() {
				return ['success' => 'true'];
		}

		/**
		 * Get Streamdent user by Bentericksen user ID
		 *
		 * @param $businessId
		 *
		 * @return Response
		 */
		static function getUserById($userId) {
				if (isset($userId)) {
						return ['success' => 'true'];
				} else {
						return ['success' => 'false'];
				}
		}

		/**
		 * Create a new user
		 *
		 * @param Array $userUpdates
		 *
		 * @return Response
		 */
		static function createUser($userUpdates) {
				if (isset($userUpdates['user_id']) && isset($userUpdates['password']) && isset($userUpdates['is_active'])) {
						return ['success' => 'true'];
				} else {
						return ['success' => 'false'];
				}
		}

		/**
		 * Update a user
		 *
		 * @param Array $userUpdates
		 *
		 * @return Response
		 */
		static function updateUser($userUpdates) {
				if (isset($userUpdates['user_id']) && isset($userUpdates['password']) && isset($userUpdates['is_active'])) {
						return ['success' => 'true'];
				} else {
						return ['success' => 'false'];
				}
		}

		/**
		 * Login a user
		 *
		 * @return Response
		 */
		static function login() {
				return ['success' => 'true'];
		}

		/**
		 * Login a user from login screen
		 *
		 * @return Response
		 */
		static function loginFromLoginScreen() {
				return ['success' => 'true'];
		}
}
