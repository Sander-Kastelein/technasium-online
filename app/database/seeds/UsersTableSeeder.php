<?php

class UsersTableSeeder extends Seeder {

	public function run()
	{

		$sander = User::create([
			'email'=>'sanderkastelein@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name' => 'Sander Kastelein',
			'class' => '5F',
			'is_teacher'=>false
		]);

		$stephan = User::create([
			'email'=>'stephannijdam@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name' => 'Stephan Nijdam',
			'class' => '4A',
			'is_teacher'=>false
		]);

		$hidde = User::create([
			'email'=>'hiddezijlstra@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name' => 'Hidde Zijlstra',
			'class' => '5C',
			'is_teacher'=>false
		]);

		$semmi = User::create([
			'email'=>'semmidegerlier@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name'=>'Semmi Değerlier',
			'class'=>'5F',
			'is_teacher'=>false
		]);

		$anna = User::create([
			'email'=>'annasuleri@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name'=>'Anna Suleri',
			'class'=>'5C',
			'is_teacher'=>false
		]);

		$mahdad = User::create([
			'email'=>'mahdadnasrollah@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name'=>'Mahdad Nasrollah',
			'class'=>'5D',
			'is_teacher'=>false
		]);

		$sil = User::create([
			'email'=>'silleijstra@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name'=>'Sil Leijstra',
			'class'=>'5F',
			'is_teacher'=>false
		]);

		$julian = User::create([
			'email'=>'juliandejong@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name'=>'Julian de Jong',
			'class'=>'5F',
			'is_teacher'=>false
		]);




		$teacher = User::create([
			'email' => 'dardyhamburger@pj.nl',
			'password'=>Hash::make('qwerty'),
			'name'=>'Dardy Hamburger',
			'class'=>'Geen',
			'is_teacher'=>true
		]);



		$project = $teacher->createNewProject('Digitaliseer O&O','Momenteel wordt er bij O&O nog van alles op papier geregeld, maar het zou veel handiger zijn als dit digitaal kan. Ontwikkel een applicatie waarmee leraren en leerlingen gemakkelijk online O&O projecten kunnen bijhouden.');
		$pillendoos = $teacher->createNewProject('Verbetrde pillendoos', 'Veel oudere mensen hebben problemen met het nemen van hun medicijnen, ontwerp een nieuwe pillendoos waardoor zowel gebruikers als zorgmedewerkers het gemakkelijker krijgen.');
		$cjib = $teacher->createNewProject('Nieuwe website en App CJIB', 'Het CJIB wil graag vernieuwen, onderzoek en ontwikkel een website en app voor het CJIB.');
		


		$file = '';

		for($i = 0; $i < 3000000; $i++){
			$file += 'x';
		}

		$project->createNewFile('opdracht.docx',substr($file,0,strlen($file*2.5*(mt_rand() / mt_getrandmax()))));
		$project->createNewFile('beoordeling.docx',substr($file,0,strlen($file*0.5*(mt_rand() / mt_getrandmax()))));

		$pillendoos->createNewFile('opdracht.docx',substr($file,0,strlen($file*2.5*(mt_rand() / mt_getrandmax()))));
		$pillendoos->createNewFile('beoordeling.docx',substr($file,0,strlen($file*0.5*(mt_rand() / mt_getrandmax()))));

		$project->createNewFile('opdracht.docx',substr($file,0,strlen($file*2.5*(mt_rand() / mt_getrandmax()))));
		$project->createNewFile('beoordeling.docx',substr($file,0,strlen($file*0.5*(mt_rand() / mt_getrandmax()))));


		$teacher->createNewGroup('Technasium online',$project,[$hidde,$sander]);
		$teacher->createNewGroup('Elektronische pillendoos',$pillendoos,[$anna,$mahdad]);
		$teacher->createNewGroup('Modern CJIB',$cjib,[$stephan,$semmi]);


		$skills = [
			[
				'name'=>'Plannen & organiseren',
				'description_level_1'=>'De leerling kan een plan van aanpak voor een korte periode maken.',
				'description_level_2'=>'De leerling kan een plan van aanpak make nvoor de hele project periode.',
				'description_level_3'=>'De leerling kan een projectplan bedenken, schrijven en uitvoeren.'
			],
			[
				'name'=>'Kennisgerichtheid',
				'description_level_1'=>'De leerling kan informatie verzamelen en selecteren uit verschillende bronnen.',
				'description_level_2'=>'De leerling kan zijn ontwerp of onderzoek theoretisch onderbouwen en verantwoorden.',
				'description_level_3'=>'De leerling kan zich aantoonbaar verdiepen in de theorie die past bij het ontwerp of onderzoek.'
			],
			[
				'name'=>'Samenwerken',
				'description_level_1'=>'De leerling kan zijn sterke en zwakke punten benoemen bij het werken in een team.',
				'description_level_2'=>'De leerling kan samen met de teamgenoten een conflict tot een goed einde brengen.',
				'description_level_3'=>'De leerling heeft de verschillende wensen en belangen van de groep begrepen en kan daar mee omgaan.'
			],
			[
				'name'=>'Productgerichtheid',
				'description_level_1'=>'De leerling is gemotiveerd om een kwalitatief goed product te leveren.',
				'description_level_2'=>'De leerling is gemotiveerd om de beste oplossingen voor de opdrachtgever te bedenken en te realiseren.',
				'description_level_3'=>'De leerling kan de vraag van de opdrachtgever interpreteren en vertalen naar een gewenst product.'
			],
			[
				'name'=>'Procesgerichtheid',
				'description_level_1'=>'De leerling kan reflecteren op belangerijke momenten in het werkproces.',
				'description_level_2'=>'De leerling draagt creatieve oplossingen aan voor een probleem en houdt rekening met de opdrachtgever.',
				'description_level_3'=>'De leerling kan met kennis van bestaande oplossingen een nieuwe oplossing bedenken.'
			],
			[
				'name'=>'Inventiviteit',
				'description_level_1'=>'De leerling draagt fantasierijke oplossingen aan voor een probleem.',
				'description_level_2'=>'De leerling draagt creatieve oplossingen aan voor een probleem en houdt rekening met de opdrachtgever.',
				'description_level_3'=>'De leerling kan met kennis van bestaande oplossingen een nieuwe oplossing bedenken.'
			],
			[
				'name'=>'Doorzetten',
				'description_level_1'=>'Met hulp van de docent kan de leerling een tegenslag overwinnen.',
				'description_level_2'=>'In overleg met het team kan de leerling een tegenslag overwinnen.',
				'description_level_3'=>'De leerling kan zichzelf en het team motiveren om bij tegenslag toch door te gaan.'
			],
			[
				'name'=>'Individueel werken',
				'description_level_1'=>'De leerling kan een deeltaak in ht team zelfstandig uitvoeren en afronden.',
				'description_level_2'=>'De leerling kan zichelf aan het werk zetten ten dienste van het team.',
				'description_level_3'=>'De leerling neemt taken op zich en onderscheidt zich hiermee in het team.'
			]
		];


		foreach($skills as $skill){
			Skill::create($skill);
		}



	}

}