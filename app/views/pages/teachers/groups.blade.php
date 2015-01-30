<h1>Groepen</h1>
<a class="btn btn-info" href="{{action('TeacherController@getNewGroup')}}">Nieuwe Groep</a>

<table id="groups" class="table table-hover">
	<thead>
		<tr>
			<th>Naam</th>
			<th>Aantal leerlingen</th>
			<th>Docenten</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		@foreach($groups as $group)
			<tr>
			<td><a href="{{action('StudentController@getGroup',['id'=>$group->id])}}">{{$group->name}}</a></td>
			<td>{{count($group->students)}}</td>
			<td>{{$group->getTeachersAsString()}}</td>
			<td><a onclick="return confirm('Weet u zeker dat u deze groep wilt verwijderen?');" href="{{action('TeacherController@getDeleteGroup',[$group->id])}}"><i class="fa fa-trash"></i></a></td>
			</tr>
		@endforeach
	</tbody>
</table>
<script>
	$(function(){
		$(document).ready( function () {
		    $('#groups').DataTable();
		} );
	});
</script>