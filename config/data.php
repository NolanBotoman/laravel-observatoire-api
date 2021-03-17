<?php

namespace Config;

class Data {

	public static function get() {
		return (object) [
			"week" => [ 
				"monday" => true, 
				"tuesday" => true, 
				"wednesday" => true, 
				"thursday" => true, 
				"friday" => true, 
				"saturday" => false,
				"sunday" => false
			],
			"start" => 9,
			"end" => 18,
			"duration" => 2,
			"max_people" => 1
		];
	}
}