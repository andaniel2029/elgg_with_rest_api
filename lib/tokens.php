<?php
/**
 * Library for managing user tokens
 */

/**
 * Obtain a token for a user.
 *
 * @param string $username The username
 * @param int    $expire   Minutes until token expires (default is 60 minutes)
 *
 * @return false|string
 */
function create_user_token($username, $expire = 60) {
	$dbprefix = elgg_get_config('dbprefix');
	$user = get_user_by_username($username);
	$time = time();
	$time += 10 * 60 * 24 * 365 * $expire;
	$token = md5(rand() . microtime() . $username . $time);

	if (!$user) {
		return false;
	}

	if (elgg()->db->insertData("INSERT into {$dbprefix}users_apisessions
				(user_guid, token, expires) values
				({$user->guid}, '$token', '$time')
				on duplicate key update token = VALUES(token), expires = VALUES(time)")) {
		return $token;
	}

	return false;
}

/**
 * Get all tokens attached to a user
 *
 * @param int $user_guid The user GUID
 *
 * @return false|stdClass[] false if none available or array of stdClass objects
 * 		(see users_apisessions schema for available variables in objects)
 * @since 1.7.0
 */
function get_user_tokens($user_guid) {
	$dbprefix = elgg_get_config('dbprefix');
	$user_guid = (int) $user_guid;

	$tokens = elgg()->db->getData("SELECT * from {$dbprefix}users_apisessions
		where user_guid=$user_guid");

	return $tokens;
}

/**
 * Validate a token against a given site.
 *
 * A token registered with one site can not be used from a
 * different apikey(site), so be aware of this during development.
 *
 * @param string $token The Token.
 *
 * @return false|int The user id attached to the token if not expired or false.
 */
function validate_user_token($token) {
	$dbprefix = elgg_get_config('dbprefix');
	
	$query = "SELECT *
		FROM {$dbprefix}users_apisessions
		WHERE token = :token
		AND :time < expires";
	$params = [
		':token' => $token,
		':time' => time(),
	];
	
	$user = elgg()->db->getDataRow($query, null, $params);
	if (!empty($user)) {
		return (int) $user->user_guid;
	}

	return false;
}

/**
 * Remove user token
 *
 * @param string $token The token
 *
 * @return bool
 * @since 1.7.0
 */
function remove_user_token($token) {
	$dbprefix = elgg_get_config('dbprefix');
	
	$query = "DELETE FROM {$dbprefix}users_apisessions
		WHERE token = :token";
	$params = [
		':token' => $token,
	];

	return (bool) elgg()->db->deleteData($query, $params);
}

/**
 * Remove expired tokens
 *
 * @return int Number of rows removed
 * @since 1.7.0
 */
function remove_expired_user_tokens() {
	$dbprefix = elgg_get_config('dbprefix');
	$time = time();

	return elgg()->db->deleteData("DELETE from {$dbprefix}users_apisessions
		where expires < $time");
}

/**
 * The auth.gettoken API.
 * This API call lets a user log in, returning an authentication token which can be used
 * to authenticate a user for a period of time. It is passed in future calls as the parameter
 * auth_token.
 *
 * @param string $username Username
 * @param string $password Clear text password
 *
 * @return string Token string or exception
 * @throws SecurityException
 * @access private
 */
function auth_gettoken($username, $password) {
	// check if username is an email address
	if (is_email_address($username)) {
		$users = get_user_by_email($username);

		// check if we have a unique user
		if (is_array($users) && (count($users) == 1)) {
			$username = $users[0]->username;
		}
	}

	// validate username and password
	if (true === elgg_authenticate($username, $password)) {
		$return['auth_token'] = create_user_token($username);
		$return['api_key'] = get_api_key();
		$return['gcm_sender_id'] = get_gcm_sender_id();
		$return['username'] = $username;
		if ($return) {
			return $return;
		}
	}

	throw new SecurityException(elgg_echo('SecurityException:authenticationfailed'));
}