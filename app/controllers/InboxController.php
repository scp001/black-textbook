<?php

class InboxController extends BaseController {




    public function __construct()
    {
	    $this->beforeFilter('auth');
        $this->current_user = $this->get_CurrentUser();
        $this->table = DB::table('messages');
        $this->table2 = DB::table('message_replies');

    }
    private function get_CurrentUser()
    {
        if(Auth::check()){
            return Confide::user();
        }else{
            return Redirect::to('login');
        }
    }



    private function get_mes_by_id($id)
    {

        return $this->table->where('id','=',$id)->first();
    }

    private function get_replies_by_message_id($id , $onlylast = false)
    {

        if ($onlylast == true) {
            return $this->table2->where('mid','=',$id)->orderBy('id','DESC')->take(1)->get();
        }else{
            return $this->table2->where('mid','=',$id)->orderBy('id','DESC')->get();
        }
    }

    private function get_seen_status($message_id)
    {
        $message=$this->get_mes_by_id($message_id);
        if (!$message){
            return 0;
        }

        $last_replies =$this->get_replies_by_message_id($message_id,true);

        if (count($last_replies) == 0){
            //case if no replies

            //if you are the sender

            if ($message->from_user_id == $this->current_user->id)
            {
                return 0;
            }
            else
            {
                // you are not the sender.
                return $message->new;
            }

        }else {
            //case if there is a replay

            //if you are the last one who replay.

            if ($last_replies[0]->user_id == $this->current_user->id){

                return 0;

            }
            else
            {
                //you aren't last one who replay.

                return $message->new;

            }

        }


    }


    private function update_seen($message_id)
    {
        $message_id = intval($message_id);
        $setToSeen = $this->table->where('id','=',$message_id)->update(['new'=>(int)0]);

        $message=$this->get_mes_by_id($message_id);
        $last_replies =$this->get_replies_by_message_id($message_id,true);

        if (count($last_replies) == 0){
            //case if no replies

            //if you are the sender
            if ($message->from_user_id == $this->current_user->id)
            {
                return;

            }
            else
            {

                // you are not the sender.
                return $setToSeen;
            }

        }else {
            //case if there is a replay

            //if you are the last one who replay.

            if ($last_replies[0]->user_id == $this->current_user->id){

                return;

            }
            else
            {
                //you aren't last one who replay.

                return $setToSeen;

            }

        }


    }
    public function get_posted_data($posted_data)
    {
        $maindata = array('subject','message','user_id','mes');

        $data = array();

        foreach($maindata as $row)
        {
            if(isset($posted_data[$row]))
            {
                $data[$row]= $posted_data[$row];
            }
        }
        return $data;
    }

    public function send_to($subject,$text,$to_user_id,$mes=false)
    {
        //TODO :: emplement send mails to users when got a message or replay.
        /*$mailData = [
            'to_user_mail'=>User::where('id','=',$to_user_id)->first()->get('email'),
        ];*/

        //is a a new message

        if($mes == false){
            $action = $this->table->insert(
                [
                    'from_user_id'=>$this->current_user->id,
                    'to_user_id'=>$to_user_id,
                    'subject'=>$subject,
                    'message'=>$text,
                    'new'=>1,
                    'created_at'=>new Datetime(),
                    'updated_at'=>new Datetime(),
                ]
            );
            if ($action){
                /*Mail::send('emails.welcome', array('key' => 'value'), function($message)
                {
                    $message->to('foo@example.com', 'John Smith')->subject('Welcome!');
                });*/
                return Redirect::to('inbox')->with('success','you message has been sent');
            }else{
                return Redirect::back()->with('error','something went wrong #94848448');
            }
        }else{

            $action = $this->table2->insert(
                [
                    'mid'=>$mes,
                    'user_id'=>$this->current_user->id,
                    'message'=>$text,
                    'created_at'=>date('Y-m-d H:i:s',strtotime('now'))
                ]
            );
            $action2 = $this->table->where('id','=',$mes)->update(
                [
                    'new'=>1,
                    'updated_at'=>new DateTime()
                ]
            );

            if ($action && $action2){

                return Redirect::to('inbox')->with('success','reply has been sent');
            }else {
                return Redirect::back()->with('error','something went wrong $iasa9da9');
            }

        }

    }

    public function do_replay_mes()
    {

        $data = $this->get_posted_data(Input::all());


        if (!isset($data['subject'])){
            $data['subject']= 'NO SUBJECT';
        }
        if ( isset($data['message']) && isset($data['user_id']) && isset($data['mes']))
        {
            return $this->send_to($data['subject'],$data['message'],$data['user_id'],intval($data['mes']));
        }

    }


    public function do_send_mes()
    {
        $data = $this->get_posted_data(Input::all());
        if (isset($data['subject']) && isset($data['message']) && isset($data['user_id']))
        {
            return $this->send_to($data['subject'],$data['message'],$data['user_id']);
        }

    }

    public function can_access($from,$to,$id)
    {
        if ($from == $id || $to == $id){
            return true;
        }else{
            return false;
        }

    }



    public function anyIndex()
    {
        if(isset($_POST['action'])){
            switch($_POST['action']):
                case 'do_send_mes':
                    return $this->do_send_mes();
                    break;

                case 'do_replay_mes':
                    return $this->do_replay_mes();
                    break;


            endswitch;

        }

        if(isset($_GET['action'])){
            switch($_GET['action']) :

                case 'get_mes':

                    return $this->get_mes(intval($_GET['mes']));

                    break;

                case 'send_mes':

                    if(isset($_GET['uid']) && $_GET['uid'] != $this->current_user->id)

                    {

                        return $this->get_send_mes(intval($_GET['uid']));

                    }else {

                        return 'something went wrong #qiqwiqw9122';

                    }

                    break;

                case 'replay_mes':

                    if(isset($_GET['mes']))

                    {

                        return $this->get_replay_mes(intval($_GET['mes']));

                    }else {

                        return 'something went wrong #qiqwiqw9122';

                    }

                    break;

            endswitch;


            return 'this is handle action';

        }else{
            return $this->draw_inbox();
        }
    }


    private function get_inbox_msgs()
    {
        $msgs = $this->table->where('from_user_id','=',$this->current_user->id)->orWhere('to_user_id','=',$this->current_user->id)->orderBy('updated_at','DESC')->get();
        return $msgs;
    }


    public function draw_inbox()
    {
        $messages = $this->get_inbox_msgs();
        $counter = 1;

        $outPut = '<table class="table"><thead><th>Num</th><th>Subject</th><th>Users</th><th>Last Update</th></thead><tbody>';
        foreach ($messages as $message ) {

            $message->username = User::get_user_meta($message->from_user_id,'frist_name');
            $message->unread = $this->get_seen_status($message->id) == 1 ?'new':'';
            $message->url = URL::to('inbox?action=get_mes&mes='.$message->id);
            //$message->url = url('inbox',array('action'=>'get_mes','mes'=>$message->id));
            if ($message->from_user_id == $this->current_user->id){

                $message->username = 'me';

            }

            $outPut .= sprintf('<tr class="%s" data-url="%s"><td>%d</td><td>%s</td><td>%s</td><td>%s</td></tr>',$message->unread,$message->url,$counter++,$message->subject,$message->username,$message->updated_at );
        }
        $outPut .='</tbody></table>';
        return View::make('inbox.inbox',compact('outPut'));

    }



    private function get_mes($message_id)
    {
        $message_id = intval($message_id);
        $replay_message_url = URL::to('inbox?action=replay_mes&mes='.$message_id);
        $message_data = $this->get_mes_by_id($message_id);
        $replies = $this->get_replies_by_message_id($message_id);

        if($this->can_access($message_data->from_user_id,$message_data->to_user_id,$this->current_user->id)){

            $outPut = '<div class="entry">';

            //print replay button
            $outPut .='<a href="'.$replay_message_url.'"><span style="padding:10px 20px; background-color:transparent; border:1px solid #FF5C00; color:#FF5C00; font-size:10px;float: right;">Reply Message</span></a>';

            //print the main message
            $outPut .="<h3 style=\"margin:5px 0 30px;\">".$message_data->subject."</h3>";
            $outPut .="<p>".$message_data->message."</p>";
            $outPut .='<div class="divider"></div>';
            //print replies
            if (count($replies) >0)
            {
                foreach($replies as $mes){


                    $outPut .= sprintf('<blockquote class="clearfix"><p>%s</p><div class="pull-right" style="text-align:right; font-style:normal; font-weight:bold;">– <a href="%s">%s</a><br>%s</div></blockquote><div class="divider"></div>',$mes->message,URL::to('/profile/?uid='.$mes->user_id),User::get_user_meta($mes->user_id,'frist_name'),$mes->created_at);

                }

            }
            $outPut .='<a href="'.$replay_message_url.'"><span style="padding:10px 20px; background-color:transparent; border:1px solid #FF5C00; color:#FF5C00; font-size:10px;float: right;">Reply Message</span></a>';
            $outPut .='</div>';
            $this->update_seen($message_data->id);
            return View::make('inbox.inbox',compact('outPut'));

        }


    }


    public function get_replay_mes($message_id)
    {
        $message = $this->get_mes_by_id($message_id);


        if (!$message || !$this->can_access($message->from_user_id,$message->to_user_id,$this->current_user->id)){
            return false;
        }
        $userToReplay = $message->from_user_id == $this->current_user->id ? $message->to_user_id :$message->from_user_id;
        $outPut = sprintf('<form method="POST" action="%s" id="contactForm">',URL::to('inbox'));
        $outPut .=sprintf('<h4><label>Responding to : %s</label></h4><br>',$message->subject);
        $outPut .='<p><label>Message :</label><br><textarea id="contact_message" name="message" cols="50"></textarea></p>';
        $outPut .='<input type="hidden" name="action" value="do_replay_mes">';
        $outPut .=sprintf('<input type="hidden" name="user_id" value="%d">',intval($userToReplay));
        $outPut .=sprintf('<input type="hidden" name="mes" value="%d">',intval($message->id));
        $outPut .='<p><input class="btn btn-primary"type="submit" value="Send Reply"></p>';
        $outPut .='</form>';
        $outPut .='';
        return View::make('inbox.inbox',compact('outPut'));
    }





    public function get_send_mes($user_id)
    {
        $user = User::where('id','=',$user_id)->first();

        if (!$user){
            return false;
        }
        $outPut = sprintf('<form method="POST" action="%s" id="contactform">',URL::to('inbox'));
        $outPut .=sprintf('<h3><label>To : %s</label></h3>',$user->username);
        $outPut .='<p><label> subject : </label><br><input type="text" name="subject" size="40"></p>';
        $outPut .='<p><label>Message :</label><br><textarea name="message" cols="70" rows="7"></textarea></p>';
        $outPut .='<input type="hidden" name="action" value="do_send_mes">';
        $outPut .=sprintf('<input type="hidden" name="user_id" value="%d">',intval($user_id));
        $outPut .='<p><input class="btn btn-primary" type="submit" value="Send Message"></p>';
        $outPut .='</form>';
        $outPut .='';
        return View::make('inbox.inbox',compact('outPut'));
    }















}