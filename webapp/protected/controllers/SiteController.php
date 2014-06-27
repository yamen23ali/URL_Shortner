<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// get object of our model class
		$model=new UrlMapping;
		$model->unsetAttributes();  // clear any default values
		$keyExist=true;
        //==== if it's request for redirect 
		if (isset($_GET['i']))
		{
			// get the code
            $model->URL_Key = $_GET['i'];
			// get the url associated with this code
			$result=$model->search();
			// if we found the url in our DB redirect
			if(sizeOf($result)>0)
				$this->redirect($result->URL);
			else
				$keyExist=false;
		}
		
		//====== if it's not redirect request , render our main page
		$this->render('index',array('keyExist'=>$keyExist));
	}
	
	//===================== Get Short URL ===========//
	/*
	* This function will generate unique code associate it with the url entered by user 
	* and store ( code , url ) in our DB to use them to ridirect later
	* the call of this function will be via AJAX
	*/
	public function actionGetShortedUrl()
	{
		$model=new UrlMapping;
		$model->unsetAttributes();  // clear any default values
		$message="";
		// get url entered by user
		$url=$_POST['url'];
		
		if(filter_var($url, FILTER_VALIDATE_URL)){  // check if it's valid url
		
			// check if the exact smae url exist in our DB , then use the already stored code
			$model->URL=$url;
			
			$result=$model->search();
			if(sizeOf($result)!=0){
				$key=$result->URL_Key;
				$message= "<p><font color='green'>Your Short URL IS : </p></font>".
						 '<p><a href="'.$key.'">ys.gopagoda.com/'.$key.'</a></p>';
			}
			else {
				$tries=0;
				// try ( 100 time ) to generate unique code , the generation will stop once we have code that is not in our DB
				// this loop is just to make sure we won't have duplicated codes
				while($tries<100){
						$key=uniqid(); // get unique code based on timpe stanp and micro seconds
						$model->URL_Key =$key ;
						$result=$model->search(); // check if code already exist
						if(sizeOf($result)==0)
						{
							$model->URL=$url;
							if($model->save()) {// save our ( code , url ) duality and print the resulted short url to user
									$shortUrl="ys.gopagoda.com/".$key;
									$message= "<p><font color='green'>Your Short URL IS : </p></font>".
											  '<p><a href="'.$key.'">ys.gopagoda.com/'.$key.'</a></p>';
								}
							else
								$message= "<font color='red'>Technical Error , Please Try Again !!</font>";
							break;
						}
						$tries++;
				}
				if($tries>=100) // in case we failed to generate unique code !!
					$message= "<font color='red'>Sorry Unable To Provide Short URL's Any More .</font>";
			}
		}
		else{
			$message= "<font color='red'>Please Enter A valid URL .</font>";
		}
		echo $message;
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}