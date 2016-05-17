<?php

class BaseController extends Controller {



    /**
    $fields= [
    ['type'=>'form_start',
    'method'=>'post',
    'action'=>URL::to('books/store')
    ],
    [
    'type'=>'text',
    'name'=>'title',
    'placeholder'=>'Book Title '
    ],
    [
    'type'=>'text',
    'name'=>'author',
    'placeholder'=>'Book author '
    ],
    [
    'type'=>'text',
    'name'=>'field',
    'placeholder'=>'Book field '
    ],
    [
    'type'=>'text',
    'name'=>'course',
    'placeholder'=>'Book course '
    ],
    [
    'type'=>'hidden',
    'name'=>'_token',
    'value'=>csrf_token()
    ],
    [
    'type'=>'submit',
    'placeholder'=>'Add',
    'class'=>'btn-info col-sm-offset-4 col-sm-4 text-bold'
    ],
    ['type'=>'form_end']
    ]
     */





    /**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

    /**
     * @param array $fields
     * @return string
     *
     *
     *
     * case select $field[data] should be array of objects option and value
     */
    public function drawForm($fields=[]){
        $outPut = '';
        foreach ($fields as $field)
        {
            if (!isset($field['id'])){
                $field['id']='';
            }
            if (!isset($field['checked'])){
                $field['checked']='';
            }
            if (!isset($field['class'])){
                $field['class']='';
            }
            if (!isset($field['value'])){
                $field['value']='';
            }
            if ($field['type'] != ''):
                switch ($field['type']):
                    case 'form_start':
                        $outPut .= sprintf('<form role="form" accept-charset="UTF-8" method="%s" action="%s" class="form-horizontal clearfix" enctype="multipart/form-data">',$field['method'],$field['action']);
                        break;
                    case 'text':
                    case 'password':
                    case 'email':
                        $outPut .= '<div class="form-group">';
                        $outPut .= sprintf('<label class="col-sm-2 control-label" for="%s">%s</label>',$field['placeholder'],$field['placeholder']);
                        $outPut .= '<div class="col-sm-10">';
                        $outPut .= sprintf('<input type="%s" placeholder="%s" id="%s" class="form-control %s" name="%s" value="%s">',$field['type'],$field['placeholder'],$field['id'],$field['class'],$field['name'],$field['value']);
                        $outPut .= '</div></div>';
                        break;
                    case 'old_pic':
                        $outPut .= '<div class="form-group">';
                        $outPut .= sprintf('<label class="col-sm-2 control-label" for="%s">%s</label>',$field['placeholder'],$field['placeholder']);
                        $outPut .= '<div class="col-sm-10">';
                        $outPut .= sprintf('<div id="imgoldbox"><img src="%s" class="profilethunb"></div><span id="removeoldpic" class="btn btn-sm btn-danger">REMOVE <i class="fa fa-fw fa-trash-o"></i></span>',$field['img']);
                        $outPut .= '</div></div>';
                    break;
                    case 'checkbox':
                        $outPut .= sprintf('<div class="form-group"><div class="col-md-8 col-md-offset-2"><label class="checkbox" for="%s">%s<input type="checkbox" value="%s" id="%s" name="%s" tabindex="1" %s></label></div></div>',$field['id'],$field['placeholder'],$field['value'],$field['id'],$field['name'],$field['checked']);
                    break;
                    case 'heading':
                        $outPut .= sprintf('<br><h3>%s</h3><hr>',$field['placeholder']);
                        break;
                    case 'submit':
                        $outPut .=sprintf('<button type="submit" class="btn %s"><b>%s</b></button>',$field['class'],$field['placeholder']);
                        break;
                    case 'file':
                        $outPut .=sprintf('<div class="form-group"><label class="col-sm-2 control-label %s" for="%s">%s</label> <div class="col-sm-10"><input type="file" data-id="%s" id="fileinput" name="%s" accept="image/*"><p class="help-block">%s.</p></div></div>',$field['class'],$field['name'],$field['placeholder'],$field['id'],$field['name'],@$field['help']);
                        break;
                    case 'select':
                        $outPut .= '<div class="form-group">';
                        $outPut .=sprintf("<label for=\"%s\" class=\"col-sm-2 control-label\">%s</label><div class=\"col-sm-10\">",$field['placeholder'],$field['placeholder']);
                        $outPut .=sprintf('<select name="%s" id="%s" class="form-control  %s">',$field['name'],$field['id'],$field['class']);
                        if (isset($field['data']) && is_array($field['data']))
                        {
                            foreach($field['data'] as $row )
                            {
                                if(isset($field['value']) && $field['value'] == $row->value)
                                {
                                    $selected = 'selected ="selected"';
                                }else {
                                    $selected = '';
                                }
                                $outPut .=sprintf('<option value="%s" %s>%s</option>',$row->value,$selected,$row->option);
                            }
                        }
                        $outPut .= '</select></div></div>';
                        break;
                    case 'typeahead':
                        $outPut .= '<div class="form-group">';
                        $outPut .= sprintf('<label class="col-sm-2 control-label" for="%s">%s</label>',$field['placeholder'],$field['placeholder']);
                        $outPut .= sprintf('<div class="col-sm-10" id="type%s">',$field['id']);
                        $outPut .= sprintf('<input type="%s" placeholder="%s" id="%s" class="form-control typeahead %s" name="%s" value="%s">',$field['type'],$field['placeholder'],$field['id'],$field['class'],$field['name'],$field['value']);
                        $outPut .= '</div></div>';
                        ?>

<?php
                        break;
                    case 'hidden':
                        $outPut .=sprintf('<input type="hidden" name="%s" value="%s">',$field['name'],$field['value']);
                        break;
                    case 'form_end':
                        $outPut .= '</form>';
                        break;
                endswitch;
            endif;

        }
        return $outPut;

    }

    //TODO :: resolve location using google maps api.
    //URL https://maps.googleapis.com/maps/api/geocode/json?address=saqr+korish+maadi+cairo+egypt+egypt+cairo




}
