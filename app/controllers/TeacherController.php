<?php
/**
 * Created by PhpStorm.
 * User: Sander
 * Date: 10-1-2015
 * Time: 14:10
 */
use \Technasium\Alert;


Class TeacherController extends BaseController{

    public function getDashboard(){
        $this->layout->page = View::make('pages.teachers.dashboard');
    }

    public function getGroups(){
    	$user = Auth::user();
    	$groups = $user->groups;
    	$this->layout->page = View::make('pages.teachers.groups')->with('groups',$groups);

    }

    public function getGroup($id){
    	$group = Group::find($id);
    	$user = Auth::user();
    	if(!$group->hasUser($user)) return Redirect::action('TeacherController@getGroups');
    	$this->layout->page = View::make('pages.teachers.group')->with('group',$group);
    }

    public function getNewGroup(){
    	$this->layout->page = View::make('pages.teachers.new_group');
    }

    public function postNewGroup(){
    	$user = Auth::user();
    	$students = Input::get('students');
    	foreach($students as $k=>$v){
    		$students[$k] = User::find($v);
    	}

        $project = Project::find(Input::get('project'));

        if(!$project){
            AlertRepo::add(new Alert('danger','Project niet gevonden!'));
            return Redirect::action('TeacherController@getGroups');
        }

    	$group = $user->createNewGroup(Input::get('name'),$project,$students);
    	   
        AlertRepo::add(new Alert('success','Nieuwe groep <strong>'.$group->name.'</strong> aangemaakt.'));
        return Redirect::action('TeacherController@getGroups');
    }

    public function getDeleteGroup($id){
        $group = Group::find($id);
        $user = Auth::user();
        if(!$group){
            AlertRepo::add(new Alert('danger','Groep bestaat niet.'));
            return Redirect::action('TeacherController@getGroups');
        }
        if(!$group->hasTeacher($user)) return Redirect::action('TeacherController@getGroups');
        $name = $group->name;
        $group->forceDelete();
        AlertRepo::add(new Alert('success','Groep <strong>'.$name.'</strong> verwijderd.'));
        return Redirect::action('TeacherController@getGroups');
    }


    public function getProjects(){
        $projects = Project::all();
        $this->layout->page = View::make('pages.teachers.projects')->with('projects',$projects);
    }

    public function getNewProject(){
        $this->layout->page = View::make('pages.teachers.new_project');
    }

    public function postNewProject(){
        $user = Auth::user();
        $name = Input::get('name');
        $description = Input::get('description');

        if(empty($description) || empty($name)){
            AlertRepo::add(new Alert('warning','gelieve alle velden in te vullen'));
            return Redirect::action('TeacherController@getNewProject');
        }
        $project = $user->createNewProject($name,$description);
        return Redirect::action('TeacherController@getProject',['id'=>$project->id]);

    }

    public function getProject($id){
        $project = Project::find($id);
        $this->layout->page = View::make('pages.teachers.project')->with('project',$project);
    }

    public function getDeleteProject($id){
        $project = Project::find($id);
        $user = Auth::user();
        if(!$project){
            AlertRepo::add(new Alert('danger','Groep bestaat niet.'));
            return Redirect::action('TeacherController@getProjects');
        }
        if($project->owner_id != $user->id){
            AlertRepo::add(new Alert('danger','Alleen de beheerder mag een project verwijderen!'));
            return Redirect::action('TeacherController@getProjects');
        }
        $name = $project->name;

        foreach($project->groups as $group){
            $group->forceDelete();
        }

        $project->forceDelete();
        AlertRepo::add(new Alert('success','Project <strong>'.$name.'</strong> verwijderd.'));
        return Redirect::action('TeacherController@getProjects');
    }

    public function getProjectFileDownload($id){
        $file = ProjectFile::find($id);
        header('Content-Description: File Transfer');
        header('Content-Type: '.$file->mime);
        header('Content-Disposition: attachment; filename="'.$file->name.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '.$file->size);
        die($file->file);
    }

    public function getUploadProjectFile($id){
        $this->layout->page = View::make('pages.teachers.upload_project_file')->with('projectId',$id);
    }

    public function postUploadProjectFile(){
        $project = Input::get('project_id');
        $project = Project::find($project);
        if(!$project){  
            AlertRepo::add(new Alert('danger','Project niet gevonden.'));
            return Redirect::action('TeacherController@getUploadProjectFile',['id'=>$project->id]);
        }
        if(!Input::hasFile('file')){
            AlertRepo::add(new Alert('danger','Kon file niet vinden.'));
            return Redirect::action('TeacherController@getUploadProjectFile',['id'=>$project->id]);
        }
        $file = Input::file('file');

        $hidden = (Input::get('hidden')==='on');
        $size = $file->getSize();
        $mime = $file->getMimeType();
        $name = $file->getClientOriginalName();
        $binary = file_get_contents($file);

        $file = $project->createNewFile($name,$binary,$size,$hidden,$mime);

        AlertRepo::add(new Alert('success','File geupload'));
        return Redirect::action('TeacherController@getProject',['id'=>$project->id]);
    }

    public function getDeleteProjectFile($id){
        $projectFile = ProjectFile::find($id);
        $projectId = $projectFile->project->id;
        $projectFile->forceDelete();
        AlertRepo::add(new Alert('success','Bestand verwijderd'));
        return Redirect::action('TeacherController@getProject',['id'=>$projectId]);
    }

}