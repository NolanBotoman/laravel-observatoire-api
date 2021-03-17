<div>
	<h1>Observatoire Camille Flammarion</h1>
	<hr>
	<p>Vous avez bien réservé une session d'observation le {{ $booking->date }}</p>
	<p>En cas de problème il est possible d'annuler la réservation en cliquant sur ce <a href="https://observatoire-laravel.herokuapp.com/réservation/annulation/{{ $booking->token }}">lien</a></p>
</div>