<h3>Bonjour,</h3>

<p>Un événement à été {{$data->event}} dans votre calendrier :</p>
<ul>
	<li>Début :<b> le {{$data->day}} à {{$data->ad}}</b></li>
	<li>Fin :<b> le {{$data->day}} à {{$data->af}}</b></li>
	<li>Type :<b> {{$data->type}}</b></li>
	<li>Sport :<b> {{$data->sport}}</b></li>
	@if(!empty($data->categorie))
		<li>Catégorie :<b> {{$data->categorie}}</b></li>
	@endif
	<li>Lieu :<b> {{$data->lieu}}</b></li>
	<li>Description :<b> {{$data->description}}</b></li>
	<li>Crée par :<b> {{$data->nom}}</b></li>
</ul>