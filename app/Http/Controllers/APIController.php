<?php

namespace App\Http\Controllers;

use DB;
use Input;
use Illuminate\Http\Request;
use Config\Data;
use Carbon\Carbon;
use App\Models\Booking;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\BookingRegistered;

class APIController extends Controller {

	protected object $data;

	public function __construct() {
		$this->data = Data::get();
	}

	public function verify(Request $request) {
		$validator = Validator::make($request->all(), 
		[
			'date' => ['required', 'date', 'after_or_equal:' . Carbon::now()]
		]);

		if ($validator->fails()) {
			return ["Result" => "La date spécifiée est invalide. Veuillez consulter nos horaires d'ouvertures."];
		}

		$request->date = Carbon::parse($request->date)->setUnitNoOverflow('hour', $request->hour, 'day');

		foreach ($this->data->week as $key => $value) {
			if (!$value && strtolower($key) == strtolower((string) $request->date->format("l"))) {
				return ["Result" => "Vous ne pouvez pas réserver à cette date, nous vous invitons à consulter nos horaires d'ouvertures. (Observatoire fermé le week-end)"];
			}
		}

		if (Booking::isBooked($request->date) > $this->data->max_people) {
			return 
				[
					"Result" => "Navré mais ce créneau est déjà réservé.",
					"isBooked" => true
				];
		}

		return 
			[
				"Result" => "Ce créneau est libre",
				"isBooked" => false
			];
	}

	public function create(Request $request) {

		$validator = Validator::make($request->all(), 
		[
			'email' => 'required|email:rfc,strict',
			'hour' => ['required', 'integer', 'between:' .($this->data->start - 1). ',' .($this->data->end + 1)]
		]);

		if ($validator->fails()) {
			return ["Result" => "Veuillez vous assurer d'avoir rentré un email valide ainsi qu'un horaire d'ouverture correct."];
		}

		$verify = $this->verify($request);

		if (isset($verify['isBooked'])) {
			if (!$verify['isBooked']) {

				$request->date = Carbon::parse($request->date)->setUnitNoOverflow('hour', $request->hour, 'day');

				$answer = Booking::store($request);

				if ($answer[1]) {
					Mail::to($request->email)->send(new BookingRegistered(Booking::getBooking($answer[0])));

					return ["Result" => "Votre réservation a bien été enregistrée, un mail de confirmation vous a été envoyé."];
				}

				return ["Result" => "Une erreur est survenue lors de l'enregistrement de votre réservation. Veuillez réessayer."];
			}
		}

		return $verify;
	}

	function delete(Request $request) {

		if (!empty(Booking::getBooking($request->token))) {

			if (Booking::remove($request->token)) {
				return ["Result" => "Votre réservation a bien été supprimée. Nous sommes désolés de l'apprendre."];
			}

			return ["Result" => "Une erreur est survenue durant la suppression de votre réservation, veuillez réessayer."];
		} else {
			return ["Result" => "Token introuvable."];
		}
	}

	function data() {
		return response()->json($this->data);
	}
}