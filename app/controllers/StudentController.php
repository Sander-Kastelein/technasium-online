<?php
/**
 * Created by PhpStorm.
 * User: Sander
 * Date: 10-1-2015
 * Time: 14:09
 */
use \Technasium\Alert;

Class StudentController extends BaseController{

    public function getDashboard(){
        $user = Auth::user();
        $amountOfComments = 0;
        $amountOfFiles = 0;
        $pesIds = [];
        $pes = PersonalEvaluation::where('user_id',$user->id)->get();
        foreach($pes as $pe){
            $pesIds[] = $pe->id;
            $amountOfComments++;
        }

        $amountOfFiles = GroupFile::where('user_id',$user->id)->count();


        $comment = PersonalEvaluationComment::whereIn('personal_evaluation_id',$pesIds)->orderBy('created_at','DESC')->first();
        $this->layout->page = View::make('pages.students.dashboard')->with('comment',$comment)->with('amountOfComments',$amountOfComments)->with('amountOfFiles',$amountOfFiles);
    }
    
    public function getGroups(){
    	$groups = Auth::user()->groups;
    	$this->layout->page = View::make('pages.students.groups')->with('groups',$groups);
    }

    public function getGroup($id){
    	$group = Group::find($id);
    	$user = Auth::user();
    	if(!$group->hasUser($user)) return Redirect::action('StudentController@getGroups');
    	$this->layout->page = View::make('pages.students.group')->with('group',$group);
    }

    public function getProjectFileDownload($id){
        $file = ProjectFile::find($id);
        $user = Auth::user();
        if(!$file->project->hasStudent($user)){
            AlertRepo::add(new Alert('danger','Je hebt geen toegang tot deze file'));
            return Redirect::action('StudentController@getDashboard');
        }

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

    public function getUploadFile($id){
        $group = Group::find($id);
        $this->layout->page = View::make('pages.students.upload_file')->with('group',$group);
    }

    public function postUploadFile(){
        $group = Group::find(Input::get('group_id'));
        $user = Auth::user();

        if(!Input::hasFile('file')){
            AlertRepo::add(new Alert('danger','Kon file niet vinden.'));
            return Redirect::action('StudentController@getUploadFile',['id'=>$group->id]);
        }
        $file = Input::file('file');

        $size = $file->getSize();
        $mime = $file->getMimeType();
        $name = $file->getClientOriginalName();
        $binary = file_get_contents($file);

        $file = $group->createNewFile($name,$binary,$size,$user->id,$mime);

        AlertRepo::add(new Alert('success','File geupload'));
        return Redirect::action('StudentController@getGroup',['id'=>$group->id]);

    }

    public function getFileDownload($id,$groupFileId){
        $file = GroupFile::findOrFail($groupFileId);

        $user = Auth::user();
        if(!$file->group->hasStudent($user)){
            AlertRepo::add(new Alert('danger','Je hebt geen toegang tot deze file'));
            return Redirect::action('StudentController@getDashboard');
        }

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

    public function getNewPersonalEvaluation($id){
        $group = Group::findOrFail($id);
        $user = Auth::user();
        $this->layout->page = View::make('pages.students.new_personal_evaluation')->with('group',$group)->with('user',$user);
    }

    public function postNewPersonalEvaluation($id){
        $group = Group::findOrFail($id);
        $user = Auth::user();

       $pe = new PersonalEvaluation();
       $pe->user_id = $user->id;
       $pe->group_id = $group->id;
       $pe->content = Input::get('html');
       $pe->title = Input::get('title');
       $pe->class = $user->class;
       $pe->save();

       return Redirect::action('StudentController@getPersonalEvaluation',['id'=>$pe->id]);
    }

    public function getPersonalEvaluation($id){
        $user = Auth::user();
        $pe = PersonalEvaluation::findOrFail($id);

        if($pe->user_id !== $user->id){
            //Fail
            return Redirect::action('StudentController@showGroups');
        }

        $this->layout->page = View::make('pages.students.pe')->with('pe',$pe);
    }

    public function getPersonalEvaluations(){
        $user = Auth::user();
        $this->layout->page = View::make('pages.students.pes')->with('pes',$user->personalEvaluations);
    }

    public function postAddComment($id){
        $pe = PersonalEvaluation::findOrFail($id);
        $user = Auth::user();



        if($pe->user->id != $user->id) return Redirect::to('/');

        $comment = new PersonalEvaluationComment();
        $comment->personal_evaluation_id = $pe->id;
        $comment->body = Input::get('html');
        $comment->owner_id = $user->id;
        $comment->save();

        return Redirect::action('StudentController@getPersonalEvaluation',['id'=>$id]);
    }




}