<?php

namespace App\Models;
use Dirape\Token\Token;
use DB;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {

	public static function isBooked($date) {
		return count(DB::table('booking')->where('date', $date)->get());
	}

	public static function getBooking($token) {
		return DB::table('booking')->where('token', $token)->get();
	}

	public static function store($booking) {
		$token = (new Token())->Unique('booking', 'token', 16);

		return [$token, DB::table('booking')->insert(['token' => $token, 'email' => $booking->email, 'date' => $booking->date])];
	}

	public static function remove($token) {
		return DB::table('booking')->where('token', $token)->delete();
	}

}